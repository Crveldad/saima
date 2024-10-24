<?php

function simulateBankResponse($amount)
{
    $randomNumber = rand(1, 10);

    // simulamos un error en el 30% de los casos
    if ($randomNumber <= 3) { 
        return [
            "success" => false,
            "message" => 0,
            "error" => 702 // código de error simulado
        ];
    }

    // si no falla, simula un éxito
    return [
        "success" => true,
        "message" => 1
    ];
}