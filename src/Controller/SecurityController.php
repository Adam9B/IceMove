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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{

    
    
    // Route pour la page de connexion
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupérer le dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    // Route pour la déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // Route pour demander la réinitialisation de mot de passe
    #[Route(path: '/mod-de-pase-oublie', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UtilisateurRepository $utilisateurRepository,
        JWTService $jwt,
        MailerInterface $mailer
    ): Response {
        // Récupérer la clé secrète depuis .env
        $secretKey = $_ENV['JWT_SECRET_KEY'];

        // Créer le formulaire de demande de réinitialisation
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'e-mail saisi par l'utilisateur
            $email = $form->get('email')->getData();

            // Rechercher l'utilisateur dans la base de données
            $utilisateur = $utilisateurRepository->findOneByEmail($email);

            if ($utilisateur) {
                // Générer un token JWT
                $payload = [
                    'user_id' => $utilisateur->getId(),
                    'exp' => time() + 3600, // Token valide pendant 1 heure
                ];

                $token = $jwt->generateToken($payload, $secretKey, 3600);

                // Générer l'URL de réinitialisation
                $resetUrl = $this->generateUrl(
                    'reset_password',
                    ['token' => $token],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                // Envoyer un e-mail avec le lien de réinitialisation
                $emailMessage = (new Email())
                    ->from('no-reply@votre-site.com')
                    ->to($utilisateur->getEmail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->html("<p>Bonjour,</p><p>Pour réinitialiser votre mot de passe, cliquez sur le lien suivant : <a href='$resetUrl'>$resetUrl</a>.</p>");

                $mailer->send($emailMessage);

                // Informer l'utilisateur que l'e-mail a été envoyé
                $this->addFlash('success', 'Un e-mail de réinitialisation a été envoyé.');
                return $this->redirectToRoute('app_login');
            }

            // Si l'utilisateur n'est pas trouvé, ne rien dire pour des raisons de sécurité
            $this->addFlash('danger', 'Aucun compte n\'est associé à cet e-mail.');
            return $this->redirectToRoute('forgotten_password');
        }

        // Afficher le formulaire
        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView(),
        ]);
    }

    // Route pour réinitialiser le mot de passe
    #[Route(path: '/mod-de-pase-oublie/{token}', name: 'reset_password')]
    public function resetPassword(string $token, JWTService $jwt): Response
    {
        // Valider le token JWT
        try {
            $decodedToken = $jwt->validateToken($token, $_ENV['JWT_SECRET_KEY']);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Le lien de réinitialisation est invalide ou expiré.');
            return $this->redirectToRoute('forgotten_password');
        }

        // Récupérer l'ID de l'utilisateur depuis le token
        $userId = $decodedToken->data->user_id;

        // TODO : Implémenter la logique pour réinitialiser le mot de passe
        // Par exemple, afficher un formulaire pour saisir un nouveau mot de passe

        return $this->render('security/reset_password_form.html.twig', [
            'token' => $token,
        ]);
    }
}