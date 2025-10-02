<?php

namespace App\Services;


class MathService {
    public function multiplication($firstDigit, $secondDigit): float
    {
        return $firstDigit * $secondDigit;
    }
}