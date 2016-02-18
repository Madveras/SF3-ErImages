<?php

namespace ErImageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BannerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file1',  FileType::class, array('label' => 'Imagen 1','mapped' => false,
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Image()
                  )
                ))
            ->add('cottage',  TextType::class, array('label' => 'Nombre de la casa'))
            ->add('text',  TextareaType::class, array('label' => 'Texto'))
            ->add('file',  HiddenType::class)
            ->add('file2',  FileType::class, array('label' => 'Imagen 2','mapped' => false,
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Image()
                  )))
            ->add('file3',  FileType::class, array('label' => 'Imagen 3','mapped' => false,
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Image()
                  )))
            ->add('view', SubmitType::class , array('label' => 'Preview'))
            ->add('save', SubmitType::class , array('label' => 'Save and Download'));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ErImageBundle\Entity\Banner',
        ));
    }
}

?>
