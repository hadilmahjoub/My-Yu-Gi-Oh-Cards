<?php

namespace App\Form;

use App\Entity\Pack;
use App\Entity\Showcase;
use App\Entity\YGOCard;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class YGOCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('pack', EntityType::class, [
                'label' => 'Pack',
                'disabled' => true,
                'class' => Pack::class,
                //'choice_label' => 'id',
            ])
           /* ->add('showcases', EntityType::class, [
                'class' => Showcase::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])*/
        ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['rows' => 3],
            ])
            ->add('attribute', TextType::class, [
                'label' => 'Attribute'
            ])
            ->add('type', TextType::class, [
                'label' => 'Type'
            ])
            ->add('race', TextType::class, [
                'label' => 'Race'
            ])
            ->add('level', NumberType::class, [
                'label' => 'Level'
            ])
            ->add('imageName', TextType::class,  [
                'label' => 'Image Name',
                'disabled' => true
            ])
            /*
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image File',
                'required' => false
            ])*/
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPEG/PNG file)',
                'required' => false,
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => YGOCard::class,
        ]);
    }
}
