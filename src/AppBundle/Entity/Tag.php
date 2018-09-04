<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tag class
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
{
    const TYPE_DATE  = "date";
    const TYPE_PRICE = "price";
    const TYPE_DESCR = "description";

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=false)
     */
    private $value;

    /**
     * @var string
     * @ORM\Column(type="string",  nullable=true)
     */
    private $format;

    /**
     * @var boolean
     * @ORM\Column(type="boolean",  nullable=true)
     */
    private $print;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @var Extraction
     * One Address has One Volunteer
     * @ORM\OneToOne(targetEntity="Extraction", inversedBy="tag",cascade={"persist"})
     */
    private $extraction;

    /**
     * @var Tag
     * Many Tags have One Parent
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;


    /**
     * @var Tag[]
     * One Crawler has Many Children.
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="parent")
     */
    private $children;


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Tag
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Tag
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Tag
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Tag $parent
     * @return Tag
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Tag[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Tag[] $children
     * @return Tag
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrint()
    {
        return $this->print;
    }

    /**
     * @param bool $print
     * @return Tag
     */
    public function setPrint($print)
    {
        $this->print = $print;

        return $this;
    }

    /**
     * @return Extraction
     */
    public function getExtraction()
    {
        return $this->extraction;
    }

    /**
     * @param Extraction $extraction
     * @return Tag
     */
    public function setExtraction($extraction)
    {
        $this->extraction = $extraction;

        return $this;
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
     * @return Tag
     */
    public function setType($type)
    {
        if ($type == self::TYPE_PRICE || $type == self::TYPE_DESCR || $type == self::TYPE_DATE) {
            $this->type = $type;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return Tag
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

}