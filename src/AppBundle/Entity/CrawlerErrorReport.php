<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * CrawlerErrorReport class
 *
 * @ORM\Table(name="crawler_error_reports")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BaseRepository")
 */
class CrawlerErrorReport extends Report
{

    /**
     * @var User the user who sent the report
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(type="string",  nullable=false)
     */
    private $errorMessage;

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return CrawlerErrorReport
     */
    public function setErrorMessage(string $errorMessage): CrawlerErrorReport
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }


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
}