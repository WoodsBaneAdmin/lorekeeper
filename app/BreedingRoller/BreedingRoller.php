<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RollingController extends Controller
{
    public function roll(Request $request)
    {
        try {
            // Extract data from the request
            $genome1 = $request->json('geno1');
            $genome2 = $request->json('geno2');
            $type1 = $request->json('type1');
            $type2 = $request->json('type2');

            // Implement logic for checking genomes, clutch size, gene rolling, etc. (replicating Flask logic)
            $clutch = MiscRoller::clutchSize();
            $output = [];
            for ($i = 0; $i < $clutch; $i++) {
                $color = GeneRoller::geneRoller($genome1, $genome2);
                $childType = TraitsRoller::calculateType($type1, $type2);
                $childTraits = TraitsRoller::calculateVariantTraits($type1, $type2, $childType);
                $result = [
                    $i + 1,
                    $color[0],
                    $color[1],
                    $childType->title(),
                    $childTraits,
                ];
                $output[] = $result;
            }

            return response()->json([
                'outputs' => $output,
                'clutch' => $clutch,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}