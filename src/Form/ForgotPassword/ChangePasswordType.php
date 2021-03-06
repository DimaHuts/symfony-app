<?php

namespace App\Form\ForgotPassword;


use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('email')
            ->remove('username');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => User::class,
           'validation_groups' => ['ChangePassword']
        ]);
    }

    public function getParent()
    {
        return UserType::class;
    }

}