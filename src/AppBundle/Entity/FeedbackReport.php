<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeedbackReport class
 *
 * @ORM\Table(name="feedback_reports")
 * @ORM\Entity()
 */
class FeedbackReport extends Report
{
    const TYPE_DEFAULT    = "default";
    const TYPE_RESTAURANT = "restaurant";

    /**
     * @var User the user who sent the report
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $feedbackType;

    /**
     * returns the user who sent the report
     *
     * @return User the user who sent the report
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * sets the user who sent the report
     *
     * @param User $user the user who sent the report
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }


    /**
     * @return string
     */
    public function getFeedbackType()
    {
        return $this->feedbackType;
    }

    /**
     * @param string $type
     * @return FeedbackReport
     */
    public function setFeedbackType(string $type)
    {
        if ($type == self::TYPE_DEFAULT || $type == self::TYPE_RESTAURANT) {
            $this->feedbackType = $type;
        }

        return $this;
    }
}