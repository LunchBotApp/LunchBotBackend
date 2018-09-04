<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Restaurant class
 *
 * @ORM\Table(name="restaurants")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RestaurantRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Restaurant
{
    /**
     * @var int The internal id of a restaurant
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"restaurant"})
     */
    private $id;

    /**
     * @var string The name of a restaurant
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"restaurant"})
     */
    private $name;

    /**
     * @var string The Url of a website from a restaurant
     *
     * @Assert\Url()
     * @ORM\Column(type="string",  nullable=true)
     * @Serializer\Groups({"restaurant"})
     */
    private $website;

    /**
     * @var string The phone number of a restaurant
     *
     * @ORM\Column(type="string",  nullable=true)
     * @Serializer\Groups({"restaurant"})
     */
    private $phone;

    /**
     * @var string The e-mail address of a restaurant
     *
     * @Assert\Email()
     * @ORM\Column(type="string",  nullable=true)
     * @Serializer\Groups({"restaurant"})
     */
    private $email;

    /**
     * @var array The meals of a restaurant
     * One restaurant has many meals.
     * @ORM\OneToMany(targetEntity="Meal", mappedBy="restaurant")
     * @ORM\OrderBy({"date" = "ASC", "price" = "ASC"})
     * @Serializer\Groups({"restaurant_meals"})
     */
    private $meals;

    /**
     * @var Address The Address of a restaurant
     * One restaurant has one address
     * @ORM\OneToOne(targetEntity="Address",cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * @Serializer\Groups({"restaurant"})
     */
    private $address;

    /**
     * @var Extraction The information for extracting meals from a restaurant
     * One restaurant has one extraction
     * @ORM\OneToOne(targetEntity="Extraction", inversedBy="restaurant")
     * @ORM\JoinColumn(name="extraction_id", referencedColumnName="id")
     */
    private $extraction;

    /**
     * Do not manually modify this.
     * @var City The city of a restaurant
     *
     * @ORM\ManyToOne(targetEntity="City", inversedBy="restaurants")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @return string The name of a restaurant
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Adds a meal to a restaurant.
     * @param Meal $meal The meal to be added
     */
    public function addMeal($meal)
    {
        $this->meals[] = $meal;
    }

    /**
     * Returns the name of a restaurant.
     * @return string The name  of a restaurant
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name  of a restaurant.
     * @param string $name The name  of a restaurant
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the internal id of a restaurant.
     * @return int The internal id of a restaurant.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the internal id  of a restaurant.
     * @param int $id The internal id of a restaurant
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Returns the website  of a restaurant.
     * @return string The website  of a restaurant
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Sets the website of a restaurant.
     * @param string $website The website of a restaurant
     */
    public function setWebsite(string $website)
    {
        $this->website = $website;
    }

    /**
     * Returns the phone number of a restaurant.
     * @return string The phone number of a restaurant
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the phone number of a restaurant.
     * @param string $phone The phone number  of a restaurant
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * Returns the e-mail address of a restaurant.
     * @return string The e-mail address of a restaurant
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the e-mail address of a restaurant.
     * @param string $email The e-mail address of a restaurant
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Returns all the meals of a restaurant.
     * @return array An array with all the meals of a restaurant
     */
    public function getMeals()
    {
        return $this->meals;
    }

    /**
     * Set the meals of a restaurant.
     * @param array $meals The meals of a restaurant
     */
    public function setMeals($meals)
    {
        $this->meals = $meals;
    }


    /**
     * Return the address of a restaurant.
     * @return Address The addressof a restaurant.
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address of a restaurant.
     * @param Address $address The address of a restaurant
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
        $this->city = $address->getCity();
    }

    /**
     * Returns the extraction of a restaurant.
     * @return Extraction The extraction of a restaurant
     */
    public function getExtraction()
    {
        return $this->extraction;
    }

    /**
     * Sets the extraction of a restaurant.
     * @param Extraction $extraction The extraction of a restaurant.
     */
    public function setExtraction(Extraction $extraction)
    {
        $this->extraction = $extraction;
    }
}
