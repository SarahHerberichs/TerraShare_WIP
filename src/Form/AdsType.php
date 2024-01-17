<?php

namespace App\Form;



use App\Entity\Ads;
use App\Entity\Type;
use App\Entity\Cities;
use App\Entity\Status;
use App\Form\PhotosType;
use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title', TextType::class)
            ->add('text', TextareaType::class)
            // Ajoutez l'option city pour lier l'annonce à la ville
            
            ->add('city', EntityType::class, [
                'class' => Cities::class,
                'data' => $options['city'], // Utilisez la ville passée en option
                'mapped' => false, // Ne mappez pas directement à l'entité Ads
                'choice_label' => 'name', // Ou toute autre propriété que vous souhaitez afficher
                'attr' => ['style' => 'display:none;'], // Ajoutez du CSS pour cacher visuellement le champ
            ])

            ->add('photos', CollectionType::class, [
                'entry_type'=> PhotosType::class,
                'allow_add'=> true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'mapped'=>false
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
            ])
            ->add('transaction', EntityType::class, [
                'class' => Transaction::class,
                'choice_label' => 'name',
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
            // Ajoutez l'option city pour lier l'annonce à la ville
            'city' => null,
        ]);
    }
}

