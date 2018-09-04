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