<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Meal class
 *
 * @ORM\Table(name="meals")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MealRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Meal
{
    /**
     * @var int The internal id of a meal.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"meal"})
     */
    private $id;

    /**
     * @var string The name of a meal.
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     * @Serializer\Groups({"meal"})
     */
    private $name;

    /**
     * @var string Additional information of a meal.
     *
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"meal"})
     */
    private $addition;

    /**
     * @var string For canteens, which line it is offered at
     *
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"meal"})
     */
    private $place;

    /**
     * @var DateTime The date which this meal is available at.
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     * @Serializer\Groups({"meal"})
     */
    private $date;

    /**
     * @var array The categories of this meal.
     * Many Meals have many categories
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="meals")
     * @ORM\JoinTable(name="meals_categories")
     */
    private $categories;

    /**
     * @var float The price of  this meal.
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="float")
     * @Serializer\Groups({"meal"})
     */
    private $price;

    /**
     * @var Restaurant The restaurant this meal is sold at.
     *
     * @Assert\NotBlank()
     * Many Meals have One Restaurant.
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="meals")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     * @Serializer\Groups({"meal"})
     */
    private $restaurant;

    public function __toString()
    {
        return $this->restaurant->getName() . '_' . $this->getName();
    }

    /**
     * Returns the name of a meal.
     *
     * @return string The name of a meal
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of a meal.
     *
     * @param string $name The name of a meal
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Adds a category to this meal.
     *
     * @param Category $category The category which will be added
     */
    public function addCategory($category)
    {
        if (is_null($this->categories) || !$this->categories->contains($category)) {
            $this->categories[] = $category;
        }
    }

    /**
     * Returns the internal id of a meal.
     *
     * @return int The internal id of a meal
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the internal id of a meal.
     *
     * @param int $id The internal id of a meal
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the date at which this meal is sold at.
     *
     * @return DateTime The date at which this meal is sold at
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date at which this meal is sold at.
     *
     * @param DateTime $date The date at which this meal is sold at.
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Returns the categories of a meal.
     *
     * @return array The categories of a meal
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories of a meal.
     *
     * @param array $categories The categories of a meal
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns the price of a meal.
     *
     * @return float The price of a meal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the price of a meal.
     *
     * @param float $price The price of a meal
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Returns the restaurant of a meal.
     *
     * @return Restaurant The restaurant of a meal
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Sets the restaurant of a meal.
     *
     * @param Restaurant $restaurant The Restaurant of a meal
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * Returns the additional infomation of a meal.
     *
     * @return string The addictional information of a meal
     */
    public function getAddition()
    {
        return $this->addition;
    }

    /**
     * Sets the additional infomation of a meal.
     *
     * @param string $addition The additional infomation of a meal
     */
    public function setAddition($addition)
    {
        $this->addition = $addition;
    }

    /**
     * Returns the place of a meal.
     *
     * @return string The place of a meal
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place of a meal.
     *
     * @param string $place The place of a meal
     */
    public function setPlace(string $place)
    {
        $this->place = $place;
    }
}
