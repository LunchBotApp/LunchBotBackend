<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User Class
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class User
{
    /**
     * @var int The internal id of a user.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"user"})
     */
    private $id;

    /**
     *
     * @var string The slack id of a user.
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", unique=true)
     * @Serializer\Groups({"user"})
     */
    private $userId;

    /**
     *
     * @var array The slack workspaces a user belongs to.
     *
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="users_groups")
     * @Serializer\Groups({"user"})
     */
    private $group;

    /**
     * @var Settings The settings of a user
     *
     * @ORM\OneToOne(targetEntity="Settings")
     * @ORM\JoinColumn(name="settings_id", referencedColumnName="id")
     * @Serializer\Groups({"user_settings"})
     */
    private $settings;

    /**
     * Returns the user id of a user.
     * @return string The user id of a user
     */
    public function __toString()
    {
        return $this->getUserId();
    }

    /**
     * Returns the user id of a user.
     * @return string The user id of a user
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the user id of a user.
     * @param string $userId The user id of a user
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Returns the internal id of a user.
     * @return int The internal id of a user
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the internal id of a user.
     * @param int $id The internal id of a user
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the groups of a user.
     * @return array The groups of a user
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Sets the groups of a user.
     * @param array $group The groups of a user
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * Returns the settings of a user.
     * @return Settings The settings of a use
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Sets the settings of a user.
     * @param Settings $settings The settings of a user.
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }
}
