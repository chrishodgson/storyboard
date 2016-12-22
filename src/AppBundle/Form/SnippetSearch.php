<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SnippetSearch extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchText', TextType::class, [
                'attr'=>['placeholder'=>'Search for...'],
                'label' => false,
                'required' => false,
            ])
            ->add('language', EntityType::class, [
                'class' => 'AppBundle:Language',
                'label' => false,
                'required' => false,
                'empty_data'  => null,
                'placeholder' => 'Choose the language',
            ])
            ->add('submit', SubmitType::class, [
                'attr'=>['class'=>'btn-primary'],
                'label' => 'Search'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_snippet_search';
    }

}
