<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom",
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => "Prénom",
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('email', TextType::class, [
                'label' => "Email",
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('sujet', TextType::class, [
                'label' => "Sujet",
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => "Categorie",
                'choices'  => [
                    'Chambre' => 'chambre',
                    'Restaurant' => 'femme',
                    'Spa' => 'spa',
                    'Sujet général' => 'sujet_general'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => "Message",
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 4,
                        'max' => 600
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-3 btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
