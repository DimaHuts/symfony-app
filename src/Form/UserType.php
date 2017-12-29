<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => "title.email",
                    'class' => 'form-control'
                ]
            ])
            ->add('username', TextType::class, [
                'required' => true,
                    'attr' => [
                        'placeholder' => 'title.username',
                        'class' => 'form-control'
                    ]
                ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'title.password',
                        'class' => 'form-control'
                    ],
                ],
                'second_options' => [
                    'label' => false,
                        'attr' => [
                            'placeholder' => 'title.repeat_password',
                            'class' => 'form-control'
                        ],
                    ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}