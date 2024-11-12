<?php

namespace App\Form;

use App\Entity\Showcase;
use App\Entity\YGOCard;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Entity\User;
use App\Repository\YGOCardRepository;

class ShowcaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dump($options);
        // Get the current [object] from 'data' option passed to the form
        $showcase = $options['data'] ?? null;
        // get the [galerie]'s creator
        $user = $showcase->getCreator();
        
        // Récupère le Pack de l'utilisateur
        $pack = $user ? $user->getPack() : null;
        
        $builder
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Published',
                'required' => false
            ])
            ->add('creator', EntityType::class, [
                'label' => 'Creator',
                'disabled' => true,
                'class' => User::class,
            ])
            ->add('ygoCards', null, [
                // adjust the loading of possible [objects] to those of the current member's [inventory]
                // the use helps pass the member to the lambda
                'class' => YGOCard::class,
                'label' => 'YGO Cards',
                'multiple' => true,
                'expanded' => true, // true
                'by_reference' => false,
                'choice_label' => 'name',
                
                'query_builder' => function (YGOCardRepository $repo) use ($user) {
                return $repo->createQueryBuilder('c')
                    ->leftJoin('c.pack', 'p')
                    ->leftJoin('p.user', 'u')
                    ->andWhere('u.id = :userId')
                    ->setParameter('userId', $user->getId())
                    ;
                }
                
                /*
                'query_builder' => function (YGOCardRepository $repo) use ($pack) {
                // Filtre pour n'afficher que les YGOCard du Pack de l'utilisateur
                return $repo->createQueryBuilder('c')
                    ->where('c.pack = :pack')
                    ->setParameter('pack', $pack);
                },
                
                */
                
                ])
             ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Showcase::class,
        ]);
    }
}
