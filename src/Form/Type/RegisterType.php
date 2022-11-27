<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'attr' => [
                    'placeholder' => 'Your username ...'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please fill the username field.'
                    ]),
                    new Assert\Length([
                        'min' => 4,
                        'minMessage' => 'The username must contain at least 4 characters.'
                    ])
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Your email ...'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please fill the email field.'
                    ]),
                    new Assert\Email([
                        'message' => '"{{ value }}" is not a valid email'
                    ])
                ]
            ])
            ->add('password', RepeatedType::class ,[
                'type' => PasswordType::class,
                'invalid_message' => 'The passwords must match.',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please choose a password.'
                    ]),
                    new Assert\Length([
                        'min' => 4,
                        'minMessage' => 'The password must contain at least 4 characters.'
                    ])
                ],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['placeholder' => 'Enter your password ...']
                ],
                'second_options' => [
                    'label' => 'Repeat password',
                    'attr' => ['placeholder' => 'Confirm your password ...']
                ]
            ])
            ->add('register', SubmitType::class)
        ;
    }
}