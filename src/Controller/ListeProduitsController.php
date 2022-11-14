<?php

namespace App\Controller;

// use App\Entity\Produit;
use App\Entity\Produit;
use App\Repository\DistributeurRepository;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListeProduitsController extends AbstractController
{
    /**
     * @Route("/", name="app_liste_produits")
     */
    public function liste(ProduitRepository $produitRepository): Response
    {
        // Méthode du livre mais depreciated, sans injection de dépendance
        // $em=$this->getDoctrine()->getManager();
        // $produitRepository=$em->getRepository(Produit::class);
        
        // Méthode cours Christian avec injection de dépendance
        $listeProduits = $produitRepository->findAll();
        //dd($listeProduits);
        
        $lastProduit = $produitRepository->getLastProduit();
        //dd($lastProduit);

        return $this->render('liste_produits/index.html.twig', [
            'liste_produits' => $listeProduits,
            'last_produit' => $lastProduit,
            'actif' => 'liste',
        ]);
    }

    /**
     * @Route("/distributeurs", name="app_distributeurs")
     */
    public function distributeurs(DistributeurRepository $distribRepo): Response
    {
        $distributeurs = $distribRepo->findAll();

        return $this->render('liste_produits/distributeurs.html.twig', [
            'distributeurs' => $distributeurs,
            'actif' => 'distributeurs',
        ]);
    }

    /**
     * @Route("/one/{id}", name="app_one_produits")
     */
    public function one(ProduitRepository $produitRepository, int $id): Response
    {
        $imprimante = $produitRepository->find($id);
        dd($imprimante);
        
        return $this->render('liste_produits/index.html.twig', [
            // 'liste_produits' => $listeProduits,
        ]);
    }

    /**
     * @Route("/treize", name="app_treize_produits")
     */
    public function treize(ManagerRegistry $doctrine): Response
    {
        // Autre méthode dispo dans la doc symfony "Databases and the Doctrine ORM"
        $produit = $doctrine->getRepository(Produit::class)->find(13);
        dd($produit);
        
        return $this->render('liste_produits/index.html.twig', [
            // 'liste_produits' => $listeProduits,
        ]);
    }

}
