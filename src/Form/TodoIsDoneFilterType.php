<?php

namespace App\Form;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoIsDoneFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stillTodo', CheckboxType::class,[
                'label' => 'Afficher uniquement les tâches qui restent à faire',
                'mapped' => false,
                'required' => false,
            ])
            ->add('searchTerms', null, [
                'mapped'=> false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Rechercher ...",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}
