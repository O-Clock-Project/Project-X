<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserSecurityType extends AbstractType
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
                'invalid_message' => 'Le mot de passe saisi n\'est pas le même',
                'options' => [
                    'attr' => [
                        'class' => 'signup-form-input',
                        'placeholder' => 'Mot de passe' 
                    ]
                ],
                'required' => true,
                 'first_options'  => [
                     'label' => 'Votre mot de passe'
                 ],
                 'second_options' => [
                     'label' => 'Confirmer mot de passe'
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
                'widget' => 'choice',
                'format' => 'yyyy-MM-dd',
                'label' => 'Date de naissance',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ] 
                    
            ])
         
            ->add('zip', NumberType::class, [
                'label' => 'Code postal', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Code Postal'  
                ],
                'invalid_message' => 'Le code postal saisi n\'est pas un chiffre',
            ])
            ->add('pseudogithub', TextType::class, [
                'label' => 'Pseudo Github', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Pseudo Github'  
                ],
            ])
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