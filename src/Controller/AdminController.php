<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/** 
 * @Route("/admin") 
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/insert", name="insert", methods={"GET", "POST"})
     */
    public function insert(Request $request): Response
    {
        $produit = new Produit;

        // Appel du formulaire externe
        $formProduit = $this->createForm(ProduitType::class, $produit); 
        
        //Ajout du champ de validation du formulaire personnalisé pour l'ajout
        $formProduit->add('Ajouter', SubmitType::class);

        //Récupération des données éventuellement transmises dans le formulaire
        $formProduit->handleRequest($request);
        
        //Test de la présence de données en post et leur validité puis traitement
        if($request->isMethod('post') && $formProduit->isValid()) {
            
            $em = $this->getDoctrine()->getManager();

            //Récupération du fichier image
            $file = $formProduit['lienImage']->getData();
            //Si le fichier est de type string (nothing par défault) le client n'a pas choisit d'image
            if(!is_string($file)) {
                //Sauvegarde avec son nom original ds le dossier images
                $fileName = $file->getClientOriginalName();
                $file->move($this->getParameter('images_directory'), $fileName);
                //Insertion du nom de l'image dans l'entité produit
                $produit->setLienImage($fileName);
            } else {
                //Si pas de fichier image choisit, message flashbag 
                $session = $request->getSession();
                $session->getFlashBag()->add('message', 'le choix d\'une image pour le produit est obligatoire');
                $session->set('statut', 'danger');
                //On redirige le client vers le formulaire
                return $this->redirect($this->generateUrl('insert'));
            }

            //Insertion des données dans la bdd
            $em->persist($produit);
            $em->flush();
            //Message flash de succès
            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Le produit a bien été sauvegardé');
            $session->set('statut', 'success');
            //Redirection vers la liste
            return $this->redirect($this->generateUrl('app_liste_produits'));
        }

        return $this->render('admin/index.html.twig', [
            'title' => 'Insertion d\'un nouveau produit',
            'form_produit' => $formProduit->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, int $id): Response
    {
        //Récupération de l'objet Produit correspondant à l'id en paramètre
        $em = $this->getDoctrine()->getManager();
        $produitRepository = $em->getRepository(Produit::class);
        $produit = $produitRepository->find($id);

        //Récupération du lien image existant
        $imgOld = $produit->getLienImage();

        //Création du formulaire
        $formProduit = $this->createForm(ProduitType::class, $produit); 
        $formProduit->add('Modifier', SubmitType::class);
        $formProduit->handleRequest($request);
        
        //Test de la présence de données en post et leur validité puis traitement
        if($request->isMethod('post') && $formProduit->isValid()) {

            //Récupération du fichier image
            $file = $formProduit['lienImage']->getData();
            //Si le fichier est de type string (nothing par défault) le client n'a pas choisit d'image
            if(!is_string($file)) {
                //Sauvegarde avec son nom original ds le dossier images
                $fileName = $file->getClientOriginalName();
                $file->move($this->getParameter('images_directory'), $fileName);
                //Insertion du nom de l'image dans l'entité produit
                $produit->setLienImage($fileName);
            } else {
                //Si pas de fichier image choisit, on garde l'ancien 
                $produit->setLienImage($imgOld);
            }

            //Insertion des données dans la bdd
            $em->persist($produit);
            $em->flush();
            //Message flash de succès
            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Le produit a bien été mis à jour');
            $session->set('statut', 'success');
            //Redirection vers la liste
            return $this->redirect($this->generateUrl('app_liste_produits'));
        }

        return $this->render('admin/index.html.twig', [
            'title' => 'Insertion d\'un nouveau produit',
            'form_produit' => $formProduit->createView(),
        ]);
    }

    /**
     * @Route("/delete{id}", name="delete")
     */
    public function delete(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $produitRepository = $em->getRepository(Produit::class);
        $produit = $produitRepository->find($id);
        $em->remove($produit);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('message', 'Le produit a bien été supprimé');
        $session->set('statut', 'success');
        //Redirection vers la liste
        return $this->redirect($this->generateUrl('app_liste_produits'));
    }

    /**
     * @Route("/insert-inside-json", name="insert_inside_json")
     */
    public function insertInsideJson(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, ['label'=>'Insérer un produit'])
            ->getForm();

            if($request->isMethod('post'))
                return new JsonResponse($request->request->all());
                //dd($request->request->all());

        return $this->render('admin/index.html.twig', [
            'title' => 'Test de formulaire créé directement dans le controlleur',
            'form_produit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/insert-outside-json", name="insert_outside_json")
     */
    public function insertOutsideJson(Request $request): Response
    {
        $produit = new Produit;
        // Appel du formulaire externe
        $formProduit = $this->createForm(ProduitType::class, $produit); 
        //echo ProduitType::class;

        //Ajout du champ de validation du formulaire
        $formProduit->add('Ajouter', SubmitType::class);

        if($request->isMethod('post'))
            return new JsonResponse($request->request->all());
            //dd($request->request->all());

        return $this->render('admin/index.html.twig', [
            'title' => 'Insertion d\'un nouveau produit',
            'form_produit' => $formProduit->createView(),
        ]);
    }
}
