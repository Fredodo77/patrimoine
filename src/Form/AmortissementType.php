<?php

namespace App\Form;

use App\Entity\Amortissement;
use App\Entity\Credit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmortissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num_echeance')
            ->add('date_echeance', null, [
                'widget' => 'single_text',
            ])
            ->add('montant_amortissement')
            ->add('montant_interet')
            ->add('num_credit', EntityType::class, [
                'class' => Credit::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Amortissement::class,
        ]);
    }
}
