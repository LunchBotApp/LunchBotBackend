<?php


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