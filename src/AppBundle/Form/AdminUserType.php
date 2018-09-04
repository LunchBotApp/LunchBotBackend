<?php


namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AdminUserType
 *
 * @package AppBundle\Form
 */
class AdminUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Name', 'required' => true])
            ->add('username', null, ['label' => 'Username', 'required' => true])
            ->add('email', null, ['label' => 'Email', 'required' => true])
            ->add('enabled', null, ['label' => 'Enabled', 'required' => true])
            ->add('roles', ChoiceType::class, ['multiple' => true,
                                               'choices'  => [
                                                   'User'  => 'ROLE_USER',
                                                   'Admin' => 'ROLE_ADMIN'
                                               ], 'label' => 'Roles', 'required' => true])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    /**
     * @return null|string
     */
    public function getParent()

    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()

    {
        return 'app_user_registration';
    }
}
