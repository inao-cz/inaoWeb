<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email', TextType::class, [
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Password again'],
                'invalid_message' => 'Hej ty hesla musi byt stejny, rozumime si?',
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 256,
                        'minMessage' => 'Mas moc kratky heslo.',
                        'maxMessage' => 'Tyvole, mas dobrou pamet ne? Vazne, staci jen 256 znaku, i tak je to bezpecny az moc vole'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn waves-effect waves']
            ])
        ;
    }
}