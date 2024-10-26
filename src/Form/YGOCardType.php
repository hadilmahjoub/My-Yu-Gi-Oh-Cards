<?php

namespace App\Form;

use App\Entity\Pack;
use App\Entity\Showcase;
use App\Entity\YGOCard;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => YGOCard::class,
        ]);
    }
}
