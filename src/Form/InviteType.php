<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class InviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class)
            ->add('groups', ChoiceType::class, [
                'multiple' => true,
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Upload images' => 'ROLE_IMAGES',
                    'Generate invites' => 'ROLE_IMAGES',
                    'Create links' => 'ROLE_LINKS',
                    'All services' => 'ROLE_ALL_SERVICES',
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn waves-effect waves']
            ]);
    }
}