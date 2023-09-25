<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content', CKEditorType::class)
            // ->add('user', EntityType::class, [
            //     "class"=>User::class,
            //     "choice_label"=>"userName",
            //     "multiple"=>true, //optionnel pour ManyToMany
            //     "expanded"=>true //optionnel pour ManyToMany
            // ] )
            ->add('image', FileType::class, [
                'label' => 'image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '40000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
            ->add('category', EntityType::class, [
                "class"=>Category::class,
                "choice_label"=>"name",
                "multiple"=>true, //optionnel pour ManyToMany
                "expanded"=>true //optionnel pour ManyToMany
            ] )
            ->add('Envoyer', SubmitType::class)
            // ->add('user', EntityType::class, [
            //     // looks for choices from this entity
            //     'class' => User::class,
            
            //     // uses the User.username property as the visible option string
            //     'choice_label' => 'username',
            
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     // 'expanded' => true,
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
