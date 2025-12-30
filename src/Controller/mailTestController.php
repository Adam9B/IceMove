<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class mailTestController extends AbstractController
{
    #[Route('/test-email', name: 'test_email')]
    public function testEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('adambacha95@gmail.com')
            ->to('bachou95100@gmail.com') // Remplacez par votre e-mail
            ->subject('Test d\'envoi d\'e-mail')
            ->text('Ceci est un e-mail de test.');

        try {
            $mailer->send($email);
            return new Response('E-mail envoyé avec succès.');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
        }
    }
}
//j'essaie d'envoyer un mail avec symfony mais j'y arrive pas, je m'aide de qwen chat 

?>