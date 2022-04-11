<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Ads::class, inversedBy="reservations")
     */
    private $ads;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avancement = "En cours de traitement";

    /**
     * @ORM\Column(type="datetime")
     */
    private $deteheure;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaid = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPret = 0;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $motifRefus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeSessionId;

    /**
     * @ORM\ManyToOne(targetEntity=Calendar::class, inversedBy="reservations")
     */
    private $calendar;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBenevolat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAds(): ?Ads
    {
        return $this->ads;
    }

    public function setAds(?Ads $ads): self
    {
        $this->ads = $ads;

        return $this;
    }

    public function getAvancement(): ?string
    {
        return $this->avancement;
    }

    public function setAvancement(string $avancement): self
    {
        $this->avancement = $avancement;

        return $this;
    }

    public function getDeteheure(): ?\DateTimeInterface
    {
        return $this->deteheure;
    }

    public function setDeteheure(\DateTimeInterface $deteheure): self
    {
        $this->deteheure = $deteheure;

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

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function getIsPret(): ?bool
    {
        return $this->isPret;
    }

    public function setIsPret(bool $isPret): self
    {
        $this->isPret = $isPret;

        return $this;
    }

    public function getMotifRefus(): ?string
    {
        return $this->motifRefus;
    }

    public function setMotifRefus(?string $motifRefus): self
    {
        $this->motifRefus = $motifRefus;

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripeSessionId;
    }

    public function setStripeSessionId(?string $stripeSessionId): self
    {
        $this->stripeSessionId = $stripeSessionId;

        return $this;
    }

    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(?Calendar $calendar): self
    {
        $this->calendar = $calendar;

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

    public function getIsBenevolat(): ?bool
    {
        return $this->isBenevolat;
    }

    public function setIsBenevolat(?bool $isBenevolat): self
    {
        $this->isBenevolat = $isBenevolat;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(?int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }
}
