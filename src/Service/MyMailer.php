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
    public function send(string $from, string $subject, string $content)
    {
        $email = (new Email())
            ->from($from)
            ->to($this->adminMail)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }
}
