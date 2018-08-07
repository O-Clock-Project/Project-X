<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WarningBookmarkRepository")
 */
class WarningBookmark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bookmark", inversedBy="warnings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bookmark;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookmarks_warned")
     */
    private $author;

    public function getId()
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
