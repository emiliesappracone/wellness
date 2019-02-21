<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="comments")
     */
    private $provider;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\surfer", inversedBy="comments")
     */
    private $surfer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Abuse", mappedBy="comment")
     */
    private $abuses;

    public function __construct()
    {
        $this->abuses = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProvider(): ?provider
    {
        return $this->provider;
    }

    public function setProvider(?provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getSurfer(): ?surfer
    {
        return $this->surfer;
    }

    public function setSurfer(?surfer $surfer): self
    {
        $this->surfer = $surfer;

        return $this;
    }

    /**
     * @return Collection|Abuse[]
     */
    public function getAbuses(): Collection
    {
        return $this->abuses;
    }

    public function addAbuse(Abuse $abuse): self
    {
        if (!$this->abuses->contains($abuse)) {
            $this->abuses[] = $abuse;
            $abuse->setComment($this);
        }

        return $this;
    }

    public function removeAbuse(Abuse $abuse): self
    {
        if ($this->abuses->contains($abuse)) {
            $this->abuses->removeElement($abuse);
            // set the owning side to null (unless already changed)
            if ($abuse->getComment() === $this) {
                $abuse->setComment(null);
            }
        }

        return $this;
    }
}
