<?php

namespace App\Form;

use App\Entity\Vote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('created_at')
            ->add('updated_at')
            ->add('is_active')
            ->add('value')
            ->add('bookmark')
            ->add('voter')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vote::class,
        ]);
    }
}
