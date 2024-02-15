<?php
namespace App\Services;

use App\Entity\User;
use App\Entity\Message;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MessageNotificationService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewMessageNotification(User $user,Message $message)
    {
    
        // Construction de l'e-mail pour informer l'utilisateur du nouveau message
        $email = (new TemplatedEmail())
        
            ->from(new Address('terrashare@outlook.fr', 'Administrateur TerraShare'))
            ->to($user->getEmail())
            ->subject('Nouveau message reçu')
            ->htmlTemplate('messages/new_message_notification.html.twig')
            ->context([
                'user' => $user,
                'sujet' => $message->getTitle(),
                'message'=> $message->getText()
            ]);

        // Envoi de l'e-mail
        $this->mailer->send($email);
    }
}
