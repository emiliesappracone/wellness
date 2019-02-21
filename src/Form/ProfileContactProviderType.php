<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Locality;
use App\Entity\Provider;
use App\Entity\ZipCode;
use App\Form\PictureFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileContactProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('contactEmail', TextType::class)
            ->add('phone', TextType::class)
            ->add('website', TextType::class)
            ->add('vat', TextType::class)
            ->add('addressNum', TextType::class)
            ->add('addressStreet', TextType::class)
//            ->add('picture', PictureType::class, array('label' => 'Image', 'required' => false))
//            ->add('logo', PictureType::class, array('label' => 'Logo', 'required' => false))
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
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
        ]);
    }
}
