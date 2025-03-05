<?php

namespace App\Controller;

use App\Service\JWTService;
use App\Repository\UtilisateurRepository;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route(path: '/mod-de-pase-oublie', name: 'forgotten_password')]
    public function forgottenPassword(Request $request,
    UtilisateurRepository $utilisateurRepository,
    JWTService $jwt) : Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$utilisateur = $utilisateurRepository->findOneByEmail($form->get('email')->getData());


if ($utilisateur) {

    $header = ['typ'=> 'JWT',
    'alg'=> 'HS256'];

    $payload = ['utilisateur_id'=> $utilisateur->getId()];

    $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

    $url = $this->generateUrl('reset_password', ['token'=> $token], UrlGeneratorInterface::ABSOLUTE_URL);
    
}

$this->addFlash('danger', 'Un probleme est survenu');
return $this->redirectToRoute('app_login');

}
        return $this->render('security/reset_password.html.twig', [
            'requestPassForm' => $form->createView(),
        ]);
   }





   
   #[Route(path: '/mod-de-pase-oublie/{token}', name: 'reset_password')]
   public function resetPassword() : Response
   {
// 22min28 pour faire le reinitialisation du mot de passe
       
   }
}
