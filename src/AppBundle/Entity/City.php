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


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * City class
 *
 * @ORM\Table(name="cities")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class City
{
    /**
     * @var int the internal id of the City
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"address", "city"})
     */
    private $id;

    /**
     * @var string the name of the city
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false, unique=true)
     * @Serializer\Groups({"address", "city"})
     */
    private $name;

    /**
     * @var Country the country, the city is part of
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="cities")
     * @ORM\JoinColumn(name="country_id",  referencedColumnName="id")
     */
    private $country;

    /**
     * @var array all the restaurants in that city
     * @ORM\OneToMany(targetEntity="Restaurant", mappedBy="city")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     */
    private $restaurants;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * returns the name of the city
     *
     * @return string the name of the city
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets the name of the city
     *
     * @param string $name the name of the city
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * returns the internal id of the City
     *
     * @return int the internal id of the City
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id of the City
     *
     * @param int $id the internal id of the City
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * returns the country, the city is part of
     *
     * @return Country the country, the city is part of
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * sets the country, the city is part of
     *
     * @param Country $country the country, the city is part of
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    /**
     * returns all the restaurants in that city
     *
     * @return array an array with all the restaurants in that city
     */
    public function getRestaurants()
    {
        return $this->restaurants;
    }

    /**
     * sets all the restaurants in that city
     *
     * @param array $restaurants an array with all the restaurants in that city
     */
    public function setRestaurants(array $restaurants)
    {
        $this->restaurants = $restaurants;
    }
}