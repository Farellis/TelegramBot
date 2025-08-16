<?php

namespace App\Entity;

use App\Repository\TradeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TradeRepository::class)]
class Trade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'trades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Position $position = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantity = 0;

    #[ORM\Column(nullable: true)]
    private ?float $quantity_native = 0;

    #[ORM\Column(nullable: true)]
    private ?float $unit_price_native = 0;

    #[ORM\Column(length: 255)]
    private ?string $tx = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $checked_at = null;

    #[ORM\Column(nullable: true)]
    private ?bool $tx_result = null;

    #[ORM\Column(nullable: true)]
    private ?float $fees_native = 0;

    #[ORM\Column(nullable: true)]
    private ?float $fees_app = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $action = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantityNative(): ?float
    {
        return $this->quantity_native;
    }

    public function setQuantityNative(float $quantity_native): static
    {
        $this->quantity_native = $quantity_native;

        return $this;
    }

    public function getUnitPriceNative(): ?float
    {
        return $this->unit_price_native;
    }

    public function setUnitPriceNative(float $unit_price_native): static
    {
        $this->unit_price_native = $unit_price_native;

        return $this;
    }

    public function getTx(): ?string
    {
        return $this->tx;
    }

    public function setTx(string $tx): static
    {
        $this->tx = $tx;

        return $this;
    }

    public function getCheckedAt(): ?\DateTimeImmutable
    {
        return $this->checked_at;
    }

    public function setCheckedAt(?\DateTimeImmutable $checked_at): static
    {
        $this->checked_at = $checked_at;

        return $this;
    }

    public function isTxResult(): ?bool
    {
        return $this->tx_result;
    }

    public function setTxResult(?bool $tx_result): static
    {
        $this->tx_result = $tx_result;

        return $this;
    }

    public function getFeesNative(): ?float
    {
        return $this->fees_native;
    }

    public function setFeesNative(?float $fees_native): static
    {
        $this->fees_native = $fees_native;

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

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): static
    {
        $this->action = $action;

        return $this;
    }
}
