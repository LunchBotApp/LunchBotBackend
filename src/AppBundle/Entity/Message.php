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
 * Message class
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Message
{
    /**
     * @var int the internal id of the message
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"message"})
     */
    private $id;

    /**
     * @var string the messages's key
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"message"})
     */
    private $messageKey;

    /**
     * @var array all the translations of this message
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="message")
     */
    private $translations;

    /**
     * returns the internal id of the message
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id of the message
     * @param int $id the internal id of the message
     */
    public function setId(int $id)
    {
        $this->id = $id;
    } 

    /**
     * returns the mesages's key
     * @return string the mesages's key
     */
    public function getMessageKey()
    {
        return $this->messageKey;
    }

    /**
     * sets the mesages's key
     * @param string $messageKey the mesages's key
     */
    public function setMessageKey(string $messageKey)
    {
        $this->messageKey = $messageKey;
    } 

    /**
     * returns all the translations of this message
     * @return array an array with all the translations of this message
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * returns all the translations of this message
     * @param array $translations an array with all the translations of this message
     */
    public function setTranslations(array $translations)
    {
        $this->translations = $translations;
    } 
}