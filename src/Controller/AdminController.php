<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @Route("/service", name="admin.service.show")
     */
    public function showService(ServiceRepository $repo )
    {

       $services = $repo->findAll();

   
        return $this->render('service/listeService.html.twig', [
            'services' => $services,

            'menu_serv' =>  'service'
        ]);
    }

    /**
     * @Route("/service/add", name="admin.service.add")
     * @Route("/service/edit/{id}", name="admin.service.edit")
     */


    public function addService(Service $service = null, Request $request )
    {
      if (!$service) {
        $service = new Service();
      }

    $form = $this->createForm(ServiceType::class, $service);


    $form->handleRequest($request);

     if($form->isSubmitted() && $form->isValid()){

       
         $entityManager = $this->getDoctrine()->getManager();

         $entityManager->persist($service);
         $entityManager->flush(); 

         return $this->redirectToRoute('admin.service.show');

        }
       
        return $this->render('service/form.html.twig', [
            'form' => $form->createView(),
            'editService' => $service->getId() !== null,
            'service' => $service,
            'menu_serv' =>  'service'
            
        ]);

    }

    /**
     * @Route("/service/delete/{id}", name="admin.service.delete")
     */

    public function deleteService( Request $request, ServiceRepository $repo, $id )
    {
      
       $service =$repo->find($id);

       $form = $this->createForm(ServiceType::class, $service);

       $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($service);
        $entityManager->flush();

         return $this->redirectToRoute('admin.service.show');  
        
    }

   
}





     
