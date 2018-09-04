<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AdminUser class
 *
 * @ORM\Table(name="admin_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BaseRepository")
 */
class AdminUser extends BaseUser
{
    /**
     * @var int the internal id of the AdminUser
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @var string the AdminUser's name
     * @Assert\NotBlank()
     * @ORM\Column(type="string",  nullable=true)
     */
    private $name;

    /**
     * returns the internal id of the AdminUser
     *
     * @return int the internal id of the AdminUser
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id of the AdminUser
     *
     * @param int $id the internal id of the AdminUser
     * @return AdminUser
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * returns the AdminUser's name
     *
     * @return string the AdminUser's name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets the AdminUser's name
     *
     * @param string $name the AdminUser's name
     * @return AdminUser
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }


}