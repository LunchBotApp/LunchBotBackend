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

/**
 * Extraction class
 *
 * @ORM\Table(name="extractions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BaseRepository")
 */
class Extraction
{
    const TYPE_DOWNLOAD = "download";
    const TYPE_WEB = "web";
    const TYPE_API = "api";

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var Restaurant  a Restaurant
     * @ORM\OneToOne(targetEntity="Restaurant", mappedBy="extraction")
     */
    private $restaurant;

    /**
     * @var string a URL
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string type of extraction
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var string remote User for API
     * @ORM\Column(type="string", nullable=true)
     */
    private $remoteUser;

    /**
     * @var string remote Password for API
     * @ORM\Column(type="string", nullable=true)
     */
    private $remotePass;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileType;
    /**
     * @var array
     * Has many keyterms
     * @ORM\Column(type="array", nullable=true)
     */
    private $keyTerms;

    /**
     * @var Tag
     * One Volunteer has One Address
     * @ORM\OneToOne(targetEntity="Tag", mappedBy="extraction")
     */
    private $tag;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Restaurant
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * @param Restaurant $restaurant
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }


    /**
     * @return array
     */
    public function getKeyTerms()
    {
        return $this->keyTerms;
    }

    /**
     * @param array $keyTerms
     */
    public function setKeyTerms($keyTerms)
    {
        $this->keyTerms = $keyTerms;
    }

    /**
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param Tag $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Extraction
     */
    public function setType(string $type)
    {
        if ($type == self::TYPE_DOWNLOAD || $type == self::TYPE_WEB || $type == self::TYPE_API) {
            $this->type = $type;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getRemoteUser()
    {
        return $this->remoteUser;
    }

    /**
     * @param string $remoteUser
     * @return Extraction
     */
    public function setRemoteUser(string $remoteUser)
    {
        $this->remoteUser = $remoteUser;

        return $this;
    }

    /**
     * @return string
     */
    public function getRemotePass()
    {
        return $this->remotePass;
    }

    /**
     * @param string $remotePass
     * @return Extraction
     */
    public function setRemotePass(string $remotePass)
    {
        $this->remotePass = $remotePass;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param string $fileType
     */
    public function setFileType(string $fileType)
    {
        $this->fileType = $fileType;
    }

}
