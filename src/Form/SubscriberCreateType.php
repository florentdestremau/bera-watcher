<?php

namespace App\Form;

use App\Entity\Subscriber;
use App\Model\Mountain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Adresse email'])
            ->add('mountains', EnumType::class, ['class'    => Mountain::class,
                                                 'multiple' => true,
                                                 'expanded' => true,
                                                 'required' => true,
                                                 'label'    => 'Massifs',
            ])
            ->add('submit', SubmitType::class, ['label' => 'S\'abonner', 'attr' => ['class' => 'btn btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscriber::class,
        ]);
    }
}
