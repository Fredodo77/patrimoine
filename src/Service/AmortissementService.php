<?php

namespace App\Service;

use App\Entity\Credit;
use App\Entity\Amortissement;
use App\Repository\AmortissementRepository;
use App\Repository\CreditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AmortissementService {

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private CreditRepository $creditRepository,
        private AmortissementRepository $amortissementRepository
    ) {
    }

    public function createAmortissement(int $id): void
    {
        /*
 * @todo
 * remplacer le find par un if
 * faire un try catch au niveau de ton for qui retourne une exception s'il y a une erreur
 * voir le système de rollback de doctrine
 */
        $credit = $this->creditRepository->find($id);
        
        // Calcul des amortissements
        $montant = $credit->getMontant();
        $taux = $credit->getTaux();
        $duree = $credit->getDuree();
        $date = $credit->getPremiereEcheance();
        $mensualite = $montant * ($taux / 12) / (1 - (1 + $taux / 12) ** (-$duree));
        $capitalRestant = $montant;
        
        for ($i = 1; $i <= $duree; $i++) {
            
            $interet = $capitalRestant * $taux / 12;
            $capital = $mensualite - $interet;
            $capitalRestant -= $capital;
            $newdate = $date->modify('+'.$i.' month');
            
                $amortissement = new Amortissement();
            $amortissement->setNumCredit($credit);
            $amortissement->setNumEcheance($i);
            $amortissement->setDateEcheance($newdate);
            $amortissement->setMontantAmortissement($mensualite);
            $amortissement->setMontantInteret($interet);
            
            $this->entityManager->persist($amortissement);
            $this->entityManager->flush();
            // throw new Exception($amortissement->getId());
        }  
        
    }
}