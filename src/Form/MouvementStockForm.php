<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\MouvementStock;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MouvementStockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity')
            ->add('type')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('commentaire')
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'nom',
            ])
            ->add('emplacement', EntityType::class, [
                'class' => Emplacement::class,
                'choice_label' => 'nom',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MouvementStock::class,
        ]);
    }
}
