<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController{
	
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response{
		if($this->isGranted('IS_AUTHENTICATED')) return $this->redirectToRoute('app_main');
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
		
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
			//$form->addError(new FormError());
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.twig', [
			'title'=>RegistrationFormType::TITLE,
            'form' => $form->createView(),
        ]);
    }
	
	#[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response{
		if($this->isGranted('IS_AUTHENTICATED')) return $this->redirectToRoute('app_main');
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();		
		return $this->render('security/login.twig', [
			'title'=>"Авторизация",
			'last_username'=>$lastUsername,
			'error'=> $error
		]);
		
    }
	
	
	
}
