<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SnippetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('story', null, ['disabled'=>true])
            ->add('title')
            ->add('description', null, ['attr'=>['rows'=>3]])
            ->add('language', null, ['placeholder' => 'Choose a language'])
            ->add('status', null, ['placeholder' => 'Choose a status'])
            ->add('code', null, ['attr'=>['rows'=>15]])
            ->add('position', null, ['data' => 0]);
//            ->add('favourite', CheckboxType::class)
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Snippet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_snippet';
    }


}
