<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank(['message'=>'Adresse mail manquante']),
                    new Email (['message'=>'Adresse mail invalide']),
                ]
            ])
            ->add('pseudo', TextType::class, [
                'constraints' => [
                new NotBlank(['message'=> 'Pseudo manquant']),
                new Length([
                    'min'=>3,
                    'minMessage'=> 'Le pseudo doit faire au moins {{ limit }} caractères',
                    'max'=>30,
                    'maxMessage'=> 'Le pseudo doit faire au moins {{ limit }} caractères',
                ]),
            ],
        ])
        ->add('currentPassword', PasswordType::class, [
            'mapped' => false,
            'constraints' => [
                new NotBlank(['message' => 'Veuillez entrer votre mot de passe actuel']),
            ],
        ])
            ->add('plainPassword', RepeatedType::class,[
                'type'=>PasswordType::class,
                'invalid_message'=> 'Les mots de pass ne correspondent pas.',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Length ([
                        'min' =>8,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                        'max'=>4096,
                        'maxMessage'=> 'Le mot de passe ne doit pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ]);
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        
        ]);
    }
}
