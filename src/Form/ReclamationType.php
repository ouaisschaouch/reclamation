<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre nom',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre nom']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre prenom',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre prenom']
            ])
            ->add('sujet', TextType::class, [
                'label' => 'Sujet',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Sujet']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Description', 'rows' => '5']
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->add('img',FileType::class,
                ['attr'=>['class'=>'form-control'],
                    'label'=>'Photo de profil',
                    'mapped'=>false,
                    'required'=>false,
                    'constraints'=>
                        [
                            new File([
                                'mimeTypes'=>[
                                    'image/jpg',
                                    'image/png',
                                    'image/jpeg',
                                    'image/gif',
                                ],
                                'mimeTypesMessage'=>'Please upload a valid image'
                            ])
                        ]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //'data_class' => Reclamation::class,
        ]);
    }
}
