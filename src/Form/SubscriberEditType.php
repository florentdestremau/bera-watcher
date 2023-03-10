<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SubscriberEditType extends BaseSubscriberType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'submit', SubmitType::class,
            [
                'label' => 'Enregistrer',
                'attr'  => ['class' => 'btn btn-primary'],
            ]
        );


    }
}
