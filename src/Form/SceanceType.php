<?php

namespace App\Form;

use App\Entity\Exercice;
use App\Entity\Programme;
use App\Entity\Sceance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SceanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('jour')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('description')
            ->add('programme', EntityType::class, [
                'class' => Programme::class,
                'choice_label' => 'id',
            ])
            // ->add('exercices', EntityType::class, [
            //     'class' => Exercice::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sceance::class,
        ]);
    }
}
