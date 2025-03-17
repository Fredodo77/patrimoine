<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Patrimoine;
use App\Form\PatrimoineType;
use App\Repository\PatrimoineRepository;

final class PatrimoineController extends AbstractController
{
    #[Route('/patrimoine', name: 'patrimoine.index')]
    public function index(PatrimoineRepository $repository): Response
    {
        $patrimoine = $repository->findAll();
        return $this->render('patrimoine/index.html.twig', [
            'patrimoine' => $patrimoine,
        ]);
    }
    #[Route('/patrimoine/create', name: 'patrimoine.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $patrimoine = new Patrimoine();
        $form = $this->createForm(PatrimoineType::class, $patrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $patrimoine = $form->getData();
            $entityManager->persist($patrimoine);
            $entityManager->flush();
            return $this->redirectToRoute('patrimoine.index');
        }
        return $this->render('patrimoine/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/patrimoine/edit/{id}', name: 'patrimoine.edit', methods: ['GET', 'POST'])]
    public function edit(PatrimoineRepository $repository, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $patrimoine = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(PatrimoineType::class, $patrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $patrimoine = $form->getData();
            $entityManager->flush();
            return $this->redirectToRoute('patrimoine.index');
        }
        return $this->render('patrimoine/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/patrimoine/delete/{id}', name: 'patrimoine.delete', methods: ['GET', 'POST'])]
    public function delete(PatrimoineRepository $patrimoine, int $id, EntityManagerInterface $entityManager): Response
    {
        $patrimoine = $patrimoine->findOneBy(['id' => $id]);
        $entityManager->remove($patrimoine);
        $entityManager->flush();
        return $this->redirectToRoute('patrimoine.index');
    }
}
