<?php

require_once __DIR__ . '/../utils/LuhnValidator.php';
require_once __DIR__ . '/../utils/ChangeCalculator.php';

class PaymentController
{
    public function handlePayment($data)
    {
        // TODO comentario Nacho
        // bifurcar petición según card_num o coin_type
        // - O HACER DOS ENDPOINTS
        // Si solo un endpoint:
        //     - error si están presentes los tipos de pago (tarjeta / efectivo)?
        // lógica en modelo de Payment
        // dos funciones: pago_tarjeta y pago_monedas
        // carpeta /vendor (simula librería pagos con banco)
        // esta librería DEVOLVERÁ PAGO OK O KO (se puede hacer con un random para que falle a veces)
        // Si pago KO devolver el CODIGO DE ERROR devuelto por la LIBRERÍA QUE IMPLEMENTA EL PAGO
        // Pago con monedas, en principio como está implementado

        // imaginemos que es pago con moneda
        // $result = pay_in_metallic($data['amount'], $data['coin_types']);

        // if (!$result["success"]) {
        //     return [
        //         "success" => false,
        //     ];
        // }

        // return [
        //     "success" => true,
        //     "amount" => $result["amount"],
        //     "coin_types" => $result["change"],
        // ];

        // INTENTA SEPARAR LÓGICA DE NEGOCIO DE LA CAPA HTTP
        // MUEVE TODO A FUNCIONES EN MODELOS, POR EJEMPLO
        // LOS CONTROLADORES SUELEN SER MUY MUY SIMPLES
        // VALIDO DATOS -> LLAMO A LÓGICA DE NEGOCIO -> TRANSFORMO RESULTADOS A RESPUESTA JSON


        // Verificamos que los campos requeridos estén presentes
        if (!isset($data['card_num']) || !(isset($data['coin_types']) && isset($data['amount']))) {
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

        // Si la validación de la tarjeta es válida, calculamos el cambio
        $amount = $data['amount'];
        $coinTypes = $data['coin_types'];
        $changeCalculator = new ChangeCalculator();
        $change = $changeCalculator->calculate($amount, $coinTypes);

        return $change;
    }
}
