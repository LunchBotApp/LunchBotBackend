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
 * Translation class
 *
 * @ORM\Table(name="translations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TranslationRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Translation
{
    /**
     * @var int the internal id of the translation
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"translation"})
     */
    private $id;

    /**
     * @var string the translated text
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"translation"})
     */
    private $value;

    /**
     * @var Language the language in which the message got translated
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="translations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * @Serializer\Groups({"translation"})
     */
    private $language;

    /**
     * @var Message the message which got translated
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="translations")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     * @Serializer\Groups({"translation"})
     */
    private $message;

    /**
     * returns the id of the translation
     * @return int the internal id of the translation
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the id of the translation
     * @param int $id the internal id of the translation
     */
    public function setId(int $id)
    {
        $this->id = $id;
    } 

    /**
     * returns the value
     * @return string the value of the translation
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * sets the value
     * @param string $value the value of the tranlsation
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    } 

    /**
     * returns the language of the translation
     * @return Language the language of the translation
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * sets the language of the translation
     * @param Language $language the language of the translation
     */
    public function setLanguage(Language $language)
    {
        $this->language = $language;
    } 

    /** 
     * returns the message
     * @return Message the message of the translation
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * sets the message
     * @param Message $message the message of the translation
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    } 
}