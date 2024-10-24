<?php

class LuhnValidator
{
    public static function validate($number)
    {
        // para sólo trabajar con números, eliminamos el resto de caracteres y le damos la vuelta con strrev
        $number = strrev(preg_replace('/\D/', '', $number));
        $sum = 0;

        for ($i = 0; $i < strlen($number); $i++) {
            $digit = $number[$i];
            // si es número par, lo multiplicamos por 2
            if ($i % 2 == 1) {
                $digit *= 2;
                // y si es mayor que 9, lo "simplificamos" restando 9
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        // si es exactamente múltiplo de 10, es que es válido
        return ($sum % 10) === 0;
    }
}
