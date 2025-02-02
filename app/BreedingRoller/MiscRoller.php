<?php

namespace App\BreedingRoller;

use Illuminate\Support\Facades\Log;

class MiscRoller
{
    public static function clutchSize()
    {
        try {
            $result = random_int(1, 100);

            if ($result <= 5) {
                return 3;
            } elseif ($result > 5 && $result <= 26) {
                return 2;
            } else {
                return 1;
            }
        } catch (\Exception $e) {
            Log::error('Error calculating clutch size: ' . $e->getMessage());
            return 1; // Default to 1 baby on error
        }
    }
}