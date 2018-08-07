<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnouncementTypeRepository")
 */
class AnnouncementType
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
     * @ORM\OneToMany(targetEntity="App\Entity\Announcement", mappedBy="type")
     */
    private $announces;

    public function __construct()
    {
        $this->announces = new ArrayCollection();
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
            $announce->setType($this);
        }

        return $this;
    }

    public function removeAnnounce(Announcement $announce): self
    {
        if ($this->announces->contains($announce)) {
            $this->announces->removeElement($announce);
            // set the owning side to null (unless already changed)
            if ($announce->getType() === $this) {
                $announce->setType(null);
            }
        }

        return $this;
    }
}
