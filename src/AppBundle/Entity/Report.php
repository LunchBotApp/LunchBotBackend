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


use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Report class
 *
 * @ORM\Table(name="reports")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"report"="Report","issue" = "IssueReport", "feedback"="FeedbackReport", "crawler"="CrawlerErrorReport"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReportRepository")
 */
class Report
{
    /**
     * @var int the internal id of the settings
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var bool true if someone solved the problem, false if nobody did
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean",  nullable=true)
     */
    protected $finished = false;

    /**
     * @var string a short description of the problem
     * @ORM\Column(type="string",  nullable=true)
     */
    protected $message;

    /**
     * @var DateTime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * Report constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * returns the internal id
     *
     * @return int the internal id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the internal id
     *
     * @param int $id the internal id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * returns the information about the status of the report
     *
     * @return bool the information about the status of the report
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * sets the information about the status of the report
     *
     * @param bool $finished the information about the status of the report
     */
    public function setFinished(bool $finished)
    {
        $this->finished = $finished;
    }

    /**
     * returns the description of the problem
     *
     * @return string the description of the problem
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * sets the description of the problem
     *
     * @param string $message the description of the problem
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Report
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;

        return $this;
    }


}