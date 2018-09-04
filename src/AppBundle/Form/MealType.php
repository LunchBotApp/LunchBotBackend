<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MealType
 *
 * @package AppBundle\Form
 */
class MealType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Name', 'required' => true])
            ->add('addition', null, ['label' => 'Additional description', 'required' => false])
            ->add('price', null, ['label' => 'Price', 'required' => true])
            ->add('restaurant', null, ['label' => 'Restaurant', 'required' => true])
            ->add('date', DateType::class, ['label' => 'Date', 'required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['id' => 'datetimepicker'],])
            ->add('place', null, ['label' => 'Place', 'required' => false])
            ->add('categories', null, ['label' => 'Categories', 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }


}