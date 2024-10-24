<?php

header('Content-Type: application/json');

require_once 'controllers/PaymentController.php';

// leemos la solicitud
$request = file_get_contents('php://input');
$data = json_decode($request, true);

// validamos que se haya recibido correctamente el JSON
if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Solicitud inválida, no se ha recibido nada."
    ]);
    die();
}

$paymentController = new PaymentController();

// comprobamos el tipo de pago
if (isset($data['card_num'])) {
    // pago con tarjeta
    $response = $paymentController->handleCardPayment($data);
} elseif (isset($data['coin_types'])) {
    // Pago en efectivo
    $response = $paymentController->handleCashPayment($data);
} else {
    // tipo de pago no reconocido
    echo json_encode([
        "success" => false,
        "message" => "Tipo de pago no reconocido, faltan parámetros."
    ]);
    die();
}

// enviamos la respuesta de la API
echo json_encode($response);
