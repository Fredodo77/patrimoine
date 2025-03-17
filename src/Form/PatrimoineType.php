<?php

namespace App\Form;

use App\Entity\Patrimoine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatrimoineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('details')
            ->add('dateAcquisition', null, [
                'widget' => 'single_text',
            ])
            ->add('typeBien')
            ->add('montant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patrimoine::class,
        ]);
    }
}
