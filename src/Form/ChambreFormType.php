<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChambreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description_courte')
            ->add('description_longue')
            ->add('photo')
            ->add('prix_journalier')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('commande')
            ->add('slider')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
