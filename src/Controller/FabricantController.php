<?php

namespace App\Controller;

use App\Entity\Fabricant;
use App\Form\FabricantType;
use App\Repository\FabricantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** 
 * @Route("/admin") 
 */
class FabricantController extends AbstractController
{
    /**
     * @Route("/fabricants", name="all_fabricants")
     */
    public function see(FabricantRepository $fabRepo):Response
    {
        $listeFabs = $fabRepo->findAll();

        return $this->render('fabricant/index.html.twig', [
            'title' => 'Les Fabricants',
            'actif' => 'fabricants',
            'listeFabs' => $listeFabs,
        ]);
    }

    /**
     * @Route("/new-fabricant", name="new_fabricant")
     */
    public function insert(Request $request, ManagerRegistry $doctrine): Response
    {
        $fabricant = new Fabricant;

        $formFabricant = $this->createForm(FabricantType::class, $fabricant);
        $formFabricant->add('Ajouter', SubmitType::class);
        $formFabricant->handleRequest($request);

        if($request->isMethod('post') && $formFabricant->isValid()) {

            $em = $doctrine->getManager();
            $em->persist($fabricant);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Le fabricant à bien été ajouté');
            $session->set('statut', 'success');

            return $this->redirect($this->generateUrl('all_fabricants'));
        }

        return $this->render('fabricant/form-fab.html.twig', [
            'title' => 'Ajout d\'un fabricant',
            'form_fabricant' => $formFabricant->createView(),
        ]);
    }

    /**
     * @Route("/update-fabricant/{id}", name="update_fabricant")
     */
    public function update(FabricantRepository $fabRepo, int $id, Request $request, ManagerRegistry $doctrine):Response
    {
        $fabricant = $fabRepo->find($id);

        $formFabricant = $this->createForm(FabricantType::class, $fabricant);
        $formFabricant->add('Modifier', SubmitType::class);
        $formFabricant->handleRequest($request);

        if($request->isMethod('post') && $formFabricant->isValid()) {

            $em = $doctrine->getManager();
            $em->persist($fabricant);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Le fabricant à bien été modifié');
            $session->set('statut', 'success');

            return $this->redirect($this->generateUrl('all_fabricants'));
        }

        return $this->render('fabricant/form-fab.html.twig', [
            'title' => 'Les Fabricants',
            'actif' => 'fabricants',
            'form_fabricant' => $formFabricant->createView(),
        ]);
    }

    /**
     * @Route("/delete-fabricant/{id}", name="delete_fabricant")
     */
    public function delete(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $em = $doctrine->getManager();
        $fabRepo = $em->getRepository(Fabricant::class);
        $fab = $fabRepo->find($id);
        $em->remove($fab);
        $em->flush();
        $session = $request->getSession();
        $session->getFlashBag()->add('message', 'Le fabricant a bien été supprimé');
        $session->set('statut', 'success');
        //Redirection vers la liste
        return $this->redirect($this->generateUrl('all_fabricants'));
    }


}
