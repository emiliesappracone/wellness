<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Locality;
use App\Entity\Provider;
use App\Entity\Surfer;
use App\Entity\ZipCode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('isSubToNewsletter', CheckboxType::class, ['label' => 'Have subscribe to newsletter ?', 'required' => false, 'attr' => [
                'class' => 'inputHide'
            ]])
//            ->add('username', TextType::class)
//            ->add('password', PasswordType::class, ['required' => false])
            ->add('addressNum', TextType::class)
            ->add('addressStreet', TextType::class)
            ->add('isBanned', CheckboxType::class, ['required' => false,'attr' => [
                'class' => 'inputHide'
            ]])
            ->add('basicEmail', TextType::class)
            ->add('isRegistered', CheckboxType::class, ['required' => false, 'attr' => [
                'class' => 'inputHide'
            ]])
//            ->add('registeredAt', DateTimeType::class)
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
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Surfer::class,
            'translation_domain' => 'forms'
        ]);
    }
}
