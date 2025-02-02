<?php

namespace App\BreedingRoller;


use Illuminate\Support\Facades\Log;

class TraitsRoller
{
    private static $Drakovai_rarity = [
        "wyvern" => "common",
        "drake" => "common",
        "true dragon" => "uncommon",
    ];

    private static $rarity_score = [
        "common" => 1,
        "uncommon" => 2,
    ];

    private static $Drakovai_traits = [
        "wyvern" => [
            "body variant" => ["scaled", "feathered"],
            "head" => ["crest", "none"],
            "mouth" => ["basic", "primitive", "beak"],
        ],
        "drake" => [
            "body variant" => ["scaled", "armored"],
            "head" => ["frill", "none"],
            "mouth" => ["basic", "primitive", "tusk"],
        ],
        "true dragon" => [
            "body variant" => ["scaled", "armored"],
            "placeholder" => ["temp"],
            "mouth" => ["basic", "primitive", "fangs"],
        ],
    ];

    public static function highestRarity()
    {
        $high = array_search(max(self::Drakovai_rarity), self::Drakovai_rarity);
        return $high;
    }

    public static function calculateType(string $type1, string $type2): string
    {
        $rarity1 = self::$Drakovai_rarity[$type1];
        $rarity2 = self::$Drakovai_rarity[$type2];
    
        $result = random_int(1, 100);
    
        if (self::$rarity_score[$rarity1] > self::$rarity_score[$rarity2]) {
            $high = [$rarity1, $type1];
            $low = [$rarity2, $type2];
        } else {
            $high = [$rarity2, $type2];
            $low = [$rarity1, $type1];
        }
    
        if ($result == 1) { // Case 1: 1% chance to be a random dragon of higher rarity (but there is no higher rarity)
            if ($high[0] === self::highestRarity()) { 
                $types = array_keys(array_filter(self::$Drakovai_rarity, fn($value) => $value === $high[0]));
                return $types[array_rand($types)];
            } else {
                $rarities = array_keys(array_filter(self::$rarity_score, fn($value) => $value === self::$rarity_score[$high[0]] + 1));
                $types = array_keys(array_filter(self::$Drakovai_rarity, fn($value) => in_array($value, $rarities)));
                return $types[array_rand($types)];
            }
        } elseif ($result <= 6) { // Case 2: 5% chance to be a random dragon of either parent's rarity
            $types = array_keys(array_filter(self::$Drakovai_rarity, fn($value) => $value === $high[0] || $value === $low[0]));
            return $types[array_rand($types)];
        } else {// Default: inherit one of the parent's types
            $score = self::$rarity_score[$high[0]] - self::$rarity_score[$low[0]];
    
            switch ($score) {
                case 0:
                    $possibleTypes = [$low[1], $high[1]];
                    break;
                case 1:
                    $possibleTypes = [$low[1], $low[1], $high[1]];
                    break;
                case 2:
                    $possibleTypes = [$low[1], $low[1], $low[1], $high[1]];
                    break;
                default:
                    $possibleTypes = [$low[1], $low[1], $low[1], $low[1], $low[1], $high[1]];
                    break;
            }
    
            return $possibleTypes[array_rand($possibleTypes)];
        }
    }

    public static function calculateVariantTraits(string $parent1, string $parent2, string $child): array
    {
        $child_traits = [];

        if ($child !== $parent1 && $child !== $parent2) {
            foreach (self::$Drakovai_traits[$child] as $trait => $options) {
                $child_traits[$trait] = $options[array_rand($options)];
            }
        } elseif ($child === $parent1 && $child === $parent2) {
            foreach (self::$Drakovai_traits[$child] as $trait => $options) {
                $child_traits[$trait] = $options[array_rand($options)];
            }
        } else {
            $result = random_int(1, 100);

            if ($child === $parent1) {
                $different = $parent2;
            } else {
                $different = $parent1;
            }

            switch ($result) {
                case 1:
                case 2:
                    $traits = array_keys(self::$Drakovai_traits[$different]);
                    if (count($traits) === 3) {
                        foreach ($traits as $trait) {
                            $child_traits[$trait] = self::$Drakovai_traits[$different][$trait][array_rand(self::$Drakovai_traits[$different][$trait])];
                        }
                        foreach (self::$Drakovai_traits[$child] as $trait => $options) {
                            if (!isset($child_traits[$trait])) {
                                $child_traits[$trait] = $options[array_rand($options)];
                            }
                        }
                    } else {
                        for ($i = 0; $i < 3; $i++) {
                            $trait = $traits[array_rand($traits)];
                            while (isset($child_traits[$trait])) {
                                $trait = $traits[array_rand($traits)];
                            }
                            $child_traits[$trait] = self::$Drakovai_traits[$different][$trait][array_rand(self::$Drakovai_traits[$different][$trait])];
                        }
                        foreach (self::$Drakovai_traits[$child] as $trait => $options) {
                            if (!isset($child_traits[$trait])) {
                                $child_traits[$trait] = $options[array_rand($options)];
                            }
                        }
                    }
                    break;
                case 3:
                case 4:
                case 5:
                    $traits = array_keys(self::$Drakovai_traits[$different]);
                    for ($i = 0; $i < 2; $i++) {
                        $trait = $traits[array_rand($traits)];
                        while (isset($child_traits[$trait])) {
                            $trait = $traits[array_rand($traits)];
                        }
                        $child_traits[$trait] = self::$Drakovai_traits[$different][$trait][array_rand(self::$Drakovai_traits[$different][$trait])];
                    }
                    foreach (self::$Drakovai_traits[$child] as $trait => $options) {
                        if (!isset($child_traits[$trait])) {
                            $child_traits[$trait] = $options[array_rand($options)];
                        }
                    }
                    break;
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                    $traits = array_keys(self::$Drakovai_traits[$different]);
                    $trait = $traits[array_rand($traits)];
                    $child_traits[$trait] = self::$Drakovai_traits[$different][$trait][array_rand(self::$Drakovai_traits[$different][$trait])];
                    foreach (self::$Drakovai_traits[$child] as $trait => $options) {
                        if (!isset($child_traits[$trait])) {
                            $child_traits[$trait] = $options[array_rand($options)];
                        }
                    }
                    break;
                default:
                    foreach (self::$Drakovai_traits[$child] as $trait => $options) {
                        $child_traits[$trait] = $options[array_rand($options)];
                    }
            }
        }

        return $child_traits;
    }
}