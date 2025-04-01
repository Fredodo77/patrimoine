<?php

namespace App\Controller;

use App\Repository\TypePatrimoineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\TypePatrimoine;
use App\Form\TypePatrimoineType;

final class TypePatrimoineController extends AbstractController
{
    #[Route('/patrimoine/type', name: 'typepatrimoine.index', methods: ['GET', 'POST'])]
    public function index(TypePatrimoineRepository $repository): Response
    {
        $typePatrimoine = $repository->findAll();
        return $this->render('type_patrimoine/index.html.twig', [
            'typePatrimoine' => $typePatrimoine,
        ]);
    }
    #[Route('/patrimoine/type/create', name: 'typepatrimoine.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typePatrimoine = new TypePatrimoine();
        $form = $this->createForm(TypePatrimoineType::class, $typePatrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $typePatrimoine = $form->getData();
            $entityManager->persist($typePatrimoine);
            $entityManager->flush();
            return $this->redirectToRoute('typepatrimoine.index');
        }
        return $this->render('type_patrimoine/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/patrimoine/type/edit/{id}', name: 'typepatrimoine.edit', methods: ['GET', 'POST'])]
    public function edit(TypePatrimoineRepository $repository, int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $typePatrimoine = $repository->find($id);
        $form = $this->createForm(TypePatrimoineType::class, $typePatrimoine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $typePatrimoine = $form->getData();
            $entityManager->flush();
            return $this->redirectToRoute('typepatrimoine.index');
        }
        return $this->render('type_patrimoine/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/patrimoine/type/{id}/delete', name: 'typepatrimoine.delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        return $this->redirectToRoute('typepatrimoine.index');
    }
    #[Route('/patrimoine/type/delete/{id}', name: 'typepatrimoine.delete', methods: ['GET'])]
    public function deleteType(TypePatrimoineRepository $typePatrimoine, int $id, EntityManagerInterface $entityManager): Response
    {
        $typePatrimoine = $typePatrimoine->findOneBy(['id' => $id]);
        $entityManager->remove($typePatrimoine);
        $entityManager->flush();
        return $this->redirectToRoute('typepatrimoine.index');
    }
}
