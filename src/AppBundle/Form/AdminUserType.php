<?php
/**
 * This file is part of LunchBotBackend.
 *
 * Copyright (c) 2018 Benoît Frisch, Haris Dzogovic, Philipp Machauer, Pierre Bonert, Xiang Rong Lin
 *
 * LunchBotBackend is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 *
 * LunchBotBackend is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty ofMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with LunchBotBackend If not, see <http://www.gnu.org/licenses/>.
 *
 */

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
