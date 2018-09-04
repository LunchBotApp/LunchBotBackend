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

namespace AppBundle\Repository;

use AppBundle\Entity\Group;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * The repository handling the saving, deleting and fetching of groups
 *
 * @package AppBundle\Repository
 */
class GroupRepository extends BaseRepository
{
    /**
     * @param $userToCheck
     * @param $groupID
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function groupcheck($userToCheck, $groupID) {
        $group = $this->findOneBy(['groupId' => $groupID]);

        if ($group === NULL) {
            return false;
        }

        $groupUsers = $group->getUser();

        foreach ($groupUsers as $userInGroup) {
            if ($userInGroup->getId() == $userToCheck->getId()) {
                return true;
            }
        }

        $em = $this->getEntityManager();

        $groupUsers[] = $userToCheck;
        $group->setUser($groupUsers);

        $em->merge($group);
        $em->flush();

        return true;
    }

    /**
     * @param $groupID
     * @return Group|NULL|object
     */
    public function getByGroupId($groupID) {
        return $this->findOneBy(['groupId' => $groupID]);
    }
}