<?php

namespace App\Form;

use App\Entity\Internship;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Tests\Fixtures\TraversableArrayObject;

class InternshipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('availableFrom', DateType::class, [
                'widget'=>'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('availableTo', DateType::class, [
                'widget'=>'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('start', DateType::class, [
                'widget'=>'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('end', DateType::class, [
                'widget'=>'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('description', TextareaType::class)
            ->add('moreInfo', TextType::class)
            ->add('name', TextType::class)
            ->add('price', TextType::class)
            ->add('submit', SubmitType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Internship::class,
        ]);
    }
}
