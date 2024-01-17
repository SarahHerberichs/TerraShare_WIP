<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//lorsque compte validé
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
       
        $form= $this->createForm(ProfileType::class,$this->getUser());
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $user = $form->getData();
            $plainPassword = $form->get('plainPassword')->getData();

            if (null !== $plainPassword){
                $user->setPassword($passwordHasher->hashPassword($user,$plainPassword));
            }
            $em->flush();
            $this->addFlash('success', 'Votre profil à été mis à jour');
        }

        return $this->render('profile/index.html.twig', [
            'form' =>$form->createView()
        ]);
    }
}