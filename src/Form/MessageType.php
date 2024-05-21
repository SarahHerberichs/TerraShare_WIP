<?php

namespace App\Form;

use App\Entity\Ads;
use App\Entity\User;
use App\Entity\Message;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //réactiver si besoin d'ajouter un titre quelconque et dans MessageController, réactiver ca :
         // $form = $this->createForm(MessageType::class);
        // $form->handleRequest($request);
        //->add('title')
        ->add('title', TextType::class, [
            'data' => $options['adTitle'], // Préremplir le champ avec le titre de l'annonce
            'disabled' => true, // Rend le champ titre désactivé
            'mapped' => false, // Ne mappe pas ce champ à l'entité Message
        ])
        ->add('text');
    }
   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'adTitle' => null,
        ]);
    }
}
