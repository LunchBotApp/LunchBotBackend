<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The category of a meal.
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Category
{

    /**
     * @var int The internal id of a category.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"category"})
     */
    private $id;

    /**
     * @var string The name of a category.
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     * @Serializer\Groups({"category"})
     */
    private $name;

    /**
     * @var array The search terms of meals of this category.
     *
     * @ORM\Column(type="array")
     */
    private $searchTerms;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Meal", mappedBy="categories")
     */
    private $meals;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Adds a new meal to a category.
     * @param Meal $meal The meal to be added
     */
    public function addMeal($meal)
    {
        $this->meals[] = $meal;
    }

    /**
     * Adds a new search term to a category.
     * @param String $term The search term to be added
     */
    public function addSearchTerm($term)
    {
        $this->searchTerms[] = $term;
    }

    /**
     * Returns the name of the category.
     *
     * @return string The name of the category.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the names of the category.
     *
     * @param $name string The name of the category
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the id of the category.
     *
     * @return int The id of the category
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of a category.
     * @param int $id The internal id of a category
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the search terms of the category.
     *
     * @return mixed The seach terms of the category.
     */
    public function getSearchTerms()
    {
        return $this->searchTerms;
    }

    /**
     * Sets the search terms of the category.
     *
     * @param array $searchTerms The search terms of the category. Regex expressions
     */
    public function setSearchTerms($searchTerms)
    {
        $this->searchTerms = $searchTerms;
    }

    /**
     * Returns the meals of a category.
     * @return array The meals of a category
     */
    public function getMeals()
    {
        return $this->meals;
    }

    /**
     * Sets the meals of a category.
     * @param array $meals The meals of a category
     */
    public function setMeals($meals)
    {
        $this->meals = $meals;
    }
}
