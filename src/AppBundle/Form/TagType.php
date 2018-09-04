<?php


namespace AppBundle\Form;

use AppBundle\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MealType
 *
 * @package AppBundle\Form
 */
class TagType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', null, ['label' => 'Value (X-path)', 'required' => true])
            ->add('print', null, ['label' => 'Print', 'required' => false])
            ->add('type', ChoiceType::class, ['label' => 'Tag Type', 'required' => true, 'choices' => [
                'Description' => Tag::TYPE_DESCR,
                'Price'       => Tag::TYPE_PRICE,
                'Date'        => Tag::TYPE_DATE]])
            ->add('format', null, ['label' => 'Format (date Y-m-d)', 'required' => false])
            ->add('parent', null, ['label' => 'Parent Tag', 'required' => false])
            ->add('children', null, ['label' => 'Children', 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }


}