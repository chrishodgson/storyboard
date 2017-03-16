<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorySearch extends AbstractType
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
            ->add('project', EntityType::class, [
                'class' => 'AppBundle:Project',
                'label' => false,
                'required' => false,
                'empty_data'  => null,
                'placeholder'=>'Choose a project',
            ])
            ->add('showFavourites', CheckboxType::class, [
                'label_attr' => ['class'=>'padd'],
                'label' => 'Show favourites',
                'required' => false
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
        return 'appbundle_story_search';
    }
}
