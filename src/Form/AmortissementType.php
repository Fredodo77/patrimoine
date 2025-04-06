<?php

namespace App\Form;

use App\Entity\Amortissement;
use App\Entity\Credit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmortissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numEcheance')
            ->add('dateEcheance', DateType::class, [
                'html5' => false,
                'format' => 'dd/MM/yyyy',
            ])
            ->add('montantAmortissement')
            ->add('montantInteret')
            ->add('numCredit', EntityType::class, [
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
