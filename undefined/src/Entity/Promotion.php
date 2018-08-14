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
 * @ORM\Entity(repositoryClass="App\Repository\PromotionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Promotion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"full", "concise", "profile", "promotion"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"full"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"full"})
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     * @Groups({"full"})
     */
    private $is_active = true;
    
    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"full", "concise", "profile", "promotion"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="promotion", orphanRemoval=true)
     * @MaxDepth(1)
     * @Groups({"full", "promotion"})
     */
    private $affectations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Announcement", inversedBy="promotions")
     * @MaxDepth(1)
     * @Groups({"full", "promotion"})
     */
    private $announces;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PromotionLink", mappedBy="promotion", orphanRemoval=true)
     * @MaxDepth(1)
     * @Groups({"full", "promotion"})
     */
    private $links;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->announces = new ArrayCollection();
        $this->links = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $affectation->setPromotion($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getPromotion() === $this) {
                $affectation->setPromotion(null);
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
        }

        return $this;
    }

    public function removeAnnounce(Announcement $announce): self
    {
        if ($this->announces->contains($announce)) {
            $this->announces->removeElement($announce);
        }

        return $this;
    }

    /**
     * @return Collection|PromotionLink[]
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(PromotionLink $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setPromotion($this);
        }

        return $this;
    }

    public function removeLink(PromotionLink $link): self
    {
        if ($this->links->contains($link)) {
            $this->links->removeElement($link);
            // set the owning side to null (unless already changed)
            if ($link->getPromotion() === $this) {
                $link->setPromotion(null);
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
        return $this->getName();
    }

}
