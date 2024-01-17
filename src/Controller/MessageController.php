<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\AdsRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageController extends AbstractController
{
    #[Route('/sendMessage/{adId}', name: 'app_sendMessage')]
    public function sendMessage(
        Request $request,
        EntityManagerInterface $em,
        $adId,
        TokenStorageInterface $tokenStorage,
        AdsRepository $adsRepository

    ): Response
    {
        $sender = $tokenStorage->getToken()->getUser();
        $ad = $adsRepository->find($adId);
        $receiver = $ad->getUser();

        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new Message();
            $message->setTitle($form->get('title')->getData());
            $message->setText($form->get('text')->getData());
            $message->setSender($sender);
            $message->setReceiver($receiver);
            $message->setAd($ad);
            $em->persist($message);
            $em->flush();

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
}
