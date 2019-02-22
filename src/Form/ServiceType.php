<?php
/**
 * Created by PhpStorm.
 * User: mvstudio
 * Date: 11/10/18
 * Time: 09:36
 */

namespace App\Form;


use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class, ['required' => false])
            ->add('is_highlighted', CheckboxType::class,  ['label' => 'Is highlighted ?', 'required' => false, 'attr' => [
                'class' => 'inputHide'
            ]])
            ->add('is_valid', CheckboxType::class,  ['label' => 'Is valid ?', 'required' => false, 'attr' => [
                'class' => 'inputHide'
            ]])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
            'translation_domain' => 'forms'
        ]);
    }
}