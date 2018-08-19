<?php

namespace App\Entity;

use App\Entity\Locale;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BookmarkRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Bookmark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({ "concise" , "profile", "bookmarks", "bookmark"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     * 
     */
    private $is_active = true;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "concise", "bookmarks", "bookmark" })
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({ "concise", "bookmark" })
     */
    private $resume;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "concise", "bookmarks", "bookmark" })
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({  "bookmark" })
      */
    private $image;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     *
     */
    private $banned;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $published_at;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WarningBookmark", mappedBy="bookmark", orphanRemoval=true)
     * @MaxDepth(1)
     *
     */
    private $warnings;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Support", inversedBy="bookmarks")
     * @MaxDepth(1)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $support;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Difficulty", inversedBy="bookmarks")
     * @MaxDepth(1)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $difficulty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookmarks")
     * @MaxDepth(1)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="bookmark", orphanRemoval=true)
     * @MaxDepth(1)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $votes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="favorites")
     * @ORM\JoinTable(name="bookmark_faved")
     * @MaxDepth(1)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $faved_by;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="certified_bookmarks")
     * @ORM\JoinTable(name="bookmark_certified")
     * @MaxDepth(1)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $certified_by;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="bookmarks")
     * @ORM\JoinTable(name="bookmark_tag",
     * joinColumns={@ORM\JoinColumn(name="bookmark_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     * @MaxDepth(1)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Locale", inversedBy="bookmarks")
     * @MaxDepth(1)
     * @Groups({ "bookmarks", "bookmark" })
     */
    private $locale;


    public function __construct()
    {
        $this->warnings = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->faved_by = new ArrayCollection();
        $this->certified_by = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->banned = false;
    }

    public function getId()
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|WarningBookmark[]
     */
    public function getWarnings(): Collection
    {
        return $this->warnings;
    }

    public function addWarning(WarningBookmark $warning): self
    {
        if (!$this->warnings->contains($warning)) {
            $this->warnings[] = $warning;
            $warning->setBookmark($this);
        }

        return $this;
    }

    public function removeWarning(WarningBookmark $warning): self
    {
        if ($this->warnings->contains($warning)) {
            $this->warnings->removeElement($warning);
            // set the owning side to null (unless already changed)
            if ($warning->getBookmark() === $this) {
                $warning->setBookmark(null);
            }
        }

        return $this;
    }

    public function getSupport(): ?Support
    {
        return $this->support;
    }

    public function setSupport(?Support $support): self
    {
        $this->support = $support;

        return $this;
    }

    public function getDifficulty(): ?Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulty $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $vote->setBookmark($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getBookmark() === $this) {
                $vote->setBookmark(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavedBy(): Collection
    {
        return $this->faved_by;
    }

    public function addFavedBy(User $favedBy): self
    {
        if (!$this->faved_by->contains($favedBy)) {
            $this->faved_by[] = $favedBy;
        }

        return $this;
    }

    public function removeFavedBy(User $favedBy): self
    {
        if ($this->faved_by->contains($favedBy)) {
            $this->faved_by->removeElement($favedBy);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getCertifiedBy(): Collection
    {
        return $this->certified_by;
    }

    public function addCertifiedBy(User $certifiedBy): self
    {
        if (!$this->certified_by->contains($certifiedBy)) {
            $this->certified_by[] = $certifiedBy;
        }

        return $this;
    }

    public function removeCertifiedBy(User $certifiedBy): self
    {
        if ($this->certified_by->contains($certifiedBy)) {
            $this->certified_by->removeElement($certifiedBy);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getLocale(): ?Locale
    {
        return $this->locale;
    }

    public function setLocale(?Locale $locale): self
    {
        $this->locale = $locale;

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
        return $this->getTitle();
    }

}
