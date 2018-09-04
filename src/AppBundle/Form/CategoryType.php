<?php


namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MealType
 *
 * @package AppBundle\Form
 */
class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Name', 'required' => true])
            ->add('searchterms', CollectionType::class, [
                'entry_type' => TextType::class,
                'label'      => 'Search Terms',
                'required'   => true,
                'allow_add'  => true])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }


}