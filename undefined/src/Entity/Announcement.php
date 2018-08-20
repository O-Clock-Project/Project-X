<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnouncementRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Announcement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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
     */
    private $title;
    
    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="boolean")
     * 
     */
    private $frozen;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $closing_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="announcement", orphanRemoval=true)
     * @MaxDepth(1)
     * 
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="announces")
     * @MaxDepth(1)
     * 
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Promotion", mappedBy="announces")
     * @MaxDepth(1)
     * 
     */
    private $promotions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnnouncementType", inversedBy="announces")
     * @MaxDepth(1)
     */
    private $type;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->frozen = false;
    }

    public function getId()
    {
        return $this->id;
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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getFrozen(): ?bool
    {
        return $this->frozen;
    }

    public function setFrozen(bool $frozen): self
    {
        $this->frozen = $frozen;

        return $this;
    }

    public function getClosingAt(): ?\DateTimeInterface
    {
        return $this->closing_at;
    }

    public function setClosingAt(\DateTimeInterface $closing_at): self
    {
        $this->closing_at = $closing_at;

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
            $comment->setAnnouncement($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAnnouncement() === $this) {
                $comment->setAnnouncement(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->addAnnounce($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->contains($promotion)) {
            $this->promotions->removeElement($promotion);
            $promotion->removeAnnounce($this);
        }

        return $this;
    }

    public function getType(): ?AnnouncementType
    {
        return $this->type;
    }

    public function setType(?AnnouncementType $type): self
    {
        $this->type = $type;

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

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function closingTimestamps()
    {
        $endDate = '+1 month';

        if ($this->getClosingAt() == null) {
            $this->setClosingAt(new \DateTime($endDate));
        }
    }

    public function __toString()
    {
        return $this->getTitle();
    }

}
