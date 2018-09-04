<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Language class
 *
 * @ORM\Table(name="languages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LanguageRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Language
{
    /**
     * @var int the internal id of the settings
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"language"})
     */
    private $id;

    /**
     * @var string the name of the language
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"language"})
     */
    private $name;

    /**
     * @var string the ISO-Code of the language
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     * @Serializer\Groups({"language"})
     */
    private $locale;

    /**
     * @var array all the translations of this language  all the translations of this language
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="language")
     */
    private $translations;

    /**
     * returns the internal id of the settings
     * @return int the internal id of the settings
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id of the settings
     * @param int $id the internal id of the settings
     */
    public function setId(int $id)
    {
        $this->id = $id;
    } 

    /**
     * returns the name of the language
     * @return string the name of the language
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets the name of the language
     * @param string $name the name of the language
     */
    public function setName(string $name)
    {
        $this->name = $name;
    } 

    /**
     * returns the ISO-Code of the language
     * @return string the ISO-Code of the language
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * sets the ISO-Code of the language
     * @param string $locale the ISO-Code of the language
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    } 

    /**
     * returns all the translations of this language
     * @return array an array with all the translations of this language
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * sets all the translations of this language
     * @param array $translations an array with all the translations of this language
     */
    public function setTranslations(array $translations)
    {
        $this->translations = $translations;
    } 
}