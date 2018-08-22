<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvitationRepository")
 */
class Invitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $secret_code;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="invitations")
     */
    private $sender;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $created_user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Promotion", inversedBy="invitations")
     */
    private $promotion;

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

    public function getSecretCode(): ?string
    {
        return $this->secret_code;
    }

    public function setSecretCode(string $secret_code): self
    {
        $this->secret_code = $secret_code;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getCreatedUser(): ?User
    {
        return $this->created_user;
    }

    public function setCreatedUser(?User $created_user): self
    {
        $this->created_user = $created_user;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }
}
