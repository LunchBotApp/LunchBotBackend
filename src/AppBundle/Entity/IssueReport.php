<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Report as Report;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * IssueReport class
 *
 * @ORM\Table(name="issue_reports")
 * @ORM\Entity()
 */
class IssueReport extends Report
{
    /**
     * @var User the user who sent the report
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * returns the user who sent the report
     * @return User the user who sent the report
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * sets the user who sent the report
     * @param User $user the user who sent the report
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    } 
}