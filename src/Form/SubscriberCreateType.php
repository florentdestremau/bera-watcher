<?php

namespace App\Form;

use App\Entity\Subscriber;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberCreateType extends BaseSubscriberType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'submit', SubmitType::class,
            [
                'label' => 'S\'abonner',
                'attr'  => ['class' => 'btn btn-primary'],
            ]
        );
    }
}
