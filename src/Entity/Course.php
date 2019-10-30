<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 */
class Course
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedOn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="coursesCreated")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CreatedBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="coursesManaged")
     * @ORM\JoinTable(name="CourseManagedbyUsers", )
     */
    private $ManagedBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisible;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="courseEnrolled")
     * @ORM\JoinTable(name="StudentsInCourses", )
     */
    private $Students;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $StartTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $EndTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CourseLocation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublic;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Pad", mappedBy="course", cascade={"persist", "remove"})
     */
    private $pad;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JointFile", mappedBy="Course")
     */
    private $jointFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Help", mappedBy="Course")
     */
    private $helps;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $link;

    /**
     * Course constructor.
     * @param User $currentUser
     */
    public function __construct( User $currentUser)
    {
        $this->CreatedOn =  new \DateTime();("Y/m/d");
        $this->CreatedBy =  $currentUser;
        $this->isVisible = True;
        $this->isPublic = True;
        $this->ManagedBy = new ArrayCollection();
        $this->ManagedBy->add($currentUser);
        $this->Students = new ArrayCollection();
        $this->setPad(New Pad($this));
        $this->jointFiles = new ArrayCollection();
        $this->helps = new ArrayCollection();
        $this->setLink($this->generateRandomString(6    ));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

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

    public function getCategory(): ?string
    {
        return $this->Category;
    }

    public function setCategory(?string $Category): self
    {
        $this->Category = $Category;

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

    /**
     * @return Collection|User[]
     */
    public function getManagedBy(): Collection
    {
        return $this->ManagedBy;
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function addManagedBy(User $managedBy): self
    {
        if (!$this->ManagedBy->contains($managedBy)) {
            $this->ManagedBy[] = $managedBy;
        }

        return $this;
    }

    public function removeManagedBy(User $managedBy): self
    {
        if ($this->ManagedBy->contains($managedBy)) {
            $this->ManagedBy->removeElement($managedBy);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getStudents(): Collection
    {
        return $this->Students;
    }



    /**
     */
    public function getStudentsList(): array
    {
        $lt = array();
        foreach ( $this->Students as $student) {
            array_push($lt,$student->getId());
        }
        return $lt;
    }




    public function addStudent(User $student): self
    {
        if (!$this->Students->contains($student)) {
            $this->Students[] = $student;
        }
        return $this;
    }

    public function removeStudent(User $student): self
    {
        if ($this->Students->contains($student)) {
            $this->Students->removeElement($student);
        }

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->StartTime;
    }

    public function setStartTime(?\DateTimeInterface $StartTime): self
    {
        $this->StartTime = $StartTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->EndTime;
    }

    public function setEndTime(?\DateTimeInterface $EndTime): self
    {
        $this->EndTime = $EndTime;

        return $this;
    }

    public function getCourseLocation(): ?string
    {
        return $this->CourseLocation;
    }

    public function setCourseLocation(?string $CourseLocation): self
    {
        $this->CourseLocation = $CourseLocation;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getPad(): ?Pad
    {
        return $this->pad;
    }

    public function setPad(Pad $pad): self
    {
        $this->pad = $pad;

        // set the owning side of the relation if necessary
        if ($this !== $pad->getCourse()) {
            $pad->setCourse($this);
        }

        return $this;
    }

    /**
     * @return Collection|JointFile[]
     */
    public function getJointFiles(): Collection
    {
        return $this->jointFiles;
    }

    public function addJointFile(JointFile $jointFile): self
    {
        if (!$this->jointFiles->contains($jointFile)) {
            $this->jointFiles[] = $jointFile;
            $jointFile->setCourse($this);
        }

        return $this;
    }

    public function removeJointFile(JointFile $jointFile): self
    {
        if ($this->jointFiles->contains($jointFile)) {
            $this->jointFiles->removeElement($jointFile);
            // set the owning side to null (unless already changed)
            if ($jointFile->getCourse() === $this) {
                $jointFile->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Help[]
     */
    public function getHelps(): Collection
    {
        return $this->helps;
    }

    public function getHelpsStudentsIds()
    {
        $hs = $this->getHelps();
        $a = array();
        foreach ($hs as $h){
            if ($h->getIsNeeded()){
                array_push($a, $h->getUser()->getId());
            }
        }
        return $a;
    }

    public function getHelpsByUser(User $user)
    {
        $z = False;
        $hs = $this->getHelps();
        foreach ($hs as $h){
            if ($h->getUser()->getId() == $user->getId() ){
                $z = $h;
            }
        }
        return $z;
    }


    public function getStudentSatInCourse(User $user)
    {
        $z = False;
	$a = [];
        $hs = $this->getHelps();
        foreach ($hs as $h){
            if ($h->getUser()->getId() == $user->getId() ){
                $z = $h;
		array_push($a,$z);
            }
        }
        return $a;
    }

    public function addHelp(Help $help): self
    {
        if (!$this->helps->contains($help)) {
            $this->helps[] = $help;
            $help->setCourse($this);
        }

        return $this;
    }

    public function removeHelp(Help $help): self
    {
        if ($this->helps->contains($help)) {
            $this->helps->removeElement($help);
            // set the owning side to null (unless already changed)
            if ($help->getCourse() === $this) {
                $help->setCourse(null);
            }
        }

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }



}
