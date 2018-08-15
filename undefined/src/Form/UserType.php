<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Pseudo'  
                ]
            ])

            ->add('email', TextType::class, [
                'label' => 'Email', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Email'  
                ],
                
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => [
                    'attr' => [
                        'class' => 'signup-form-input',
                        'placeholder' => 'Mot de passe' 
                    ]
                ],
                'required' => true,
                'first_options'  => [
                    'label' => 'Tapez votre mot de passe'
                ],
                'second_options' => [
                    'label' => 'Répètez le mot de passe'
                ],
                'required' => false
            ])
            // ->add('code')
            ->add('firstname', TextType::class, [
                'label' => 'Prenom', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Prénom'  
                ],  
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Nom'  
                ], 
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'Date de naissance',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ], 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Date de naissance'  
                ],
                
            ])
            ->add('zip', IntegerType::class, [
                'label' => 'Code postal', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Code Postal'  
                ],
            ])
            ->add('pseudogithub', TextType::class, [
                'label' => 'Pseudo Github', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Pseudo Github'  
                ],
            ])
            //->add('sexe')
            //->add('isActive')
       
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}