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

namespace AppBundle\Entity;

use AppBundle\Entity\Suggestion as Suggestion;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A suggestion for a single user
 *
 * @ORM\Table(name="solo_suggestions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SuggestionRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class SoloSuggestion extends Suggestion
{
    /**
     *
     * @var User The user of a solo suggestion.
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Groups({"suggestion"})
     */
    private $user;


    /**
     *
     * @var array The meals that were suggested.
     *
     * @ORM\ManyToMany(targetEntity="Meal")
     * @ORM\JoinColumn(name="meal_id", referencedColumnName="id")
     * @Serializer\Groups({"suggestion"})
     */
    private $suggestions;

    /**
     * Returns the user of a solo suggestion.
     *
     * @return User The user of a solo suggestion.
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user of a solo suggestion.
     *
     * @param User $user The user of a solo suggestion.
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Returns the meals that were suggested.
     *
     * @return array The meals that were suggested.
     */
    public function getSuggestions()
    {
        return $this->suggestions;
    }

    /**
     * Set the meals that were suggested.
     *
     * @param array $suggestions The meals that were suggested.
     */
    public function setSuggestions($suggestions)
    {
        $this->suggestions = $suggestions;
    }
}
