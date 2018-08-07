<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromotionRepository")
 */
class Promotion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="promotion", orphanRemoval=true)
     */
    private $affectations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Announcement", inversedBy="promotions")
     */
    private $announces;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PromotionLink", mappedBy="promotion", orphanRemoval=true)
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
}
