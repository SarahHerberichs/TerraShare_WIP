<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Repository\AdsRepository;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Services\MessageNotificationService;

class MessageController extends AbstractController
{

    #[Route("/get-unread-messages-count", name:"get_unread_messages_count")]
    public function getUnreadMessagesCount(MessageRepository $messageRepository): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            // Si l'utilisateur n'est pas connecté
            return new JsonResponse(['count' => 0]);
        }
        
        $unreadMessagesCount = $messageRepository->count(['receiver' => $user, 'isRead' => false]);

        return new JsonResponse(['count' => $unreadMessagesCount]);
    }


    #[Route('/sendMessage/{adId}/{messageId?}', name: 'app_sendMessage')]
    public function sendMessage(
        Request $request,
        EntityManagerInterface $em,
        $adId,
        $messageId = null,
        TokenStorageInterface $tokenStorage,
        UserRepository $userRepository,
        AdsRepository $adsRepository,
        MessageRepository $messageRepository,
        ConversationRepository $conversationRepository,
        MessageNotificationService $messageNotificationService
    ): Response {
            //Le sender est l'utilisateur actuellement authentifié
            $sender = $tokenStorage->getToken()->getUser();
            // Cherche l'annonce dont l'id est dans l'url
            $ad = $adsRepository->find($adId);
            // Initialisation de $receiver
            $receiver = null;

            // Si le messageID a été renseigné dans l'URL
            if ($messageId !== null) {
                //Récupère ttes les infos sur le message
                $message = $messageRepository->find($messageId);
        
                // Si le message existe et l'utilisateur en cours est le destinataire ou l'expéditeur du message,
                //Détermine l'autre interlocuteur
                if ($message && ($message->getReceiver() === $sender || $message->getSender() === $sender)) {
                    $receiver = ($message->getReceiver() === $sender) ? $message->getSender() : $message->getReceiver();
                }
            } else {
                // Si aucun message n'existe encore, définir le receiver sur le poster
                $receiver = $ad->getUser();
            }
            //Cherche la conversation entre les deux parties autour de l'annonce
            $existingConversation = $conversationRepository->findConversationByUsersAndAd($sender, $receiver, $ad);
        
            $form = $this->createForm(MessageType::class);
            $form->handleRequest($request);
            //Insertion du nouveau message dans la BDD
            if ($form->isSubmitted() && $form->isValid()) {
            
                $message = new Message();
                $message->setTitle($form->get('title')->getData());
                $message->setText($form->get('text')->getData());
                $message->setSender($sender);
                $message->setReceiver($receiver);
                $message->setAd($ad);
                $message->setIsRead(false);
                $messageNotificationService->sendNewMessageNotification($receiver,$message);
                $em->persist($message);
                $em->flush();
    
                // Si la conversation n'existe pas encore, ajouter la conversation
                if (!$existingConversation) {
                    $conversation = new Conversation();
                    $conversation->setAd($ad);
                    $conversation->setUser1($sender);
                    $conversation->setUser2($receiver);
                    $em->persist($conversation);
                    $em->flush();
            
                    // Attribuer la conversation au message maintenant que la conversation a un ID
                    $message->setConversation($conversation);
                    $em->persist($message);
                    $em->flush();
                } else {
                    // Si la conversation existe déjà, attribuer la conversation existante au message
                    $message->setConversation($existingConversation);
                    $em->persist($message);
                    $em->flush();
                }
                
                $this->addFlash('success', 'Votre message a été envoyé avec succès!');
                return $this->redirectToRoute('app_home');
            }     

        return $this->render('messages/index.html.twig', [
            'form' => $form->createView(),
            'receiver' => $receiver,
            'sender' => $sender,
            'ad' => $ad
        ]);
    }
    //Consultation des messages
    #[Route('/myMessages', name: 'my_messages')]
    public function myMessages(
        Request $request,
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage,
        MessageRepository $messageRepository

    ): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        $myMessages = $messageRepository->findBy(['receiver' => $user]);
        
        return $this->render('messages/my_messages.html.twig',[
            'messages' => $myMessages
        ]);
      
    }
    //Passe le message en Lu
    #[Route('/update-message-status/{id}', name: 'update_message_status')]
        public function updateMessageStatus($id, EntityManagerInterface $entityManager, Request $request)
        {
            $message = $entityManager->getRepository(Message::class)->find($id);

            if (!$message) {
                return new JsonResponse(['error' => 'Message not found'], 404);
            }

            if ($request->isXmlHttpRequest()) {
                // Si la requête est une requête AJAX n bascule le message comme lu et modif la BDD
                $message->setIsRead(true);
                $entityManager->flush();

                return new JsonResponse(['success' => true]);
            } else {
                // Si la requête n'est pas une requête AJAX
                $message->setIsRead(true);
                $entityManager->flush();

                return new JsonResponse();
            }
        }
}
