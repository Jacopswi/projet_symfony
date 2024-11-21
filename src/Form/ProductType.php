<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Enum\ProductStatus;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('stock')
            ->add('status', EnumType::class, ['class' => ProductStatus::class])
            ->add('image', ImageType::class, [
                'label' => 'Ajouter une image',
            ])
            ->add('Category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
                'multiple' => true,
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
