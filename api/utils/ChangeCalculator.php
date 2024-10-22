<?php

class ChangeCalculator
{
    // public function coins_to_amount($coins_arr) { 
    //     // return monedas pasadas a valor
    // }

    // public function get_change($amount) {
    //     // dada una cantidad de dinero, calcular las monedas devueltas
    // }

    // public function check_payment($amount, $coins_arr) {
    //     return coins_to_amount($coins_arr) - $amount;
    // }

    // public function amount_to_coins($amount) {
    //     // transformar una cantidad en un array moneditas
    // }

    // public function pay_in_metallic($amount, $coins_arr) {
    //     $exceed = check_payment($amount, $coins_arr);
    //     if ($exceed >= 0) {
    //         return [
    //             "success" => true,
    //             "amount" => $exceed
    //             "change" => amount_to_coins($exceed)
    //         ];
    //     }

    //     return [
    //         "success" => false,
    //         "amount" => 0,
    //         "change" => [],
    //     ];
    // }


    public function calculate($amount, $coinTypes)
    {
        $totalPaid = 0;
        foreach ($coinTypes as $coinValue => $quantity) {
            $totalPaid += $coinValue * $quantity;
        }

        // Lo pasamos todo a céntimos
        $changeToReturn = $totalPaid - $amount;

        if ($changeToReturn < 0) {
            return [
                "success" => false,
                "message" => "Cantidad pagada insuficiente."
            ];
        }

        if ($changeToReturn == 0) {
            return [
                "success" => true,
                "message" => "No hay cambio a devolver",
                "change" => 0
            ];
        }

        // Todas las monedas en céntimos
        $coins = [200, 100, 50, 20, 10, 5, 2, 1];
        $changeDistribution = [];

        foreach ($coins as $coin) {
            if ($changeToReturn >= $coin) {
                $quantity = intdiv($changeToReturn, $coin);
                $changeDistribution[$coin] = $quantity;
                $changeToReturn -= $quantity * $coin;
            }
        }

        return [
            "success" => true,
            "amount" =>  $totalPaid - $amount,
            "coin_types" => $changeDistribution
        ];
    }
}
