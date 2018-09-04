<?php


namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AddressType
 *
 * @package AppBundle\Form
 */
class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', null, ['label' => 'Number', 'required' => true])
            ->add('street', null, ['label' => 'Street', 'required' => true])
            ->add('code', null, ['label' => 'Postal code', 'required' => true])
            ->add('city', null, ['label' => 'City', 'required' => true])
            ->add('country', null, ['label' => 'Country', 'required' => true]);
    }
}
