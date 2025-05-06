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
            
            // Suppression du champ 'exercices' ici pour éviter le chargement de tous les exercices.
            // À la place, tu pourras ajouter les exercices via un bouton ou une autre page (ex : /exercice/{id}/ajouter-a-seance).
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sceance::class,
        ]);
    }
}
