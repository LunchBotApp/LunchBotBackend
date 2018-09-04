<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * Rating Class
 *
 * @ORM\Table(name="ratings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RatingRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Rating
{

    /**
     * @var int The internal id of a rating.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"rating"})
     */
    private $id;

    /**
     * @var User The user of a rating.
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Serializer\Groups({"rating"})
     */
    private $user;

    /**
     * @var Meal The meal of a rating.
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Meal")
     * @ORM\JoinColumn(name="meal_id", referencedColumnName="id")
     * @Serializer\Groups({"rating"})
     */
    private $meal;

    /**
     * @var DateTime The date and time a rating was made.
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @var int The value of a rating.
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"rating"})
     */
    private $value;

    /**
     * @var Suggestion The suggestion of a rating
     *
     * @ORM\ManyToOne(targetEntity="Suggestion", inversedBy="ratings")
     * @ORM\JoinColumn(name="suggestion_id", referencedColumnName="id")
     */
    private $suggestion;


    /**
     * Returns the internal id of a rating.
     * @return int The internal id of a rating
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the internal id of a rating.
     * @param int $id The internal id of a rating
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the user of a rating.
     * @return User The user of a rating
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user of a rating.
     * @param User $user The user of a rating
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Returns the meal of a rating.
     * @return Meal The meal of a rating
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * Sets the meal of a rating.
     * @param Meal $meal The meal of a rating
     */
    public function setMeal($meal)
    {
        $this->meal = $meal;
    }

    /**
     * Returns the date and time of a rating.
     * @return DateTime The date and time of a rating
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Sets the date and time of a rating.
     * @param DateTime $timestamp The date and time of a rating
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Returns the value of a rating.
     * @return int The value of a rating
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of a rating.
     * @param int $value The value of a rating
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the suggestion this ratings belongs to.
     * @return Suggestion The suggestion this ratings belongs to
     */
    public function getSuggestion()
    {
        return $this->suggestion;
    }

    /**
     * Sets the suggestion this ratings belongs to.
     * @param Suggestion $suggestion The suggestion this ratings belongs to
     */
    public function setSuggestion($suggestion)
    {
        $this->suggestion = $suggestion;
    }
}
