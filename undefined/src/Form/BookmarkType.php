<?php

namespace App\Form;

use App\Entity\Bookmark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookmarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('created_at')
            ->add('updated_at')
            ->add('is_active')
            ->add('title')
            ->add('resume')
            ->add('url')
            ->add('image')
            ->add('banned')
            ->add('published_at')
            ->add('author')
            ->add('support')
            ->add('difficulty')
            ->add('user')
            ->add('faved_by')
            ->add('certified_by')
            ->add('tags')
            ->add('locale')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bookmark::class,
        ]);
    }
}
