<?php

require_once __DIR__ . '/../utils/LuhnValidator.php';
require_once __DIR__ . '/../utils/ChangeCalculator.php';
require_once __DIR__ . '/../vendor/bank.php';
require_once __DIR__ . '/../../config/db.php';


class PaymentController
{
    public function handleCardPayment($data)
    {
        // Verificamos que los campos requeridos estén presentes
        if (!isset($data['card_num']) || !isset($data['amount'])) {
            return [
                "success" => false,
                "message" => "Tipo de pago no reconocido, faltan parámetros."
            ];
        }

        // Validamos el número de tarjeta
        $cardNumber = $data['card_num'];
        if (!LuhnValidator::validate($cardNumber)) {
            return [
                "success" => false,
                "message" => "Número de tarjeta inválido."
            ];
        }

        // Simulación de respuesta del banco
        $response = simulateBankResponse($data['amount']);
        //return $response;

        if ($response['success']) {
            // Insertar en TransaccionTarjeta
            $tarjetaId = $this->insertCardTransaction($cardNumber, $response['message']);

            // Insertar en Transaccion
            $transactionId = $this->insertTransaction($data['amount'], 'ok', $tarjetaId);

            return [
                "success" => true,
                "transaction_id" => $transactionId
            ];
        } else {
            // Insertar en Transaccion (con estado ko)
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
        // Verificamos que los campos requeridos estén presentes
        if (!isset($data['coin_types']) || !isset($data['amount'])) {
            return [
                "success" => false,
                "message" => "Tipo de pago no reconocido, faltan parámetros."
            ];
        }

        // Si el método se llama correctamente, calculamos el cambio
        $amount = $data['amount'];
        $coinTypes = $data['coin_types'];
        $changeCalculator = new ChangeCalculator();
        $change = $changeCalculator->calculate($amount, $coinTypes);

        //return $change;
        // Suponiendo que también quieras insertar en la base de datos
        $efectivoId = $this->insertCashTransaction($coinTypes, $change);

        // Insertar en Transaccion (asumiendo que el estado es 'ok')
        $transactionId = $this->insertTransaction($amount, 'ok', null, $efectivoId);

        return [
            "success" => true,
            "change" => $change,
            "transaction_id" => $transactionId
        ];
    }

    private function insertCardTransaction($cardNumber, $responseMessage)
    {
        global $pdo; // Asegúrate de que $pdo esté accesible

        $sql = "INSERT INTO TransaccionTarjeta (numeroTarjeta, respuestaBanco) 
                VALUES (:numeroTarjeta, :respuestaBanco)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':numeroTarjeta' => $cardNumber,
            ':respuestaBanco' => (bool) $responseMessage // Guarda como true/false
        ]);

        return $pdo->lastInsertId(); // Retorna el ID de la última inserción
    }

    private function insertCashTransaction($coinTypes, $change)
    {
        global $pdo; // Asegúrate de que $pdo esté accesible

        $sql = "INSERT INTO TransaccionEfectivo (monedas, devolucion) 
                VALUES (:monedas, :devolucion)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':monedas' => json_encode($coinTypes), // Guarda las monedas como JSON
            ':devolucion' => $change // Guarda la devolución
        ]);

        return $pdo->lastInsertId(); // Retorna el ID de la última inserción
    }

    private function insertTransaction($amount, $status, $tarjetaId = null, $efectivoId = null)
    {
        global $pdo; // Asegúrate de que $pdo esté accesible

        $sql = "INSERT INTO Transaccion (cantidad, status, transaccion_tarjeta_id, transaccion_efectivo_id) 
                VALUES (:cantidad, :status, :transaccion_tarjeta_id, :transaccion_efectivo_id)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cantidad' => $amount,
            ':status' => $status,
            ':transaccion_tarjeta_id' => $tarjetaId,
            ':transaccion_efectivo_id' => $efectivoId
        ]);

        return $pdo->lastInsertId(); // Retorna el ID de la última inserción
    }
}
