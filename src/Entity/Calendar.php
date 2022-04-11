<?php

namespace App\Entity;

use App\Repository\CalendarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarRepository::class)
 */
class Calendar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="time")
     */
    private $start;

    /**
     * @ORM\Column(type="time")
     */
    private $end;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $all_day;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $backdround_color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $border_color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $text_color;

    /**
     * [usual=>{from :12:00, to:14:00}, date=>[{date :29-02-2021, from :16:20, to:17:00}], Allday=>{ from :29-02-2021 to 29-02-2021}]
     * @ORM\Column(type="json", nullable=true)
     */
    private $pause=[];

    

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $dayon;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="calendars")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="calendar")
     */
    private $reservations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxSupport;
    public function __construct()
    {
        $this->pause= new ArrayCollection();
        $this->reservations = new ArrayCollection();

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

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAllDay(): ?bool
    {
        return $this->all_day;
    }

    public function setAllDay(?bool $all_day): self
    {
        $this->all_day = $all_day;

        return $this;
    }

    public function getBackdroundColor(): ?string
    {
        return $this->backdround_color;
    }

    public function setBackdroundColor(?string $backdround_color): self
    {
        $this->backdround_color = $backdround_color;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->border_color;
    }

    public function setBorderColor(?string $border_color): self
    {
        $this->border_color = $border_color;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->text_color;
    }

    public function setTextColor(?string $text_color): self
    {
        $this->text_color = $text_color;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPause()
    {
        return $this->pause;
    }

    public function setPause(array $pause): self
    {
        $this->pause=$pause;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setEmployer($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEmployer() === $this) {
                $reservation->setEmployer(null);
            }
        }

        return $this;
    }

  
    public function getDayon(): ?string
    {
        return $this->dayon;
    }

    public function setDayon(string $dayon): self
    {
        $this->dayon = $dayon;

        return $this;
    }

    public function getMaxSupport(): ?int
    {
        return $this->maxSupport;
    }

    public function setMaxSupport(?int $maxSupport): self
    {
        $this->maxSupport = $maxSupport;

        return $this;
    }


}
