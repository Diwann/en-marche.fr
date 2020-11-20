<?php

namespace App\Form;

use App\Event\Filter\ListFilterObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommitteeMemberFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ageMin', IntegerType::class, ['required' => false])
            ->add('ageMax', IntegerType::class, ['required' => false])
            ->add('firstName', TextType::class, ['required' => false])
            ->add('city', TextType::class, ['required' => false])
            ->add('registeredSince', DatePickerType::class, ['required' => false])
            ->add('registeredUntil', DatePickerType::class, ['required' => false])
            ->add('joinedSince', DatePickerType::class, ['required' => false])
            ->add('joinedUntil', DatePickerType::class, ['required' => false])
            ->add('sort', HiddenType::class, ['required' => false])
            ->add('order', HiddenType::class, ['required' => false])
            ->add('subscribed', ChoiceType::class, ['required' => false, 'placeholder' => 'common.all', 'choices' => [
                'common.adherent.subscribed' => true,
                'common.adherent.unsubscribed' => false,
            ]])
        ;

        if (true === $options['is_supervisor']) {
            $builder->add('votersOnly', CheckboxType::class, ['required' => false]);
        }
    }

    public function getBlockPrefix()
    {
        return 'filter';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => ListFilterObject::class,
                'is_supervisor' => false,
            ])
            ->setAllowedTypes('is_supervisor', ['bool'])
        ;
    }
}
