<?php

/**
 * User email type.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserEmailType.
 */
class UserEmailType extends AbstractType
{
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder the form builder
     * @param array                $options the options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'attr' => [
                    'maxlength' => 64,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'message.field_blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 64,
                        'maxMessage' => 'message.invalid_length',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'message.password_mismatch',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'mapped' => true,
                'first_options' => ['label' => 'label.password'],
                'second_options' => ['label' => 'label.repeat_password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'message.blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'message.password_too_short',
                        // The maximum length allowed by Symfony for security reasons
                        'max' => 64,
                    ]),
                ],
            ]);
    }
}
