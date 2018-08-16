<?php

namespace App\Form;

use App\Entity\Announcement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('created_at')
            ->add('updated_at')
            ->add('is_active')
            ->add('title')
            ->add('body')
            ->add('frozen')
            ->add('closing_at')
            ->add('author')
            ->add('promotions')
            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
        ]);
    }
}
