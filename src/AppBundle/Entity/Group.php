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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The Slack Workspace
 *
 * @ORM\Table(name="groups")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupRepository")
 */
class Group
{
    /**
     * @var int The internal id of a group.
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string The slack id of a group.
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string", unique=true)
     */
    private $groupId;

    /**
     * @var string The verification token of a group.
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @var array The users in a group.
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="group")
     */
    private $users;

    /**
     * The contructor for a group.
     *
     * @param string $groupId The slack id of this group.
     * @param string $token The verification token of this group.
     */
    public function __construct($groupId, $token)
    {
        $this->groupId = $groupId;
        $this->token   = $token;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Group
     */
    public function setId(int $id): Group
    {
        $this->id = $id;

        return $this;
    }
    /**
     * Set the slack id of a group.
     *
     * @param string $groupId The slack id of a group.
     *
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * Get the slack id of a group.
     *
     * @return string The slack id of a group.
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set the verification token of a group.
     *
     * @param string $token The verification token of a group.
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get the verfication token of a group.
     *
     * @return string The verification token of a group.
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the users of a group.
     *
     * @param array $user The users of a group.
     */
    public function setUser($user)
    {
        $this->users = $user;
    }

    /**
     * Get all users of a group.
     *
     * @return array The users of a group.
     */
    public function getUser()
    {
        return $this->users;
    }
}
