<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MyMailer
{

    // J'ai besoin dans mon service du mailer interface, je fais donc une "injection de dépendance", le conteneur de service se charge du reste.
    private $mailer;
    private $adminMail;

    public function __construct(MailerInterface $mailer, string $adminMail)
    {
        $this->mailer = $mailer;
        $this->adminMail = $adminMail;
    }

    /**
     * Fonction pour envoyer un mail
     *
     * @param string $subject Subject of the mail
     * @param string $text content
     */
    public function send(string $subject, string $text)
    {
        // création d'un objet email, templatedEmail permet d'utiliser twig
        $email = (new Email())
            // de quel adresse le mail est envoyé
            // ! ici l'adresse doit être renseigné sur votre compte mailjet
            ->from($this->adminMail)
            // to, vers qui va le mail
            ->to("amgadgaafr@hotmail.fr")
            // le sujet de l'email
            ->subject($subject)
            // le contenu qui est un lien vers le fichier twig
            ->text($text);
        // les variables necessaires au template
        // ->context($context);

        $this->mailer->send($email);
    }
}
