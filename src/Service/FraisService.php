<?php

namespace App\Service;

use App\Repository\FraisRepository;

class FraisService
{
    private $taxe;
    private $fraisRepository;
    public function __construct(FraisRepository $fraisRepository)
    {
       $this->fraisRepository=$fraisRepository;
    }

    public function Prix(int $montant){
        $taxes = $this->fraisRepository->findAll();
        foreach ($taxes as $tax) {
            switch (true){
                case($montant >= $tax->getMin() && $montant < $tax->getMax()):
                    $this->taxe = $tax->getPrix();
                    if($this->taxe == 0.02){
                        $this->taxe *=$montant;
                    }
            }
        }
        return $this->taxe;
    }
}
