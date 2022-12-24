<?php

namespace App\Form;

use App\Model\Mountain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LookupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultDate = new \DateTime();

        if ($defaultDate->format('H') < 15) {
            $defaultDate->modify('-1 day');
        }

        $builder
            ->add('mountains', EnumType::class, [
                'class'        => Mountain::class,
                'choice_label' => 'value',
                'label'        => 'Massif',
            ])
            ->add('date', DateType::class, [
                'data'  => $defaultDate,
                'label' => 'Date',
                'years' => range(2016, date('Y')),
            ])
            ->add('lookup', SubmitType::class, [
                'attr'  => ['class' => 'btn btn-primary'],
                'label' => 'Consulter le BERA',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => true]);
    }
}
