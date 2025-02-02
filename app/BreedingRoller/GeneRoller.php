<?php

namespace App\BreedingRoller;



class GeneRoller
{
    private $coats = [
        "Black" => ["Bb Rr Yy" ,"BB Rr Yy", "Bb RR Yy" , "BB RR Yy" , "Bb Rr YY", "BB Rr YY" , "Bb RR YY" , "BB RR YY"],
        "White" => ["bb rr yy"], "Red"=> ["bb Rr yy","bb RR yy"], "Yellow"=> ["bb rr Yy", "bb rr YY"], "Blue"=> ["Bb rr yy", "BB rr yy"],
        "Orange"=> ["bb Rr Yy", "bb Rr YY", "bb RR Yy", "bb RR YY"], "Green"=> ["Bb rr Yy" , "Bb rr YY" ,"BB rr Yy" ,"BB rr YY"],
        "Purple"=> ["Bb Rr yy" , "Bb RR yy" , "BB Rr yy" , "BB RR yy"]
    ];

    private $dilutions = [
        "Scorched"=> ["nSc" , "ScSc"], "Sunglow"=>["nSg" , "SgSg"], "Dimmed"=> ["nDi" , "DiDi"]
    ];

    private $markings = [
        "Faded"=>["nFa","FaFa"], "Speckled"=>["nSp","SpSp"], "Barred"=> ["nBr" , "BrBr"],"Piebald"=> ["nPi" , "PiPi"],
        "Mottled"=> ["nMo" , "MoMo"], "Striated"=>["nSt","StSt"],"Pointed"=> ["nP" , "PP"], "Cloaked"=> ["nCl" , "ClCl"],
        "Shimmer"=>"shsh", "Shimmer (carried)"=> "nsh", "Gradient"=> ["nGr" , "GrGr"], "Hooded"=>["nHo" , "HoHo"],
        "Marbled"=>["nMar" , "MarMar"],"Patternless"=> "plpl", "Patternless (carried)"=> "npl", "Albino"=> "aa", "Albino (carried)"=> "na",
        "Melanism"=> "mm", "Melanism (carried)"=> "nm", "Painted"=> ["nPa" , "PaPa"], 
        "Python"=> ["nPy" , "PyPy"], "Lightning"=> ["nLi" , "LiLi"],"Lightning Python"=> "LiPy",
        "Eyespots"=>["nESp","ESpESp"],"Rosette"=>["nRo,RoRo"],"Element Touched"=>["nEle","EleEle"]
    ];

    public function roll(string $genome1, string $genome2)
    {

        $genes1 = explode(' ', $genome1);
        $genes2 = explode(' ', $genome2);

        $alleles1 = self::alleleRoller($genes1);
        $alleles2 = self::alleleRoller($genes2);
        $genes = self::combineAlleles($alleles1, $alleles2);
        $phenotype = self::phenotype($genes); 

        return [$phenotype, implode(' ', $genes)]; 
    }

    private function basecoat(array $genes)
    {
        $base = implode(' ', array_slice($genes, 0, 3)); 

        foreach ($this->coats as $key => $values) {
            if (in_array($base, $values)) {
                return $key;
            }
        }

        return "Invalid gene";
    }

    private function dilution($gene)
    {
        foreach ($this->dilutions as $key => $values) {
            if (in_array($gene, $values)) {
                return $key;
            }
        }

        return "Invalid gene";
    }

    private function marking($gene)
    {
        foreach ($this->markings as $key => $values) {
            if (in_array($gene, (array)$values)) { // Handle single-value arrays
                return $key;
            }
        }

        return "Invalid gene";
    }

    private function phenotype(array $genes)
    {
        $base = $this->basecoat($genes);

        $dil = [];
        $mark = [];

        for ($i = 3; $i < count($genes); $i++) { 
            $d = $this->dilution($genes[$i]);
            if ($d !== "Invalid gene") {
                $dil[] = $d;
            }

            $m = $this->marking($genes[$i]);
            if ($m !== "Invalid gene") {
                $mark[] = $m;
            }
        }

        if ($base === "Invalid gene" || in_array("Invalid gene", $dil) || in_array("Invalid gene", $mark)) {
            return "Invalid gene";
        }

        return implode(' ', $dil) . ' ' . $base . ' ' . implode(' ', $mark);
    }

    private function alleleRoller(array $genes)
    {
        $alleles = [];

        foreach ($genes as $gene) {
            if ($gene[0] != "n") {
                $allele = $this->randomAllele($gene);
            } else {
                $allele = $this->randomAllele($gene); 
            }

            if ($allele !== "n") {
                $alleles[] = $allele;
            }
        }

        return $alleles;
    }

    private function randomAllele($gene)
    {
        if ($gene[0] != "n") {
            $alleles = [$gene[0], substr($gene, 1)];
        } else {
            $alleles = [$gene[0], $gene[1]];
        }

        return $alleles[array_rand($alleles)];
    }

    private function combineAlleles(array $alleles1, array $alleles2)
    {
        $basecoatAlleles = ['B', 'R', 'Y'];
        $basecoatLowerAlleles = ['b', 'r', 'y'];
        $genes = [];

        foreach ($alleles1 as $allele1) {
            if (in_array($allele1, $basecoatAlleles)) {
                if (in_array($allele1, $alleles2)) {
                    $genes[] = $allele1 . $allele1; 
                } else {
                    $genes[] = $allele1 . strtolower($allele1);
                }
            } elseif (in_array($allele1, $basecoatLowerAlleles)) {
                if (in_array($allele1, $alleles2)) {
                    $genes[] = $allele1 . $allele1;
                } else {
                    $genes[] = strtoupper($allele1) . $allele1;
                }
            } elseif ($allele1 === 'Py') {
                if (in_array('Py', $alleles2)) {
                    $genes[] = 'PyPy';
                } elseif (in_array('Li', $alleles2)) {
                    $genes[] = 'LiPy';
                } else {
                    $genes[] = 'nPy';
                }
            } elseif ($allele1 === 'Li') {
                if (in_array('Py', $alleles2)) {
                    $genes[] = 'LiPy';
                } elseif (in_array('Li', $alleles2)) {
                    $genes[] = 'LiLi';
                } else {
                    $genes[] = 'nLi';
                }
            } elseif (in_array($allele1, $alleles2) && $allele1 !== 'Py' && $allele1 !== 'Li') {
                $genes[] = $allele1 . $allele1;
            } else {
                $genes[] = 'n' . $allele1;
            }
        }

        foreach ($alleles2 as $allele2) {
            if (in_array($allele2, $alleles1) || 
                in_array($allele2, $basecoatAlleles) || 
                in_array($allele2, $basecoatLowerAlleles) || 
                in_array('n' . $allele2, $genes) || 
                in_array('LiPy', $genes)) {
                continue; 
            } else {
                $genes[] = 'n' . $allele2;
            }
        }

        return $genes;
    }
}