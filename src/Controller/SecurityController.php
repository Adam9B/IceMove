<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\JWTService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * Page de connexion
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère l'erreur de connexion si elle existe
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier e-mail saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Déconnexion
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la logique de déconnexion.');
    }

    /**
     * Demande de réinitialisation de mot de passe
     */
    #[Route(path: '/reset-password', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UtilisateurRepository $utilisateurRepository,
        JWTService $jwt,
        MailerInterface $mailer
    ): Response {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            $user = $utilisateurRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet e-mail.');
                return $this->redirectToRoute('forgotten_password');
            }

            // Génération du token JWT
            $secretKey = $_ENV['JWT_SECRET_KEY'];
            $payload = [
                'user_id' => $user->getId(),
                'exp' => time() + 3600, // Token valide pendant 1 heure
            ];

            $algorithm = 'HS256'; // Replace with the correct algorithm if needed
            $token = $jwt->generateToken($payload, $secretKey, $algorithm);

            $resetUrl = $this->generateUrl(
                'reset_password',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $emailMessage = (new Email())
                ->from('no-reply@votre-site.com')
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->html("<p>Bonjour,</p><p>Pour réinitialiser votre mot de passe, cliquez sur le lien suivant : <a href='$resetUrl'>$resetUrl</a>.</p>");

            $mailer->send($emailMessage);

            $this->addFlash('success', 'Un e-mail de réinitialisation a été envoyé.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView(),
        ]);
    }

    /**
     * Formulaire de réinitialisation de mot de passe
     */
    #[Route(path: '/reset-password/{token}', name: 'reset_password')]
    public function resetPassword(string $token, JWTService $jwt): Response
    {
        try {
            $decodedToken = $jwt->validateToken($token, $_ENV['JWT_SECRET_KEY']);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Le lien de réinitialisation est invalide ou expiré.');
            return $this->redirectToRoute('forgotten_password');
        }

        $userId = $decodedToken->data->user_id;

        // TODO : Implémenter la logique de réinitialisation du mot de passe ici

        return $this->render('security/reset_password_form.html.twig', [
            'token' => $token,
        ]);
    }
}