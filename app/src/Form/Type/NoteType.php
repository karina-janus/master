<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                'label' => 'label.title',
                'required' => true,
            ])
            ->add('content',
                TextareaType::class,
                [
                'label' => 'label.content',
                'required' => true,
            ])
            ->add('category',
                EntityType::class,
                [
                'label' => 'label.category',
                'class' => Category::class,
                'choice_label' => function ($category): string {
                    return $category->getTitle();
                },
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'note';
    }
}
