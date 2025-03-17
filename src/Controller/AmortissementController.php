<?php

namespace App\Controller;

use App\Entity\Amortissement;
use App\Form\AmortissementType;
use App\Repository\AmortissementRepository;
use App\Repository\CreditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AmortissementController extends AbstractController
{
    #[Route('/amortissement/{id}', name: 'amortissement.index', methods: ['GET', 'POST'])]
    public function show(CreditRepository $credit, int $id): Response
    {  
        $credit = $credit->findOneBy(['id' => $id]);
        $montant = $credit->getMontant();
        $taux = $credit->getTaux();
        $duree = $credit->getDuree();
        $date = $credit->getPremiereEcheance();
        $mensualite = $montant * ($taux / 12) / (1 - (1 + $taux / 12) ** (-$duree));
        $amortissements = [];
        $capitalRestant = $montant;
        for ($i = 1; $i <= $duree; $i++) {
            $interet = $capitalRestant * $taux / 12;
            $capital = $mensualite - $interet;
            $capitalRestant -= $capital;
            $newdate = $date->modify('+'.$i.' month');
            $amortissements[] = [
                'mois' => $i,
                'date' => $newdate,
                'mensualite' => $mensualite,
                'interet' => $interet,
                'capital' => $capital,
                'capitalRestant' => $capitalRestant
            ];
        }
        return $this->render('amortissement/index.html.twig', [
            'credit' => $credit,
            'amortissements' => $amortissements
        ]);
    }
    #[Route('/amortissement/create/{id}', name: 'amortissement.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, CreditRepository $credit, int $id): Response
    {
        $credit = $credit->findOneBy(['id' => $id]);
        $amortissement = new Amortissement();
        // Calcul des amortissements
        $montant = $credit->getMontant();
        $taux = $credit->getTaux();
        $duree = $credit->getDuree();
        $date = $credit->getPremiereEcheance();
        $mensualite = $montant * ($taux / 12) / (1 - (1 + $taux / 12) ** (-$duree));
        $amortissements = [];
        $capitalRestant = $montant;
        for ($i = 1; $i <= $duree; $i++) {
            $interet = $capitalRestant * $taux / 12;
            $capital = $mensualite - $interet;
            $capitalRestant -= $capital;
            $newdate = $date->modify('+'.$i.' month');
            $amortissements[] = [
                'mois' => $i,
                'date' => $newdate,
                'mensualite' => $mensualite,
                'interet' => $interet,
                'capital' => $capital,
                'capitalRestant' => $capitalRestant
            ];
        }
        // fin du calcul
        $form = $this->createForm(AmortissementType::class, $amortissement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $amortissement = $form->getData();
            $entityManager->persist($amortissement);
            $entityManager->flush();
            return $this->redirectToRoute('credit.index');
        }
        return $this->render('amortissement/create.html.twig', [
            'form' => $form->createView(),
            'credit' => $credit,
            'amortissements' => $amortissements
        ]);
    }
    #[Route('/amortissement/edit/{id}', name: 'amortissement.edit', methods: ['GET', 'POST'])]
    public function edit(): Response
    {
        return $this->render('amortissement/edit.html.twig');
    }
    #[Route('/amortissement/delete/{id}', name: 'amortissement.delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        return $this->redirectToRoute('amortissement.index');
    }
}
