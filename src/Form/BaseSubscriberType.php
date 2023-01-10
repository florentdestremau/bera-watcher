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
                'label'    => 'Jours de la semaine',
                'multiple' => true,
                'expanded' => true,
                'class'    => Weekday::class,
                'choice_label' => fn (Weekday $weekday) => $weekday->value,
            ])
            ->add('mountains', EnumType::class, [
                'class'    => Mountain::class,
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'label'    => 'Massifs',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscriber::class,
        ]);
    }
}
