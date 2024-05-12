<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie',methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('back/categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_categorie_new')]
    public function new(Request $request,EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form=$this->createForm(CategorieType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorieNom = $form->get('nomCateq')->getData();
            $img = $form->get('catImg')->getData();
            $uploadDirectory = $this->getParameter('images_directory');
            $imgName = md5(uniqid()).'.'.$img->guessExtension();
            $img->move($uploadDirectory, $imgName);

            $categorie->setNomCateq($categorieNom);
            $categorie->setCatImg($imgName);
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie');
        }

        return $this->render('back/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('back/categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);

    }
    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,Categorie $categorie,EntityManagerInterface $entityManager){
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorieNom = $form->get('nomCateq')->getData();
            $img = $form->get('catImg')->getData();
            $uploadDirectory = $this->getParameter('images_directory');
            $imgName = md5(uniqid()).'.'.$img->guessExtension();
            $img->move($uploadDirectory, $imgName);

            $categorie->setNomCateq($categorieNom);
            $categorie->setCatImg($imgName);
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie');
        }
        return $this->render('back/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);

    }
    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie');


    }

}
