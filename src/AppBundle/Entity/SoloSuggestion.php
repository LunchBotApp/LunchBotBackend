<?php

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
