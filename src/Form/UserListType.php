<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('users', CollectionType::class,[
                'entry_type' => UserType::class,
                'entry_options' => ['label' => false],
                'data' => $builder->getOption('userList'),
            ])
            ->add('delete', SubmitType::class, [])
            /*->add('block', SubmitType::class, [])
            ->add('unblock', SubmitType::class, [])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('userList');
    }
}
