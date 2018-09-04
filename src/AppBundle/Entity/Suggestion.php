<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstract Suggestion class
 *
 * @ORM\Table(name="suggestions")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"suggestion"="Suggestion","solo"="SoloSuggestion","group" = "GroupSuggestion"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SuggestionRepository")
 * @Serializer\ExclusionPolicy("none")
 */
abstract class Suggestion
{

    /**
     * @var int The internal id of a suggestion
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Rating|array The ratings of meal from the users
     *
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="suggestion")
     * @ORM\JoinColumn(name="rating_id", referencedColumnName="id")}
     * @Serializer\Groups({"suggestion"})
     */
    protected $ratings;

    /**
     * @var Rating|array The ratings of meal from the users
     *
     * @ORM\OneToMany(targetEntity="MealChoice", mappedBy="suggestion")
     * @ORM\JoinColumn(name="meal_choice_id", referencedColumnName="id")}
     * @Serializer\Groups({"suggestion"})
     */
    protected $mealChoices;

    /**
     * @var DateTime The date and time of a suggestion.
     *
     * @Assert\NotBlank()
     * @ORM\column(type="datetime")
     */
    protected $timestamp;

    /**
     * @var Settings The settings for this whole suggesiton
     *
     * @ORM\OneToOne(targetEntity="Settings")
     * @ORM\JoinColumn(name="setting_id", referencedColumnName="id")
     * @Serializer\Groups({"suggestion"})
     */
    protected $settings;

    /**
     * Adds a new rating to a suggestion.
     *
     * @param Rating|array $rating The rating to be added
     */
    public function addRating($rating)
    {
        $this->ratings[] = $rating;
    }

    /**
     * Returns the internal id of a suggestion.
     *
     * @return int The internal id of a suggestion.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the internal id of a suggestion.
     *
     * @param int $id The internal id of a suggestion.
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the date and time of a suggestion.
     *
     * @return DateTime The date and time of a suggestion.
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Sets the date and time of a suggestion.
     *
     * @param DateTime $timestamp The date and time of a suggestion.
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Returns the settings of a suggestion.
     *
     * @return Settings The settings of a suggestion.
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Sets the settings of a suggestion.
     *
     * @param Settings $settings The settings of a suggestion.
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Returns the ratings of an suggestion.
     *
     * @return Rating|array The ratings of an suggestion.
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * Sets the ratings of an suggestion.
     *
     * @param Rating|array $ratings The ratings of an suggestion.
     */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;
    }

    /**
     * @return Rating|array
     */
    public function getMealChoices()
    {
        return $this->mealChoices;
    }

    /**
     * @param Rating|array $mealChoices
     * @return Suggestion
     */
    public function setMealChoices($mealChoices)
    {
        $this->mealChoices = $mealChoices;

        return $this;
    }
}
