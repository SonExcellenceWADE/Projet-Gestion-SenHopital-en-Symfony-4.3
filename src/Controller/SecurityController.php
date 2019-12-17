<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;




class SecurityController extends AbstractController
{
    private $encoder;
   
    public function __construct(UserPasswordEncoderInterface $encoder){
            $this->encoder = $encoder;
    }

    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(SymfonyRequest $request) {

        $user = new User();
       $form= $this->createForm(RegistrationType::class, $user);
      
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
           
       $plainPassword = $user->getPassword();
        
        $encoded = $this->encoder->encodePassword($user, $plainPassword);
  
        $user->setPassword($encoded); 



       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($user);
       $entityManager->flush();

           return $this->redirectToRoute('security_login');
       }
       return $this->render('security/registration.html.twig',[
           'form' => $form->createView()
       ]);
    }
     /**
      * @Route("/connexion", name="security_login")
      */
    public function login(Request $request, AuthenticationUtils $utils){
 
       $error = $utils->getLastAuthenticationError();
       $lastUsername = $utils->getLastUsername();

        return $this->render('security/login.html.twig',[
            'error'            => $error,
             'last_username'    => $lastUsername
        ]);
       
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
   public function logout(){
    return $this->render('security/login.html.twig',[
        'menu_logout' => 'logout'
    ]);
   }
    } 

