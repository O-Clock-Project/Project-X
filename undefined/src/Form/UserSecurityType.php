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
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'minMessage' => ': Votre pseudo est trop court (2 caractères minimum)',
                        'max' => 20,
                        'maxMessage' => ': Votre pseudo est trop long (20 caractères maximum)'
                    ])
                ],
                'label' => 'Pseudo', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Pseudo'  
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [ 
                    new NotBlank(),
                    new Email([
                        'checkHost' => 'true'
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
                    new NotBlank(),
                    new Length([
                        'min' => 8,
                        'minMessage' => ': Votre mot de passe est trop court (8 caractères minimum)',
                        'max' => 20,
                        'maxMessage' => ': Votre mot de passe est trop long (20 caractères maximum)'
                    ])
                ],
                'type' => PasswordType::class,
                'invalid_message' => ': Le mot de passe saisi n\'est pas le même',
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
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'minMessage' => ': Votre prénom est trop court (2 caractères minimum)',
                        'max' => 30,
                        'maxMessage' => ': Votre prénom est trop long (30 caractères maximum)'
                    ])
                ],
                'label' => 'Prenom', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Prénom'  
                ],  
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [ 
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'minMessage' => ': Votre nom est trop court (2 caractères minimum)',
                        'max' => 30,
                        'maxMessage' => ': Votre nom est trop long (30 caractères maximum)'
                    ])
                ],
                'label' => 'Nom', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Nom'  
                ], 
            ])
            ->add('birthday', BirthdayType::class, [
                'constraints' => [ 
                    new NotBlank()
                ],
                'widget' => 'choice',
                'format' => 'dd-MM-yyyy',
                'label' => 'Date de naissance',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ] 
                    
            ])
         
            ->add('zip', TextType::class, [
                'constraints' => [ 
                    new NotBlank(),
                    new Length([
                        'min' => 4,
                        'minMessage' => ': Votre code postal est trop court (4 caractères minimum)',
                        'max' => 10,
                        'maxMessage' => ': Votre code postal est trop long (10 caractères maximum)'
                    ])
                ],
                'label' => 'Code postal', 
                'attr' => [
                    'class' => 'signup-form-input',
                    'placeholder' => 'Code Postal'  
                ],
            ])
            ->add('pseudogithub', TextType::class, [
                'constraints' => [ 
                    new NotBlank()
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