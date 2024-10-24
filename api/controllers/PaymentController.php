<?php

require_once __DIR__ . '/../utils/LuhnValidator.php';
require_once __DIR__ . '/../utils/ChangeCalculator.php';
require_once __DIR__ . '/../vendor/bank.php';
require_once __DIR__ . '/../../config/db.php';

class PaymentController
{
    public function handleCardPayment($data)
    {
        // verificamos que los campos requeridos estén presentes
        if (!isset($data['card_num']) || !isset($data['amount'])) {
            return [
                "success" => false,
                "message" => "Tipo de pago no reconocido, faltan parámetros."
            ];
        }

        // validamos el número de tarjeta
        $cardNumber = $data['card_num'];
        if (!LuhnValidator::validate($cardNumber)) {
            return [
                "success" => false,
                "message" => "Número de tarjeta inválido."
            ];
        }

        // simulación de respuesta del banco
        $response = simulateBankResponse($data['amount']);

        if ($response['success']) {
            // Insertar en TransaccionTarjeta
            $tarjetaInsert = $this->insertCardTransaction($cardNumber, $response['message']);

            if ($tarjetaInsert['success']) {
                // insertar en Transaccion (estado 'ok')
                $transactionId = $this->insertTransaction($data['amount'], 'ok', $tarjetaInsert['id']);

                return [
                    "success" => true,
                    "message" => "Pago con tarjeta procesado correctamente. ",
                    "transaction_id" => $transactionId
                ];
            } else {
                return [
                    "success" => false,
                    "message" => $tarjetaInsert['message']
                ];
            }
        } else {
            // insertar en Transaccion (estado 'ko')
            $transactionId = $this->insertTransaction($data['amount'], 'ko');

            return [
                "success" => false,
                "message" => $response['error'],
                "transaction_id" => $transactionId
            ];
        }
    }

    public function handleCashPayment($data)
    {
        // verificamos que los campos requeridos estén presentes
        if (!isset($data['coin_types']) || !isset($data['amount'])) {
            return [
                "success" => false,
                "message" => "Tipo de pago no reconocido, faltan parámetros."
            ];
        }

        // si el método se llama correctamente, calculamos el cambio
        $amount = $data['amount'];
        $coinTypes = $data['coin_types'];
        $changeCalculator = new ChangeCalculator();
        $change = $changeCalculator->calculate($amount, $coinTypes);

        if (!$change['success']) {
            // si el cambio es incorrecto, guardamos la transacción como 'ko'
            $transactionId = $this->insertTransaction($amount, 'ko', null, null);

            return [
                "success" => false,
                "message" => "Error en el pago en efectivo. Cambio incorrecto.",
                "transaction_id" => $transactionId
            ];
        }

        // si el cambio es correcto, guardamos la transacción como 'ok'
        $efectivoInsert = $this->insertCashTransaction($coinTypes, $change);

        if ($efectivoInsert['success']) {
            $transactionId = $this->insertTransaction($amount, 'ok', null, $efectivoInsert['id']);

            return [
                "success" => true,
                "change" => $change,
                "transaction_id" => $transactionId
            ];
        } else {
            return [
                "success" => false,
                "message" => $efectivoInsert['message']
            ];
        }
    }

    private function insertCardTransaction($cardNumber, $responseMessage)
    {
        global $pdo;

        $sql = "INSERT INTO TransaccionTarjeta (numeroTarjeta, respuestaBanco) 
                VALUES (:numeroTarjeta, :respuestaBanco)";

        $stmt = $pdo->prepare($sql);
        if (
            $stmt->execute([
                ':numeroTarjeta' => $cardNumber,
                ':respuestaBanco' => (bool) $responseMessage
            ])
        ) {
            return [
                "success" => true,
                "message" => "Transacción de tarjeta guardada correctamente.",
                "id" => $pdo->lastInsertId()
            ];
        }

        return [
            "success" => false,
            "message" => "Error al guardar la transacción de tarjeta."
        ];
    }

    private function insertCashTransaction($coinTypes, $change)
    {
        global $pdo;

        $sql = "INSERT INTO TransaccionEfectivo (monedas, devolucion) 
                VALUES (:monedas, :devolucion)";

        $stmt = $pdo->prepare($sql);
        if (
            $stmt->execute([
                ':monedas' => json_encode($coinTypes),
                ':devolucion' => $change['amount'] / 100
            ])
        ) {
            return [
                "success" => true,
                "message" => "Transacción en efectivo guardada correctamente.",
                "id" => $pdo->lastInsertId()
            ];
        }

        return [
            "success" => false,
            "message" => "Error al guardar la transacción en efectivo."
        ];
    }

    private function insertTransaction($amount, $status, $tarjetaId = null, $efectivoId = null)
    {
        global $pdo;

        $sql = "INSERT INTO Transaccion (cantidad, status, transaccion_tarjeta_id, transaccion_efectivo_id) 
                VALUES (:cantidad, :status, :transaccion_tarjeta_id, :transaccion_efectivo_id)";

        $stmt = $pdo->prepare($sql);
        if (
            $stmt->execute([
                ':cantidad' => $amount,
                ':status' => $status,
                ':transaccion_tarjeta_id' => $tarjetaId,
                ':transaccion_efectivo_id' => $efectivoId
            ])
        ) {
            return [
                "success" => true,
                "message" => "Transacción guardada correctamente.",
                "id" => $pdo->lastInsertId()
            ];
        }

        return [
            "success" => false,
            "message" => "Error al guardar la transacción."
        ];
    }
}
