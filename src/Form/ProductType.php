<?php

namespace App\Form;


use App\Entity\Product;
use App\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ProductType
 *
 * @package AppBundle\Form
 * @author Dmitry Huts
 */
class ProductType extends AbstractType
{

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('name', TextType::class, [
                  'required' => true,
                  'attr' => [
                      'class' => 'form-control',
                      'placeholder' => $this->translator->trans('table.product.name', [])
                  ]
                 ])
           ->add('price', NumberType::class, [
                  'required' => true,
                  'attr' => [
                      'class' => 'form-control',
                      'placeholder' => $this->translator->trans('table.product.price')
                  ]
                 ])
           ->add('description', TextareaType::class, [
                  'required' => true,
                  'label' => $this->translator->trans('table.product.description'),
                  'attr' => [
                      'class' => 'form-control',
                      'placeholder' => $this->translator->trans('table.product.description')
                  ]
                 ])
            ->add('uploadedFiles', FileType::class, [
                  'label' => $this->translator->trans('product.image'),
                  'required' => false,
                  'multiple' => true
                 ])
           ->add('category', EntityType::class, [
                  'class' => ProductCategory::class,
                  'choice_label' => 'categoryName',
                  'multiple'     => true,
                  'label' => false,
                  'required' => false,
                  'attr' => [
                      'class' => 'form-control'
                ]

               ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

}