<?php

namespace App\Form;

use App\Entity\ProductCategory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class CategoryType extends AbstractType
{

    private $manager;

    /**
     * CategoryType constructor.
     * @param $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['categories'] = $this->manager->getRepository(ProductCategory::class)->findAll();
    }

    public function getParent()
    {
        return TextType::class;
    }

}