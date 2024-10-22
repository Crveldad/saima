<?php

class ChangeCalculator
{
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
