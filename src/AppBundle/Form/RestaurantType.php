<?php


namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RestaurantType
 *
 * @package AppBundle\Form
 */
class RestaurantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Name', 'required' => true])
            ->add('address', AddressType::class, ['label' => 'Address', 'required' => true, 'data' => $options['address']])
            ->add('website', null, ['label' => 'Website', 'required' => false])
            ->add('email', null, ['label' => 'Email', 'required' => false])
            ->add('phone', null, ['label' => 'Phone', 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'address' => null,
        ]);
    }
}
