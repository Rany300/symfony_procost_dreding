<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;



class WorkUnitType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $projectChoices = $options['project_choices'];

        $builder
            ->add('duration', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'Le nombre d\'heures doit être un nombre entier',
                    ]),
                ],
            ])
            ->add('project', ChoiceType::class, [
                'choices' =>  $projectChoices,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('submit', SubmitType::class);

    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\WorkUnit::class,
            'project_choices' => [],
        ]);
    }


}
