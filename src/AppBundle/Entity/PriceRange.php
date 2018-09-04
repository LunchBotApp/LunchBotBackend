<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PriceRange class
 *
 * @ORM\Table(name="price_ranges")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BaseRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class PriceRange
{
    /**
     * @var int the internal id of the priceRange
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int the min amount of money the user wants to spend
     * @Assert\NotBlank()
     * @ORM\Column(type="integer",  nullable=false)
     * @Serializer\Groups({"priceRange"})
     */
    private $min;

    /**
     * @var int the max amount of money the user wants to spend the max amount of money the user wants to spen
     * @Assert\NotBlank()
     * @ORM\Column(type="integer",  nullable=false)
     * @Serializer\Groups({"priceRange"})
     */
    private $max;

    /**
     * returns the internal id of the priceRange
     *
     * @return int the internal id of the priceRange
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id of the priceRange
     *
     * @param int $id the internal id of the priceRange
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * returns the min amount of money the user wants to spend
     *
     * @return int the min amount of money the user wants to spend
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * sets the min amount of money the user wants to spend
     *
     * @param int $min the min amount of money the user wants to spend
     */
    public function setMin(int $min)
    {
        $this->min = $min;
    }

    /**
     * returns the max amount of money the user wants to spen
     *
     * @return int the max amount of money the user wants to spen
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * sets the max amount of money the user wants to spen
     *
     * @param int $max the max amount of money the user wants to spen
     */
    public function setMax(int $max)
    {
        $this->max = $max;
    }
}