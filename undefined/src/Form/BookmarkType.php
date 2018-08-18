<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Bookmark;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use App\Form\DataTransformer\ManyToEntityTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookmarkType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
        $builder
            ->add('title')
            ->add('resume')
            ->add('url')
            ->add('image')
            ->add('published_at', DateType::class, [
                'widget' => 'single_text',
            ] )
            ->add('author')
        ;



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bookmark::class,
            'csrf_protection' => false
        ]);
    }
}
