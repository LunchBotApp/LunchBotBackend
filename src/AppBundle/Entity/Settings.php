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
 * Settings class
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BaseRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Settings
{
    /**
     * @var int the internal id of the settings
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var PriceRange the range of money a user wants to spend
     * @ORM\OneToOne(targetEntity="PriceRange")
     * @ORM\JoinColumn(name="priceRange_id", referencedColumnName="id")
     * @Serializer\Groups({"settings"})
     */
    private $priceRange;

    /**
     * @var Address the address where the user starts from
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * @Serializer\Groups({"settings"})
     */
    private $location;

    /**
     * @var int the max distance the user wants to walk to get to the restaurant
     * @ORM\Column(type="integer",  nullable=true)
     * @Serializer\Groups({"settings"})
     */
    private $distance;

    /**
     * @var String
     * @ORM\Column(type="string",  nullable=true)
     * @Serializer\Groups({"settings"})
     */
    private $locale;

    /**
     * @var Language the language in which the LunchBot should respond
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    /**
     * returns the internal id
     *
     * @return int the internal id of the setings
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id;
     *
     * @param int $id the internal id of the setings
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * returns the priceRange
     *
     * @return PriceRange the priceRange
     */
    public function getPriceRange()
    {
        return $this->priceRange;
    }

    /**
     * sets the priceRange
     *
     * @param PriceRange $priceRange the priceRange
     */
    public function setPriceRange(PriceRange $priceRange)
    {
        $this->priceRange = $priceRange;
    }

    /**
     * returns the address where the user wants to start
     *
     * @return Address the address where the user wants to start
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * sets the address where the user wants to start
     *
     * @param Address $location the address where the user wants to start
     */
    public function setLocation(Address $location)
    {
        $this->location = $location;
    }

    /**
     * returns the max distance a user wants to walk to get to his restaurant
     *
     * @return int the max distance a user wants to walk to get to his restaurant
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * sets the max distance a user wants to walk to get to his restaurant
     *
     * @param int $distance the max distance a user wants to walk to get to his restaurant
     */
    public function setDistance(int $distance)
    {
        $this->distance = $distance;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * sets the language in which the Lunchbot should answer
     *
     * @param Language $language the language in which the Lunchbot should answer
     */
    public function setLanguage(Language $language)
    {
        $this->language = $language;
    }

    /**
     * @return String
     */
    public function getLocale(): String
    {
        return $this->locale;
    }

    /**
     * @param String $locale
     * @return Settings
     */
    public function setLocale(String $locale): Settings
    {
        $this->locale = $locale;

        return $this;
    }


}