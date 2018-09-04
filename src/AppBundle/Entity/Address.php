<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Address class
 *
 * @ORM\Table(name="addresses")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BaseRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Address
{
    /**
     * @var int the internal id of the Address
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"address"})
     */
    private $id;

    /**
     * @var Country the country of the address
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * @Serializer\Groups({"address"})
     */
    private $country;

    /**
     * @var City the city of the address
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * @Serializer\Groups({"address"})
     */
    private $city;

    /**
     * @var string the street of the address
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"address"})
     */
    private $street;

    /**
     * @var string the house number of the address
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"address"})
     */
    private $number;

    /**
     * @var string the postal code of the address
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"address"})
     */
    private $code;

    /**
     * returns the internal id of the Address
     *
     * @return int the internal id of the Address
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id of the Address
     *
     * @param int $id the internal id of the Address
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * returns the country of the address
     *
     * @return Country the country of the address
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * sets the country of the address
     *
     * @param Country $country the country of the address
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->getNumber() ? ($this->getNumber() . ", " . $this->getStreet() . "\n" . $this->getCode() . " " . $this->getCity()) : "";
    }

    /**
     * returns the house number of the address
     *
     * @return string the house number of the address
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * sets the house number of the address
     *
     * @param string $number the house number of the address
     */
    public function setNumber(string $number)
    {
        $this->number = $number;
    }

    /**
     * returns the street of the address
     *
     * @return string the street of the address
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * sets the street of the address
     *
     * @param string $street the street of the address
     */
    public function setStreet(string $street)
    {
        $this->street = $street;
    }

    /**
     * returns the postal code of the address
     *
     * @return string the postal code of the address
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * sets the postal code of the address
     *
     * @param string $code the postal code of the address
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * returns the city of the address
     *
     * @return City the city of the address
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * sets the city of the address
     *
     * @param City $city the city of the address
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }
}