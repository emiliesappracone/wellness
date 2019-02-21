<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Locality;
use App\Entity\Provider;
use App\Entity\Service;
use App\Entity\ZipCode;
use App\Form\PictureFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('contactEmail', EmailType::class)
            ->add('phone', TextType::class)
            ->add('vat', TextType::class)
            ->add('website', TextType::class)
            ->add('addressNum', TextType::class)
            ->add('addressStreet', TextType::class)
            ->add('isBanned', CheckboxType::class, ['required' => false])
            ->add('basicEmail', TextType::class)
            ->add('isRegistered', CheckboxType::class, ['required' => false])
//            ->add('picture', PictureType::class, array('label' => 'Photo', 'required' => false))
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
            ->add('services', EntityType::class, [
                'class' => Service::class,
//                'required' => false,
                'multiple' => true
            ])
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
