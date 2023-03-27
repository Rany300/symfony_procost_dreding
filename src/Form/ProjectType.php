<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;




class ProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 2555,
                    ]),
                ],
            ])
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'Le coût doit être un nombre entier',
                    ]),
                ],
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('deliveredAt', CheckboxType::class, [
                'required' => false,
                'label' => 'Delivered',
                'mapped' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $project = $event->getData();

                if ($form->get('deliveredAt')->getData()) {
                    $project->setDeliveredAt(new \DateTimeImmutable());
                } else {
                    $project->setDeliveredAt(null);
                }
            })
            ->add('save', SubmitType::class);
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver$resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Project::class,
        ]);
    }

}
