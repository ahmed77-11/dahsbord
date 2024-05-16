<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\ImageProduit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;


#[Route('/back/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_back_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('/back/produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $produit = new Produit();
        $user=$this->getUser();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form->get('prodImg')->getData();
            $ref=$form->get('reference')->getData();
            $des=$form->get('designation')->getData();
            $cat=$form->get('Categorie')->getData();
            $mar=$form->get('Marque')->getData();
            $produit->setCategorie($cat);
            $produit->setDesignation($des);
            $produit->setMarque($mar);
            $produit->setReference($ref);
            $produit->setDesignation($des);
            $produit->setUser($user);
            $uploadDirectory = $this->getParameter('images_directory');
            $imgName = md5(uniqid()).'.'.$imageFiles->guessExtension();
            $imageFiles->move($uploadDirectory, $imgName);
            $produit->setProdImg($imgName);
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute('app_back_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'app_back_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('back/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
