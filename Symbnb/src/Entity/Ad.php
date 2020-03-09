<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * permet d'indiquer des element de vie 
 * @ORM\HasLifecycleCallbacks
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pics", mappedBy="ad", orphanRemoval=true)
     */
    private $pics;

    public function __construct()
    {
        $this->pics = new ArrayCollection();
    }

    /**
    * Let initialise slug 
    *
    * @ORM\PrePersist
    * @ORM\PreUpdate
    *
    *@return void
    */ 

    public function initializeSlug()
    {
        if (empty($this->slug)) 
        {
           $slugify = new Slugify();
           $this->slug = $slugify->slugify($this->title);
        }
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

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

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Pics[]
     */
    public function getPics(): Collection
    {
        return $this->pics;
    }

    public function addPic(Pics $pic): self
    {
        if (!$this->pics->contains($pic)) {
            $this->pics[] = $pic;
            $pic->setAd($this);
        }

        return $this;
    }

    public function removePic(Pics $pic): self
    {
        if ($this->pics->contains($pic)) {
            $this->pics->removeElement($pic);
            // set the owning side to null (unless already changed)
            if ($pic->getAd() === $this) {
                $pic->setAd(null);
            }
        }

        return $this;
    }
}
