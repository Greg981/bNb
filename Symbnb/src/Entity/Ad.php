<?php

namespace App\Entity;



use App\Entity\User;
use App\Entity\Booking;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="ad", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->pics = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     * Allow to get the comment from an author on a specific ad
     *
     * @param User $author
     * @return Comment|null
     */
    public function getCommentFromAuthor(User $author)
    {
        foreach($this->comments as $comment)
        {
            if ($comment->getAuthor() === $author) return $comment; 
        }
        
        return null;
    }

    /**
     * Allow to get Ad average rating
     *
     * @return float
     */
    public function getAvgRatings()
    {   // make sum of all ratings
        $sum = array_reduce($this->comments->toArray(), function($total , $comment){
            return $total + $comment->getRating();
        }, 0);

        // divide sum to get average 
       if(count($this->comments) > 0) return $sum / count($this->comments);

       return 0;
    }

    /**
     * Make an array of Accomodation not available days(previous booking)
     *
     * @return array arrays of DateTime for accomodation booked days
     */
    public function getNotAvailableDays()
    {
       $notAvailableDays =[];

       foreach ($this->bookings as $booking) {
          // Calcule how many days between check-in and check-out
          $result = range(
            $booking->getStartDate()->getTimestamp(),
            $booking->getEndDate()->getTimestamp(),
            24*60*60
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
            $comment->setAd($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAd() === $this) {
                $comment->setAd(null);
            }
        }

        return $this;
    }
}
