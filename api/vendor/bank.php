<?php

function simulateBankResponse($amount)
{
    // Genera un número aleatorio entre 1 y 10
    $randomNumber = rand(1, 10);

    // Simulamos un error en el 30% de los casos
    if ($randomNumber <= 3) { // 30% de probabilidad de fallo
        return [
            "success" => false,
            "error" => 702 // Código de error simulado
        ];
    }

    // Si no falla, simula un éxito
    return [
        "success" => true
    ];
}