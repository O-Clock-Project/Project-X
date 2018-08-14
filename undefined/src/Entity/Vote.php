<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Vote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({ "concise" , "profile"})
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
     * @ORM\Column(type="integer")
     * @Groups({ "concise" })
     */
    private $value;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bookmark", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     * @Groups({ "concise" })
     */
    private $bookmark;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votes")
     * @MaxDepth(1)
     * 
     */
    private $voter;

    public function __construct()
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getBookmark(): ?Bookmark
    {
        return $this->bookmark;
    }

    public function setBookmark(?Bookmark $bookmark): self
    {
        $this->bookmark = $bookmark;

        return $this;
    }

    public function getVoter(): ?User
    {
        return $this->voter;
    }

    public function setVoter(?User $voter): self
    {
        $this->voter = $voter;

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
        return $this->getVoter() . ' a voté ' . strval($this->getValue()) . ' pour ' . $this->getBookmark();
    }

}
