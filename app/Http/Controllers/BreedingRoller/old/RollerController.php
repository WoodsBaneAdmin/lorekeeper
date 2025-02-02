<?php

namespace App\Http\Controllers\BreedingRoller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client; // Make sure you have Guzzle installed: `composer require guzzlehttp/guzzle`

class RollerController extends Controller
{
    public function roll(Request $request)
    {
        $outputs = [];
        $clutch = 0;
        $error = null;

        if ($request->isMethod('post')) {
            $client = new Client();
            try {
                $response = $client->post('http://192.168.1.163:5000', [ // <-- Add your API URL here
                    'json' => [
                        'geno1' => $request->input('geno1'),
                        'geno2' => $request->input('geno2'),
                        'type1' => $request->input('type1'),
                        'type2' => $request->input('type2'),
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if (isset($data['error'])) {
                    $error = $data['error'];
                } else {
                    $outputs = $data['outputs'];
                    $clutch = $data['clutch'];
                }

                dd($outputs, $clutch);

            } catch (\Exception $e) {
                $error = "Error communicating with the Python service: " . $e->getMessage();
            }
        }

        return view('/roll', ['outputs' => $outputs, 'clutch' => $clutch, 'error' => $error]); 
    }
}