<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'positions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'positions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Token $token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?float $initial = 0;

    #[ORM\Column(nullable: true)]
    private ?float $profit_native = 0;

    #[ORM\Column(nullable: true)]
    private ?float $fees_native = 0;

    #[ORM\OneToMany(mappedBy: 'position', targetEntity: Trade::class, orphanRemoval: true)]
    private Collection $trades;

    #[ORM\Column(nullable: true)]
    private ?float $fees_app = 0;

    public function __construct()
    {
        $this->trades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function setToken(?Token $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getInitial(): ?float
    {
        return $this->initial;
    }

    public function setInitial(float $initial): static
    {
        $this->initial = $initial;

        return $this;
    }

    public function getProfitNative(): ?float
    {
        return $this->profit_native;
    }

    public function setProfitNative(float $profit_native): static
    {
        $this->profit_native = $profit_native;

        return $this;
    }

    public function getFeesNative(): ?float
    {
        return $this->fees_native;
    }

    public function setFeesNative(float $fees_native): static
    {
        $this->fees_native = $fees_native;

        return $this;
    }

    /**
     * @return Collection<int, Trade>
     */
    public function getTrades(): Collection
    {
        return $this->trades;
    }

    public function addTrade(Trade $trade): static
    {
        if (!$this->trades->contains($trade)) {
            $this->trades->add($trade);
            $trade->setPosition($this);
        }

        return $this;
    }

    public function removeTrade(Trade $trade): static
    {
        if ($this->trades->removeElement($trade)) {
            // set the owning side to null (unless already changed)
            if ($trade->getPosition() === $this) {
                $trade->setPosition(null);
            }
        }

        return $this;
    }

    public function getFeesApp(): ?float
    {
        return $this->fees_app;
    }

    public function setFeesApp(?float $fees_app): static
    {
        $this->fees_app = $fees_app;

        return $this;
    }
}
