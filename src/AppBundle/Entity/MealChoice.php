<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MealChoice class.
 * Contains the association which user chose which meal.
 *
 * @ORM\Table(name="meal_choices")
 * @ORM\Entity()
 */
class MealChoice
{

    /**
     * @var int the internal id of the meal choice
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @var User The user that chose the meal.
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *
     * @var Meal The meal that the user choose.
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Meal")
     * @ORM\JoinColumn(name="meal_id", referencedColumnName="id")
     */
    private $meal;

    /**
     * @var Suggestion The suggestion of a rating
     *
     * @ORM\ManyToOne(targetEntity="Suggestion", inversedBy="mealChoices")
     * @ORM\JoinColumn(name="suggestion_id", referencedColumnName="id")
     */
    private $suggestion;

    /**
     * Returns the internal id of a meal choice.
     *
     * @return int The internal id of a meal choice
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the internal id of a meal choice.
     *
     * @param int $id The internal id of a meal choice
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the user of this meal choice.
     *
     * @return User The user of this meal choice
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user of this meal choice.
     *
     * @param User $user The user of this meal choice
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Returns the meal of this meal choice.
     *
     * @return Meal The meal of this meal choice
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * Sets the meal of this meal choice.
     *
     * @param Meal $meal The meal of this meal choice
     */
    public function setMeal($meal)
    {
        $this->meal = $meal;
    }

    /**
     * @return Suggestion
     */
    public function getSuggestion(): Suggestion
    {
        return $this->suggestion;
    }

    /**
     * @param Suggestion $suggestion
     * @return MealChoice
     */
    public function setSuggestion(Suggestion $suggestion): MealChoice
    {
        $this->suggestion = $suggestion;

        return $this;
    }
}
