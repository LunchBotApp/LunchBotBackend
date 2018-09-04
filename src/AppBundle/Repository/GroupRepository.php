<?php

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