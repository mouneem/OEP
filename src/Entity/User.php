<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Course", mappedBy="CreatedBy")
     */
    private $coursesCreated;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Course", mappedBy="ManagedBy")
     * @ORM\JoinTable(name="CourseManagedbyUsers", )
     */
    private $coursesManaged;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Course", mappedBy="Students")
     * @ORM\JoinTable(name="StudentsInCourses", )
     */
    private $courseEnrolled;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Profile", mappedBy="User", cascade={"persist", "remove"})
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JointFile", mappedBy="CreatedBy")
     */
    private $jointFiles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserState", mappedBy="User", cascade={"persist", "remove"})
     */
    private $userState;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Help", mappedBy="User")
     */
    private $helps;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $LastSeen;

    /**
     *
     */
    public function __construct()
    {
        $this->coursesCreated = new ArrayCollection();
        $this->coursesManaged = new ArrayCollection();
        $this->courseEnrolled = new ArrayCollection();
        $this->setProfile( new Profile($this) );
        $this->setUserState(new UserState($this));
        $this->jointFiles = new ArrayCollection();
        $this->helps = new ArrayCollection();
        $this->LastSeen = new DateTime();

    }

    public function getTimeDiff(){
        $d = new DateTime();
        return round(($d->getTimestamp() - $this->LastSeen->getTimestamp())/60);
    }


    public function isOnline(){
        if ( $this->getTimeDiff() > 10 ) {
                return True;
            }
        return False;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function serialize()
    {
        //die('serialize');
        return serialize(array(
            $this->id,
            $this->password
        ));
    }

    public function unserialize( $serialized )
    {
        list (
            $this->id,
            $this->password
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Course[]
     */
    public function getCoursesCreated(): Collection
    {
        return $this->coursesCreated;
    }

    public function addCoursesCreated(Course $coursesCreated): self
    {
        if (!$this->coursesCreated->contains($coursesCreated)) {
            $this->coursesCreated[] = $coursesCreated;
            $coursesCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCoursesCreated(Course $coursesCreated): self
    {
        if ($this->coursesCreated->contains($coursesCreated)) {
            $this->coursesCreated->removeElement($coursesCreated);
            // set the owning side to null (unless already changed)
            if ($coursesCreated->getCreatedBy() === $this) {
                $coursesCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCoursesManaged(): Collection
    {
        return $this->coursesManaged;
    }

    public function addCoursesManaged(Course $coursesManaged): self
    {
        if (!$this->coursesManaged->contains($coursesManaged)) {
            $this->coursesManaged[] = $coursesManaged;
            $coursesManaged->addManagedBy($this);
        }

        return $this;
    }

    public function removeCoursesManaged(Course $coursesManaged): self
    {
        if ($this->coursesManaged->contains($coursesManaged)) {
            $this->coursesManaged->removeElement($coursesManaged);
            $coursesManaged->removeManagedBy($this);
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourseEnrolled(): Collection
    {
        return $this->courseEnrolled;
    }

    public function addCourseEnrolled(Course $courseEnrolled): self
    {
        if (!$this->courseEnrolled->contains($courseEnrolled)) {
            $this->courseEnrolled[] = $courseEnrolled;
            $courseEnrolled->addStudent($this);
        }

        return $this;
    }

    public function removeCourseEnrolled(Course $courseEnrolled): self
    {
        if ($this->courseEnrolled->contains($courseEnrolled)) {
            $this->courseEnrolled->removeElement($courseEnrolled);
            $courseEnrolled->removeStudent($this);
        }

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

        // set the owning side of the relation if necessary
        if ($this !== $profile->getUser()) {
            $profile->setUser($this);
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
            $jointFile->setCreatedBy($this);
        }

        return $this;
    }

    public function removeJointFile(JointFile $jointFile): self
    {
        if ($this->jointFiles->contains($jointFile)) {
            $this->jointFiles->removeElement($jointFile);
            // set the owning side to null (unless already changed)
            if ($jointFile->getCreatedBy() === $this) {
                $jointFile->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getUserState(): ?UserState
    {
        return $this->userState;
    }

    public function setUserState(UserState $userState): self
    {
        $this->userState = $userState;

        // set the owning side of the relation if necessary
        if ($this !== $userState->getUser()) {
            $userState->setUser($this);
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

    /**
     * @return Collection|Help[]
     */
    public function getHelpsId(): Collection
    {
        $a = array();
        foreach ( $this.$this->getHelps() as $h ){
            array_push($a, $h->getUser()->getId());
            return $this->helps;
        }
    }

    public function addHelp(Help $help): self
    {
        if (!$this->helps->contains($help)) {
            $this->helps[] = $help;
            $help->setUser($this);
        }

        return $this;
    }

    public function removeHelp(Help $help): self
    {
        if ($this->helps->contains($help)) {
            $this->helps->removeElement($help);
            // set the owning side to null (unless already changed)
            if ($help->getUser() === $this) {
                $help->setUser(null);
            }
        }

        return $this;
    }

    public function getLastSeen(): ?\DateTimeInterface
    {
        return $this->LastSeen;
    }

    public function setLastSeen(?\DateTimeInterface $LastSeen): self
    {
        $this->LastSeen = $LastSeen;

        return $this;
    }


}
