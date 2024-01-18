<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\AdsRepository;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageController extends AbstractController
{
    #[Route('/sendMessage/{adId}/{messageId}', name: 'app_sendMessage')]
    public function sendMessage(
        Request $request,
        EntityManagerInterface $em,
        $adId,
        $messageId = null,
        TokenStorageInterface $tokenStorage,
        UserRepository $userRepository,
        AdsRepository $adsRepository,
        MessageRepository $messageRepository,

    ): Response
    {
        $sender =  $tokenStorage->getToken()->getUser();
      
        //Si l'Ad trouvée : ad.userId = $sender ALORS
        //Cherche l'annonce dont l'id est dans l'url
        $ad = $adsRepository->find($adId);
        //Cherche celui qui a posté l'annonce
        $poster = $ad->getUser();
        $receiver = $messageRepository->find($messageId)->getSender();
       
        //Si celui qui a posté l'annonce n'est pas celui dont la session est en cours, c'est que l'utilisateur en cours veut contacter l'annonceur
        if ($poster !== $sender ) {
            $receiver = $ad->getUser();
            //Sinon, c'est que l'utilisateur (qui est l'annonceur initial), veut contacter l'envoyeur du message initial
        } else {
            //retrouver le posteur de l'annonce initiale (message.sender_id) là ou message.ad_Id  = {adId}
            $receiver = $messageRepository->find($messageId)->getSender();
        }
       
        //Seulement dans le cadre d'un contact au click sur l'annonce, sinon le $receiver est (dans la page html my_messages) : message.sender.id
         
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new Message();
            $message->setTitle($form->get('title')->getData());
            $message->setText($form->get('text')->getData());
            $message->setSender($sender);
            $message->setReceiver($receiver);
            $message->setAd($ad);
            $message->setIsRead(false);
            $em->persist($message);
            $em->flush();
            dd($message);
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

#[Route('/update-message-status/{id}', name: 'update_message_status')]
    public function updateMessageStatus($id, EntityManagerInterface $entityManager, Request $request)
    {
        $message = $entityManager->getRepository(Message::class)->find($id);

        if (!$message) {
            return new JsonResponse(['error' => 'Message not found'], 404);
        }

        if ($request->isXmlHttpRequest()) {
            // Si la requête est une requête AJAX
            $message->setIsRead(true);
            $entityManager->flush();

            return new JsonResponse(['success' => true]);
        } else {
            // Si la requête n'est pas une requête AJAX
            $message->setIsRead(true);
            $entityManager->flush();

            // Notez que nous ne renvoyons rien ici pour éviter le rafraîchissement de la page
            return new JsonResponse();
        }
    }
}
