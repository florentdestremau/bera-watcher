<?php

namespace App\Form;

use App\Entity\Subscriber;
use App\Model\Mountain;
use App\Model\Weekday;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseSubscriberType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Adresse email'])
            ->add('weekdays', EnumType::class, [
                'class'        => Weekday::class,
                'label'        => 'Jours de la semaine',
                'multiple'     => true,
                'expanded'     => true,
                'choice_label' => fn (Weekday $weekday) => $weekday->value,
                'attr'         => ['class' => 'hstack gap-2'],
            ])
            ->add('mountains', EnumType::class, [
                'class'        => Mountain::class,
                'label'        => 'Massifs',
                'required'     => true,
                'multiple'     => true,
                'expanded'     => true,
                'choice_label' => fn (Mountain $mountain) => $mountain->value,
                'attr'         => ['class' => 'columns'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscriber::class,
        ]);
    }
}
