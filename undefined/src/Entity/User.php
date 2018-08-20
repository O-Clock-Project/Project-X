<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
// Import de la classe UserInterface pour permettre la connexion (authentification)
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;
    
    /**
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $is_active = true;
    
    /**
     * @ORM\Column(type="string", length=128)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length=128)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo_github;

    /**
     * @ORM\Column(type="integer")
     */
    private $zip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bookmark", mappedBy="user")
     * @MaxDepth(1)
     */
    private $bookmarks;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Bookmark", mappedBy="faved_by")
     * @ORM\JoinTable(name="bookmark_faved")
     * @MaxDepth(1)
     */
    private $favorites;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Bookmark", mappedBy="certified_by")
     * @ORM\JoinTable(name="bookmark_certified")
     * @MaxDepth(1)
     */
    private $certified_bookmarks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     * @MaxDepth(1)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speciality", inversedBy="students")
     * @MaxDepth(1)
     */
    private $speciality;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="voter")
     * @MaxDepth(1)
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WarningBookmark", mappedBy="author")
     * @MaxDepth(1)
     */
    private $bookmarks_warned;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Announcement", mappedBy="author")
     * @MaxDepth(1)
     */
    private $announces;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="user", orphanRemoval=true)
     * @MaxDepth(1)
     */
    private $affectations;

    /**
     * @SerializedName("avatar")
     */
    public $avatar;


    public function __construct()
    {
        $this->bookmarks = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->certified_bookmarks = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->bookmarks_warned = new ArrayCollection();
        $this->announces = new ArrayCollection();
        $this->affectations = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return  $this->avatar;
    }

    /**
     * @ORM\PostLoad
     * @ORM\PreUpdate
     */
    public function setAvatar(): self
    {
        $this->avatar = 'https://avatars.githubusercontent.com/'. $this->pseudo_github;

        return $this;
    }

    public function getPseudoGithub(): ?string
    {
        return $this->pseudo_github;
    }

    public function setPseudoGithub(string $pseudo_github): self
    {
        $this->pseudo_github = $pseudo_github;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection|Bookmark[]
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setUser($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->contains($bookmark)) {
            $this->bookmarks->removeElement($bookmark);
            // set the owning side to null (unless already changed)
            if ($bookmark->getUser() === $this) {
                $bookmark->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bookmark[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Bookmark $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->addFavedBy($this);
        }

        return $this;
    }

    public function removeFavorite(Bookmark $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            $favorite->removeFavedBy($this);
        }

        return $this;
    }

    /**
     * @return Collection|Bookmark[]
     */
    public function getCertifiedBookmarks(): Collection
    {
        return $this->certified_bookmarks;
    }

    public function addCertifiedBookmark(Bookmark $certifiedBookmark): self
    {
        if (!$this->certified_bookmarks->contains($certifiedBookmark)) {
            $this->certified_bookmarks[] = $certifiedBookmark;
            $certifiedBookmark->addCertifiedBy($this);
        }

        return $this;
    }

    public function removeCertifiedBookmark(Bookmark $certifiedBookmark): self
    {
        if ($this->certified_bookmarks->contains($certifiedBookmark)) {
            $this->certified_bookmarks->removeElement($certifiedBookmark);
            $certifiedBookmark->removeCertifiedBy($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setVoter($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getVoter() === $this) {
                $vote->setVoter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WarningBookmark[]
     */
    public function getBookmarksWarned(): Collection
    {
        return $this->bookmarks_warned;
    }

    public function addBookmarksWarned(WarningBookmark $bookmarksWarned): self
    {
        if (!$this->bookmarks_warned->contains($bookmarksWarned)) {
            $this->bookmarks_warned[] = $bookmarksWarned;
            $bookmarksWarned->setAuthor($this);
        }

        return $this;
    }

    public function removeBookmarksWarned(WarningBookmark $bookmarksWarned): self
    {
        if ($this->bookmarks_warned->contains($bookmarksWarned)) {
            $this->bookmarks_warned->removeElement($bookmarksWarned);
            // set the owning side to null (unless already changed)
            if ($bookmarksWarned->getAuthor() === $this) {
                $bookmarksWarned->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Announcement[]
     */
    public function getAnnounces(): Collection
    {
        return $this->announces;
    }

    public function addAnnounce(Announcement $announce): self
    {
        if (!$this->announces->contains($announce)) {
            $this->announces[] = $announce;
            $announce->setAuthor($this);
        }

        return $this;
    }

    public function removeAnnounce(Announcement $announce): self
    {
        if ($this->announces->contains($announce)) {
            $this->announces->removeElement($announce);
            // set the owning side to null (unless already changed)
            if ($announce->getAuthor() === $this) {
                $announce->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setUser($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getUser() === $this) {
                $affectation->setUser(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * Methode qui implémente la classe UserInterface
     **/

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /*
    * TODO function getRoles a changer
     */
    public function getRoles()
    {
        return array('ROLE_TEACHER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }


    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, array('allowed_classes' => false));
    }

}
