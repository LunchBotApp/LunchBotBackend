<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Country class
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Country
{
    /**
     * @var int the internal id of the country
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"address", "country"})
     */
    private $id;

    /**
     * @var string the name for the country
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false, unique=true)
     * @Serializer\Groups({"address", "country"})
     */
    private $name;

    /**
     * @var array all the cities in this country which are supported by LunchBot
     * @ORM\OneToMany(targetEntity="City", mappedBy="country")
     */
    private $cities;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * returns the name of the country
     *
     * @return string the name of the country
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets the name of the country
     *
     * @param string $name the name of the country
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * returns the internal id of the country
     *
     * @return int the internal id of the country
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id of the country
     *
     * @param int $id the internal id of the country
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * returns all the cities in this country which are supported by LunchBot
     *
     * @return array an array with all the cities in this country which are supported by LunchBot
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * sets all the cities in this country which are supported by LunchBot
     *
     * @param array $cities an array with all the cities in this country which are supported by LunchBot
     */
    public function setCities(array $cities)
    {
        $this->cities = $cities;
    }
}