<?php

// src/Form/TaskType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Task;

class TaskType extends AbstractType
{
    /**
     * Créez le formulaire de tâche avec les champs titre et contenu.
     * 
     * @param FormBuilderInterface $builder Le constructeur de formulaires
     * @param array $options Les options du formulaire (requises par l'interface, utilisées ici pour des raisons de compatibilité)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
            // Ajoutez d'autres champs de formulaire si nécessaire
        ;
    }
}
