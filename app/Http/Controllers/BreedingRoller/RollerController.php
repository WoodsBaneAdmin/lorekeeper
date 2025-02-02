<?php

namespace App\Http\Controllers\BreedingRoller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\BreedingRoller\GeneRoller;
use App\BreedingRoller\MiscRoller;
use App\BreedingRoller\TraitsRoller;

class RollerController extends Controller
{
    public function index(Request $request)
    {
        return view('roll',['old' => $request->old()]); // Replace 'breeding' with your actual blade file name
    }

    public function roll(Request $request)
    {
        $request->validate([
            'geno1' => 'required|string',
            'geno2' => 'required|string',
            'type1' => 'required|in:wyvern,drake,true_dragon',
            'type2' => 'required|in:wyvern,drake,true_dragon',
            'element1' => 'nullable|in:fire,water,air,earth', 
            'elementTwo1' => 'nullable|in:none,fire,water,air,earth',
            'element2' => 'nullable|in:fire,water,air,earth',
            'elementTwo2' => 'nullable|in:none,fire,water,air,earth',
        ]);

        $outputs = [];
        $clutch = 0;
        $error = null;

        try {
            $clutch = MiscRoller::clutchSize();

            for ($i = 0; $i < $clutch; $i++) {
                $geneRoller = new GeneRoller(); 
                $color=$geneRoller->roll($request->input('geno1'), $request->input('geno2'));

                $traitsRoller = new TraitsRoller();
                $childType = $traitsRoller->calculateType($request->input('type1'), $request->input('type2'));
                #dd($childType);
                $childTraits = $traitsRoller->calculateVariantTraits($request->input('type1'), $request->input('type2'), $childType);
                
                
                $outputs[] = [
                    'number' => $i + 1, 
                    'phenotype' => $color[0],
                    'genotype' => $color[1],
                    'type' => $childType,
                    'traits' => $childTraits
                ];
            }

        

        } 
        catch (\Exception $e) {
            $error = 'An error occurred during the roll: ' . $e->getMessage(); 
            return view('roll', [
                'error' => $error, 
                'old' => $request->old()
            ]); 
        }
        
        return view('roll', [
            'outputs' => $outputs, 
            'clutch' => $clutch, 
            'error' => $error, 
            'old' => $request->old()
        ]);
    }
}