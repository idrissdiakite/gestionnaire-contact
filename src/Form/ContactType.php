<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                // null permet de ne pas précser de type de champs pour pouvoir
                // envoyer des options à l'input en troisième argument
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide !',
                    ]),
                    new Regex([
                        'pattern' => '/[a-zA-Z]/',
                        'message' => 'Merci d\'utiliser uniquement des lettres',
                    ]),
                ]
            ])
            ->add('lastname', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide !',
                    ])
                ]
            ])
            ->add('email', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide !',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/',
                        'message' => 'L\'adresse mail n\'est pas valide',
                    ]),
                ]
            ])
            ->add('phoneNumber', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide !',
                    ]),
                    new Regex([
                        'pattern' => '/^0[1-9][0-9]{8}$/',
                        'message' => 'Le numéro doit commencer par 0, suivit d\'un autre chiffre et comporter en tout 10 chiffres.',
                    ]),
                ]
            ])
            ->add('city', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs ne peut être vide !',
                    ]),
                    new Regex([
                        'pattern' => '/[a-zA-Z]/',
                        'message' => 'Merci d\'utiliser uniquement des lettres',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'La ville n\'existe pas'
                    ]),
                ]
            ])
            ->add('category')
            ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}