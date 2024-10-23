<?php

header('Content-Type: application/json');

// Autoload de archivos necesarios
require_once 'controllers/PaymentController.php';

// Leemos la solicitud
$request = file_get_contents('php://input');
$data = json_decode($request, true);

// Validamos que se haya recibido correctamente el JSON
if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Solicitud inválida, no se ha recibido nada."
    ]);
    die();
}

// Inicializamos el controlador de pagos
$paymentController = new PaymentController();

// Comprobamos el tipo de pago
if (isset($data['card_num'])) {
    // Pago con tarjeta
    $response = $paymentController->handleCardPayment($data);
} elseif (isset($data['coin_types'])) {
    // Pago en efectivo
    $response = $paymentController->handleCashPayment($data);
} else {
    // Tipo de pago no reconocido
    echo json_encode([
        "success" => false,
        "message" => "Tipo de pago no reconocido, faltan parámetros."
    ]);
    die();
}

// Enviamos la respuesta de la API
echo json_encode($response);
