<?php

require_once __DIR__ . '/../utils/LuhnValidator.php';
require_once __DIR__ . '/../utils/ChangeCalculator.php';

class PaymentController
{
    public function handlePayment($data)
    {
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
