<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Fabricant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label'=>'Nom du produit : '])
            ->add('prix', NumberType::class, ['label'=>'Prix : '])
            ->add('quantite', NumberType::class, ['label'=>'Quantité : '])
            ->add('rupture', CheckboxType::class, ['label'=>'Rupture ? : ', 'required'=>false])
            ->add('lienImage', FileType::class, ['label'=>'Illustration : ', 'required'=>false, 'data_class'=>null, 'empty_data'=>'nothing'])
            ->add('reference', ReferenceType::class, ['label'=>'Reference du produit : ', 'required'=>false])
            ->add('distributeur', CollectionType::class, [
                'entry_type' => DistributeurType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('fabricants', EntityType::class, [
                'class' => Fabricant::class,// ATTENTION : Entity et pas FormType ici !!!
                'choice_label' => 'nom',
                'label' => 'Selection des fabricants',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
