<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Locality;
use App\Entity\Picture;
use App\Entity\Surfer;
use App\Entity\ZipCode;
use App\Form\PictureFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileContactSurferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('addressNum', TextType::class)
            ->add('addressStreet', TextType::class)
//            ->add('isSubToNewsletter', CheckboxType::class, array(
//                'required' => false,
//            ))
//            ->add('picture', PictureType::class, array('label' => 'Image', 'required' => false))
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Surfer::class,
            'translation_domain' => 'forms',
        ]);
    }
}
