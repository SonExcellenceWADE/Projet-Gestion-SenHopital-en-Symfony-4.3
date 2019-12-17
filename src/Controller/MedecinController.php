<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Entity\Specialite;
use App\Generator\MatriculeGenerator;
use App\Repository\MedecinRepository;
use App\Repository\SpecialiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedecinController extends AbstractController
{
    /**
     * @Route("/medecin", name="admin.medecin.show")
     */
    public function showMedecin(MedecinRepository $repo )
    {
      
       $medecins = $repo->findAll();


        return $this->render('medecin/listeMedecin.html.twig', [
            'medecins' => $medecins,
            'menu_med' => 'medecin'
        ]);
    }


     /**
     * @Route("/medecin/add", name="admin.medecin.add")
     * @Route("/medecin/edit/{id}", name="admin.medecin.edit")
     */
    public function addUpdateMedecin(Medecin $medecin = null, Request $request, 
                     MedecinRepository $repo, MatriculeGenerator $matgenerator)
    {
      

      if (!$medecin) {
        $medecin = new Medecin();
      }

    $form = $this->createForm(MedecinType::class, $medecin);

    $form->handleRequest($request);


     if($form->isSubmitted() && $form->isValid()){

      //injection des donnees du formulaire dans la variable medecin
      $medecin = $form->getData();

      $this->addFlash('success', 'Medecin ajoute avec succes.');
      
         $entityManager = $this->getDoctrine()->getManager();

         $matricule= $matgenerator->generateMat($medecin);
         $medecin->setMatricule($matricule);
         
         $entityManager->persist($medecin);
         
         $entityManager->flush(); 

         return $this->redirectToRoute('admin.medecin.show');

        }
       
        return $this->render('medecin/form.html.twig', [
            'form' => $form->createView(),
            'editMedecin' => $medecin->getId() !== null,
            'medecin' => $medecin,
            'menu_med' => 'medecin',
            'mainNavRegistration' => true,
            
      
        ]);

    }

    /**
     * @Route("/medecin/delete/{id}", name="admin.medecin.delete")
     */

    public function deleteMedecin(Request $request, MedecinRepository $repo, $id)
    {
      $medecin= $repo->find($id);

      $form = $this->createForm(MedecinType::class, $medecin);

      $form->handleRequest($request);

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($medecin);
      $entityManager->flush();

      return $this->redirectToRoute('admin.medecin.show');
    }


    /**
     * @Route("/service/specialites/", name="services.specialite")
     * @return JsonResponse
     */
  
    public function findSpecialiteofService(SpecialiteRepository $repo, Request $request)
    {
      
        $specialites = $repo->createQueryBuilder('s')
            ->andWhere('s.service = :serviceid')
            ->setParameter('serviceid', $request->query->get('id'))
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
       $tabspecialite=[];
       
       foreach ($specialites as  $specialite) {
             $tabspecialite[]= array(
               'id' => $specialite->getId(),

               'libelle' => $specialite->getLibelle()
             );

            
       }

     return new JsonResponse( $tabspecialite);
      
    }

}
