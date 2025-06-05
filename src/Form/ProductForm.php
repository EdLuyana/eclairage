<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Location;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('name')
->add('color')
->add('price')
->add('reference')
->add('category', EntityType::class, [
'class' => Category::class,
'choice_label' => 'name', // Mieux que 'id'
])
->add('locations', EntityType::class, [
'class' => Location::class,
'choice_label' => 'name', // Mieux que 'id'
'multiple' => true,
'expanded' => false, // ou true si tu veux des cases à cocher
])
->add('brand', EntityType::class, [
'class' => Brand::class,
'choice_label' => 'name', // Mieux que 'id'
])
;
}

public function configureOptions(OptionsResolver $resolver): void
{
$resolver->setDefaults([
'data_class' => Product::class,
]);
}
}
