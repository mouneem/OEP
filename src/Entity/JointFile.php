<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JointFileRepository")
 */
class JointFile
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $FileLocation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedOn;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="jointFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CreatedBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="jointFiles")
     */
    private $Course;

    /**
     * @ORM\Column(type="integer")
     */
    private $DownloadCount;

    /**
     * JointFile constructor.
     * @param $Course
     * @param User $U
     */
    public function __construct( $Course, UserInterface $U)
    {
        $currentUser = $U;
        $this->CreatedOn =  new \DateTime();("Y/m/d");
        $this->CreatedBy =  $currentUser;
        $this->Course = $Course;
        $this->DownloadCount = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getFileLocation(): ?string
    {
        return $this->FileLocation;
    }

    public function setFileLocation(?string $FileLocation): self
    {
        $this->FileLocation = $FileLocation;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->CreatedOn;
    }

    public function setCreatedOn(\DateTimeInterface $CreatedOn): self
    {
        $this->CreatedOn = $CreatedOn;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->CreatedBy;
    }

    public function setCreatedBy(?User $CreatedBy): self
    {
        $this->CreatedBy = $CreatedBy;

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

    public function getDownloadCount(): ?int
    {
        return $this->DownloadCount;
    }

    public function setDownloadCount(int $DownloadCount): self
    {
        $this->DownloadCount = $DownloadCount;

        return $this;
    }

    public function addDownloadCount(): self
    {
        $this->DownloadCount = $this->DownloadCount+1;

        return $this;
    }
}
