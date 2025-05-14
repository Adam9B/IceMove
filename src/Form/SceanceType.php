<?php

namespace App\Form;

use App\Entity\Programme;
use App\Entity\Sceance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class SceanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['utilisateur']; // 👈 on récupère l'utilisateur

        $builder
            ->add('titre')
            ->add('jour', ChoiceType::class, [
                'choices'  => [
                    'Lundi' => 'Lundi',
                    'Mardi' => 'Mardi',
                    'Mercredi' => 'Mercredi',
                    'Jeudi' => 'Jeudi',
                    'Vendredi' => 'Vendredi',
                    'Samedi' => 'Samedi',
                    'Dimanche' => 'Dimanche',
                ],
                'placeholder' => 'Choisissez un jour',
                'label' => 'Jour de la séance',
            ])
            ->add('description')
            ->add('programme', EntityType::class, [
                'class' => Programme::class,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('p')
                        ->where('p.utilisateur = :user')
                        ->setParameter('user', $user);
                },
                'choice_label' => 'titre',
                'placeholder' => 'Aucun programme',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sceance::class,
            'utilisateur' => null, // 👈 indispensable pour pouvoir passer 'utilisateur'
        ]);
    }
}
