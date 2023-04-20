<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'label' => 'Arrivée',
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Départ',
                'widget' => 'single_text'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 1,
                        'max' => 50
                    ])
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 1,
                        'max' => 50
                    ])
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => "Téléphone"
            ])
            ->add('email', EmailType::class, [
                'label' => "Votre email",
                'constraints' => [
                    new NotBlank([
                        'message' =>'Ce champ ne peut etre vide : {{ value }}'
                    ]),
                    new Length([
                        'min' => 4,
                        'max' =>180,
                        'minMessage' =>'Votre email doit comporter au minimum {{ limit }} caractères.(email : {{ value }})',
                        'maxMessage' =>'Votre email doit comporter au maximum {{ limit }} caractères.(email : {{ value }})',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Submit",
                'validate' => false,
                'attr' => [
                    'class' => "d-block mx-auto my-3 btn btn-success col-3"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
