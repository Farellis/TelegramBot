<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ticker = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?int $decimals = null;

    #[ORM\Column(length: 255)]
    private ?string $contract_address = null;

    #[ORM\Column(length: 255)]
    private ?string $supply = null;

    #[ORM\OneToMany(mappedBy: 'token', targetEntity: Position::class, orphanRemoval: true)]
    private Collection $positions;

    #[ORM\Column(nullable: true)]
    private ?float $fdv = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pair_address = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $txns = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $volume = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $price_change = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $liquidity = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?float $price_native = null;

    #[ORM\Column(length: 255)]
    private ?string $network = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $owner_address = null;

    #[ORM\Column(nullable: true)]
    private ?float $market_cap = null;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function setTicker(string $ticker): static
    {
        $this->ticker = $ticker;

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

    public function getDecimals(): ?int
    {
        return $this->decimals;
    }

    public function setDecimals(int $decimals): static
    {
        $this->decimals = $decimals;

        return $this;
    }

    public function getContractAddress(): ?string
    {
        return $this->contract_address;
    }

    public function setContractAddress(string $contract_address): static
    {
        $this->contract_address = $contract_address;

        return $this;
    }

    public function getSupply(): ?string
    {
        return $this->supply;
    }

    public function setSupply(string $supply): static
    {
        $this->supply = $supply;

        return $this;
    }

    /**
     * @return Collection<int, Position>
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(Position $position): static
    {
        if (!$this->positions->contains($position)) {
            $this->positions->add($position);
            $position->setToken($this);
        }

        return $this;
    }

    public function removePosition(Position $position): static
    {
        if ($this->positions->removeElement($position)) {
            // set the owning side to null (unless already changed)
            if ($position->getToken() === $this) {
                $position->setToken(null);
            }
        }

        return $this;
    }

    public function getFdv(): ?float
    {
        return $this->fdv;
    }

    public function setFdv(?float $fdv): static
    {
        $this->fdv = $fdv;

        return $this;
    }

    public function getChart(): ?string
    {
        return $this->chart;
    }

    public function setChart(?string $chart): static
    {
        $this->chart = $chart;

        return $this;
    }

    public function getPairAddress(): ?string
    {
        return $this->pair_address;
    }

    public function setPairAddress(?string $pair_address): static
    {
        $this->pair_address = $pair_address;

        return $this;
    }

    public function getTxns(): ?array
    {
        return $this->txns;
    }

    public function setTxns(?array $txns): static
    {
        $this->txns = $txns;

        return $this;
    }

    public function getVolume(): ?array
    {
        return $this->volume;
    }

    public function setVolume(?array $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getPriceChange(): ?array
    {
        return $this->price_change;
    }

    public function setPriceChange(?array $price_change): static
    {
        $this->price_change = $price_change;

        return $this;
    }

    public function getLiquidity(): ?array
    {
        return $this->liquidity;
    }

    public function setLiquidity(?array $liquidity): static
    {
        $this->liquidity = $liquidity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceNative(): ?float
    {
        return $this->price_native;
    }

    public function setPriceNative(?float $price_native): static
    {
        $this->price_native = $price_native;

        return $this;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function setNetwork(string $network): static
    {
        $this->network = $network;

        return $this;
    }

    public function getOwnerAddress(): ?string
    {
        return $this->owner_address;
    }

    public function setOwnerAddress(?string $owner_address): static
    {
        $this->owner_address = $owner_address;

        return $this;
    }

    public function getMarketCap(): ?float
    {
        return $this->market_cap;
    }

    public function setMarketCap(?float $market_cap): static
    {
        $this->market_cap = $market_cap;

        return $this;
    }
}
