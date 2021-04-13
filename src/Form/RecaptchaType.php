<?php
namespace App\Form;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RecaptchaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('captcha', EWZRecaptchaType::class, [
            'attr' => [
                'options' => [
                    'theme' => 'dark',
                    'type' => 'image',
                    'size' => 'normal'
                ]
            ],
            'mapped' => false,
            'constraints'=>[
                new IsTrue()
            ]
        ])->add('submit', SubmitType::class);
    }
}