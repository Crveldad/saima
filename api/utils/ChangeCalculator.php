<?php

const COINS = [200, 100, 50, 20, 10, 5, 2, 1];

class ChangeCalculator
{

    function coinTypesToAmount($coinTypes)
    {
        $amount = 0;
        foreach ($coinTypes as $coinValue => $quantity) {
            $amount += $coinValue * $quantity;
        }
        return $amount;
    }

    function amountToCoinTypes($amount)
    {
        $coinTypes = [];

        foreach (COINS as $coinValue) {
            if ($amount >= $coinValue) {
                $coinQty = intdiv($amount, $coinValue);
                $coinTypes[$coinValue] = $coinQty;
                $amount -= $coinQty * $coinValue;
            }
        }

        return $coinTypes;
    }

    public function calculate($targetAmount, $coinTypes)
    {
        $totalPaid = $this->coinTypesToAmount($coinTypes);

        // Lo pasamos todo a céntimos
        $change = $totalPaid - $targetAmount;

        if ($change < 0) {
            return [
                "success" => false,
                "message" => "Cantidad pagada insuficiente."
            ];
        }

        if ($change == 0) {
            return [
                "success" => true,
                "message" => "No hay cambio a devolver.",
                "change" => 0
            ];
        }

        $changeInCoinTypes = $this->amountToCoinTypes($change);

        return [
            "success" => true,
            "amount" => $change,
            "coin_types" => $changeInCoinTypes
        ];
    }
}
