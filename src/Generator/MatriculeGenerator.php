<?php

namespace App\Generator;

use App\Entity\Medecin;
use App\Repository\MedecinRepository;

class MatriculeGenerator{
 
    private $matricule;

    public function __construct(MedecinRepository $repo){
        $autoMat= $repo->findAll();
        $count = count($autoMat);
        $this->matricule = sprintf("%05d", $count+1);
        
    }

    public function generateMat(Medecin $medecin){

        $index= "M";

        $service= $medecin->getService()->getLibelle();

        $numbofWorld= (str_word_count($service, 1));

        if (count($numbofWorld) ==2 ) {
            foreach ($numbofWorld as $key => $value) {
                $index.= strtoupper(substr($value, 0,1));
            }
        }else{

            $index.= strtoupper(substr($numbofWorld[0], 0,2));
        }

        return $index.$this->matricule;
    } 
}