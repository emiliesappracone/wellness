<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Locality;
use App\Entity\Provider;
use App\Entity\ZipCode;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('phone', TextType::class)
            ->add('vat', TextType::class)
            ->add('website', TextType::class)
            ->add('description', TextareaType::class)
            ->add('password', PasswordType::class, ['required' => false])
            ->add('addressNum', TextType::class)
            ->add('addressStreet', TextType::class)
            ->add('basicEmail', TextType::class)
            ->add('zipCode', EntityType::class, [
                'class' => ZipCode::class,
                'required' => false,
            ])
            ->add('locality', EntityType::class, [
                'class' => Locality::class,
                'required' => false,
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'required' => false,
            ])
//            ->add('services', EntityType::class, [
//                'class' => Services::class,
//                'required' => false,
//                'multiple' => true
//            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
            'translation_domain' => 'forms'
        ]);
    }
}
