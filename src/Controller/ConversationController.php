<?php

namespace App\Controller;

use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ConversationController extends AbstractController
{
    #[Route('/show_conversation/{messageId}', name: 'app_show_conversation')]
    public function showConversation(
        $messageId,
        TokenStorageInterface $tokenStorage,
       MessageRepository $messageRepository,
       ConversationRepository $conversationRepository
    ): Response
    {
        
        //Recup l'utilisateur en cours
        $currentUser = $tokenStorage->getToken()->getUser();
       
        //Recup le message qui vient d'etre clické
        $message = $messageRepository->find($messageId);
        //Recup les deux utilisateurs qui communiquent
        $receiver = $message->getReceiver();
        $sender = $message->getSender();
        
        //Si l'un des deux est l'utilisateur en cours , recherche la conversation 
        if ($currentUser === $sender || $currentUser === $receiver) {
        $ad = $message->getAd();
        $conversation = $conversationRepository->findConversationByUsersAndAd ($currentUser,$sender,$ad);
         
        if($conversation) {
            $messages=$conversation->getMessage();

            return $this->render('messages/show_conversation.html.twig', [
                'conversation' => $conversation,
                'messages' => $messages,
                'ad'=>$ad
            ]);
        }
        }

    }
}
