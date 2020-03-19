<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 * permet d'indiquer des element de vie 
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 * fields={"title"},message="An another Ad already have the same title, please modify yours"
 * )
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
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your Title must be at least 10 characters long",
     *      maxMessage = "Your first name cannot be longer than 255 characters")
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
     * @Assert\Length(
     *      min = 20,
     *      minMessage = "Your Introduction must be at least 20 characters long")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 100,
     *      minMessage = "Your Content must be at least 100 characters long")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @assert\Url()
     */
    private $coverImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pics", mappedBy="ad", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $pics;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="ad")
     */
    private $bookings;

    public function __construct()
    {
        $this->pics = new ArrayCollection();
        $this->bookings = new ArrayCollection();
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

    /**
     * Make an array of Accomodation not available days(previous booking)
     *
     * @return array arrays of DateTime for accomodation booked days
     */
    public function getNotAvalaibleDays()
    {
       $notAvailableDays =[];

       foreach ($this->bookings as $booking) {
          // Calcule how many days between check-in and check-out
          $result = range(
            $booking->getStartDate()->getTimestamp(),
            $booking->getEndDate()->getTimestamp(),
            24*60*60*1000
          );
         
          $days = array_map(function($dayTimestamp){
           return new \DateTime(date('Y-m-d', $dayTimestamp)); 
          }, $result);

          $notAvailableDays = array_merge($notAvailableDays, $days);
       }
       return $notAvailableDays;
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
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }
}
