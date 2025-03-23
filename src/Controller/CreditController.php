<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Amortissement;
use App\Form\AmortissementType;
use App\Repository\AmortissementRepository;
use App\Form\CreditType;
use App\Repository\CreditRepository;
use App\Repository\PatrimoineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\AmortissementService;

final class CreditController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }   

    #[Route('/credit', name: 'credit.index')]
    public function index(CreditRepository $repository): Response
    {
        $credit = $repository->findAll();
        return $this->render('credit/index.html.twig', [
            'credit' => $credit,
        ]);
    }
    #[Route('/credit/show/{id}', name: 'credit.show', methods: ['GET'])]
    public function show(CreditRepository $repository, int $id): Response
    {
        $credit = $repository->findOneBy(['id' => $id]);
        return $this->render('credit/show.html.twig', [
            'credit' => $credit
        ]);
    }
    #[Route('/credit/create', name: 'credit.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, AmortissementService $amortissementService): Response
    {
        $credit = new Credit();
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
           // try {
            $credit = $form->getData();
            $entityManager->persist($credit);
            // Gérer l'amortissement ici
            $entityManager->flush();
            
            $amortissementService->createAmortissement($credit->getId());

            

           // } catch (Exception $exception) {
             //   $entityManager->rollback();
               // $this->logger->error("L'ajout de l'amortissement a échoué");
            //}
            return $this->redirectToRoute('amortissement.index', ['id' => $credit->getId()]);
        }
        return $this->render('credit/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/credit/edit/{id}', name: 'credit.edit', methods: ['GET', 'POST'])]
    public function edit(PatrimoineRepository $patrimoine, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $credit = $patrimoine->findOneBy(['id' => $id]);
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $credit = $form->getData();
            $entityManager->flush();
            return $this->redirectToRoute('credit.index');
        }
        return $this->render('credit/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/credit/delete/{id}', name: 'credit.delete')]
    public function delete(int $id, CreditRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $credit = $repository->findOneBy(['id' => $id]);
        $entityManager->remove($credit);
        $entityManager->flush();
        return $this->redirectToRoute('credit.index');
    }
}
