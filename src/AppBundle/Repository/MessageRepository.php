<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Message as Message;

/**
 * MessageRepository
 */
class MessageRepository extends BaseRepository
{
	/**
     * Returns the message with that key
     *
     * @param int $key the messages's key
     * @return Message a message
     */
	public function getByKey($key)
	{
		return $this->findOneBy(['messageKey' => $key]);
	}
}