<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HelpRepository")
 */
class Help
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isNeeded;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="helps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Course;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="helps")
     */
    private $User;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $time;

    /**
     * Help constructor.
     * @param $Course
     * @param $User
     * @throws \Exception
     */
    public function __construct($Course, $User)
    {
        $this->isNeeded = False;
        $this->Course = $Course;
        $this ->setTime(new \DateTime());
        $this->User = $User;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsNeeded(): ?bool
    {
        return $this->isNeeded;
    }

    public function setIsNeeded(bool $isNeeded): self
    {
        $this->isNeeded = $isNeeded;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->Course;
    }

    public function setCourse(?Course $Course): self
    {
        $this->Course = $Course;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }
}
