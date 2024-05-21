<?php

namespace App\Form;

use App\Entity\Ads;
use App\Entity\Type;
use App\Entity\Cities;
use App\Entity\Departments;
use App\Entity\Status;
use App\Form\PhotosType;
use App\Entity\Transaction;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'maxlength' => 45,
                ],
            ])
            ->add('text', TextareaType::class)
            
            ->add('city', EntityType::class, [
                'class' => Cities::class,
                'data' => $options['city'], 
                'mapped' => false,
                'choice_label' => 'name', 
                'attr' => ['style' => 'display:none;'], 
            ])

            ->add('photos', CollectionType::class, [
                'entry_type'=> PhotosType::class,
                'allow_add'=> true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'mapped'=>false,
                'label' =>false
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
            ])
            ->add('price',MoneyType::class, [
                'required' => false,
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control', 
                 
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
            'city' => null,
        ]);
    }
}

