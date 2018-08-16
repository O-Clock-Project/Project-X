<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('created_at')
            ->add('updated_at')
            ->add('is_active')
            ->add('username')
            ->add('first_name')
            ->add('last_name')
            ->add('email')
            ->add('password')
            ->add('pseudo_github')
            ->add('zip')
            ->add('birthday')
            ->add('favorites')
            ->add('certified_bookmarks')
            ->add('speciality')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
