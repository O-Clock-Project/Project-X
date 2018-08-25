<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                'constraints' => [ 
                    New NotBlank()
                ],
                'label' => 'Pseudo', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Pseudo'  
                ]
            ])

            ->add('email', EmailType::class, [
                'constraints' => [ 
                    New NotBlank(),
                    new Email([
                        'mode' => 'html5'
                    ])
                ],
                'label' => 'Email', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Email'  
                ],
                
            ])
            ->add('password', RepeatedType::class, [
                'constraints' => [ 
                    New NotBlank()
                ],
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

            ])
            ->add('firstname', TextType::class, [
                'constraints' => [ 
                    New NotBlank()
                ],
                'label' => 'Prenom', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Prénom'  
                ],  
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [ 
                    New NotBlank()
                ],
                'label' => 'Nom', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Nom'  
                ], 
            ])
            ->add('birthday', BirthdayType::class, [
                'constraints' => [ 
                    New NotBlank()
                ],
                'widget' => 'choice',
                'format' => 'dd-MM-yyyy',
                'label' => 'Date de naissance',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ] 
                    
            ])
         
            ->add('zip', NumberType::class, [
                'constraints' => [ 
                    New NotBlank()
                ],
                'label' => 'Code postal', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Code Postal'  
                ],
                'invalid_message' => 'Le code postal saisi n\'est pas un chiffre',
            ])
            ->add('pseudogithub', TextType::class, [
                'constraints' => [ 
                    New NotBlank()
                ],
                'label' => 'Pseudo Github', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Pseudo Github'  
                ],
            ])

       
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}