<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RegistrationType extends AbstractType
{
    private $userLoggedIn;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->userLoggedIn = $authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('username')
        ;

        // not allowed to update email in EDIT mode
        if (!$this->userLoggedIn) {
            $builder->add('email');
        }

        // password can be updated separately through it's own change password form with current & new password fields
        if (!$this->userLoggedIn) {
            $builder->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }

}