<?php

namespace App\Controller;

use App\Entity\Amortissement;
use App\Entity\Credit;
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
    public function show(CreditRepository $credit, int $id, AmortissementRepository $amortissement): Response
    {  
        $credit = $credit->findOneBy(['id' => $id]);
        $amortissements = $amortissement->findBy(['num_credit' => $id]);
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
    public function edit(AmortissementRepository $amortissements, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $amortissement = $amortissements->findOneBy(['id' => $id]);
        $form = $this->createForm(AmortissementType::class, $amortissement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $amortissement = $form->getData();
            $entityManager->persist($amortissement);
            $entityManager->flush();
            $credit = $amortissement->getNumCredit();
            return $this->redirectToRoute('amortissement.index', ['id' => $credit]);
        }
        return $this->render('amortissement/edit.html.twig', [
            'form' => $form->createView(),
            'amortissement' => $amortissement
        ]);
    }
    #[Route('/amortissement/delete/{id}', name: 'amortissement.delete', methods: ['GET'])]
    public function delete(int $id, AmortissementRepository $amortissement, EntityManagerInterface $entityManager): Response
    {
        $amortissement = $amortissement->findOneBy(['id' => $id]);
        if ($amortissement) {
            $entityManager->remove($amortissement);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Amortissement supprimé avec succès.');
        return $this->redirectToRoute('amortissement.index', ['id' => $amortissement->getNumCredit()]);
    }
}
