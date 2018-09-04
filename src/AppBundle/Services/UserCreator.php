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

namespace AppBundle\Services;

use AppBundle\Entity\Group;
use AppBundle\Entity\Settings;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserCreator
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * VolunteerHelperService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $userIDs
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function userIdsToUsers($userIDs)
    {
        $users = [];
        foreach ($userIDs as $userID) {
            $users[] = $this->checkAndcreateUser($userID)["user"];
        }

        return $users;
    }

    /**
     * @param $userID
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function checkAndcreateUser($userID)
    {
        $user    = $this->em->getRepository(User::class)->getByUserId($userID);
        $created = 0;
        if (!$user) {
            $user = new User();
            $user->setUserId($userID);
            $settings = new Settings();;
            $user->setSettings($settings);
            $this->em->persist($settings);
            $this->em->persist($user);
            $this->em->flush();
            $created = 1;
        }

        return ["user" => $user, "created" => $created];
    }

    /**
     * @param $userID
     * @param $groupID
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function userToGroup($userID, $groupID)
    {
        $user  = $this->em->getRepository(User::class)->getByUserId($userID);
        $group = $this->em->getRepository(Group::class)->getByGroupId($groupID);
        if (count($user->getGroup()) == 0) {
            $user->setGroup([$group]);
            $this->em->merge($user);
            $this->em->flush();
        }
    }

}