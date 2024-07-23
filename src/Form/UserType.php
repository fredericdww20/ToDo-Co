<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * Créez le formulaire utilisateur avec les champs nom d'utilisateur, mot de passe, e-mail et rôles.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaires
     * @param array $options Les options du formulaire (requises par l'interface, utilisées ici pour des raisons de compatibilité)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('username', TextType::class, [
            'label' => "Nom d'utilisateur",
            'attr' => ['class' => 'form-control']
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les deux mots de passe doivent correspondre.',
            'required' => true,
            'first_options'  => [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control']
            ],
            'second_options' => [
                'label' => 'Tapez le mot de passe à nouveau',
                'attr' => ['class' => 'form-control']
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => 'Adresse email',
            'attr' => ['class' => 'form-control']
        ])
        ->add('roles', ChoiceType::class, [
            'label' => 'Rôle',
            'multiple' => true,
            'expanded' => true,
            'choices' => [
                'Utilisateur' => "ROLE_USER",
                'Administrateur' => "ROLE_ADMIN"
            ],
            'choice_attr' => function($choice, $key, $value) {
                return ['class' => 'form-check-input'];
            },
            'label_attr' => ['class' => 'form-check-label'],
            'attr' => ['class' => 'form-group mt-3']
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
