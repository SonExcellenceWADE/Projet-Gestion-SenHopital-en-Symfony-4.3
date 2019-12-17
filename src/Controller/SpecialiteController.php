<?php

namespace App\Controller;

use App\Entity\Specialite;
use App\Form\SpecialiteType;
use App\Repository\SpecialiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SpecialiteController extends AbstractController
{
    /**
     * @Route("/specialite", name="admin.specialite.show")
     */
    public function showSpecialite(SpecialiteRepository $repo)
    {

       $specialites = $repo->findAll();

   
        return $this->render('specialite/listeSpecialite.html.twig', [
            'specialites' => $specialites,

            'memu_spec' =>  'specialite'
        ]);
    }

      /**
     * @Route("/specialite/add", name="admin.specialite.add")
     * @Route("/specialite/edit/{id}", name="admin.specialite.edit")
     */


    public function addUpdateSpecialite(Specialite $specialite = null, Request $request )
    {
      if (!$specialite) {
        $specialite = new Specialite();
      }

    $form = $this->createForm(SpecialiteType::class, $specialite);


    $form->handleRequest($request);

     if($form->isSubmitted() && $form->isValid()){

       
         $entityManager = $this->getDoctrine()->getManager();

         $entityManager->persist($specialite);
         $entityManager->flush(); 

         return $this->redirectToRoute('admin.specialite.show');

        }
       
        return $this->render('specialite/form.html.twig', [
            'form' => $form->createView(),
            'editSpecialite' => $specialite->getId() !== null,

            'specialite' => $specialite,

            'memu_spec' =>  'specialite'

            
        ]);

    }
    /**
     * @Route("/specialite/delete/{id}", name="admin.specialite.delete")
     */

    public function deleteSepecialite(Request $request, SpecialiteRepository $repo, $id)
    {
      $specialite= $repo->find($id);

      $form = $this->createForm(SpecialiteType::class, $specialite);

      $form->handleRequest($request);

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($specialite);
      $entityManager->flush();

      return $this->redirectToRoute('admin.specialite.show');
    }
}
