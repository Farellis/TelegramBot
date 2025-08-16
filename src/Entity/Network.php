<?php

namespace App\Entity;

use App\Repository\NetworkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NetworkRepository::class)]
class Network
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $disabled = null;

    #[ORM\Column(length: 255)]
    private ?string $rpc_default = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rpc_secondary = null;

    #[ORM\Column(length: 255)]
    private ?string $mint_native = null;

    #[ORM\Column(length: 255)]
    private ?string $ticker_native = null;

    #[ORM\Column]
    private ?int $decimals_native = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function isDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getRpcDefault(): ?string
    {
        return $this->rpc_default;
    }

    public function setRpcDefault(string $rpc_default): static
    {
        $this->rpc_default = $rpc_default;

        return $this;
    }

    public function getRpcSecondary(): ?string
    {
        return $this->rpc_secondary;
    }

    public function setRpcSecondary(?string $rpc_secondary): static
    {
        $this->rpc_secondary = $rpc_secondary;

        return $this;
    }

    public function getMintNative(): ?string
    {
        return $this->mint_native;
    }

    public function setMintNative(string $mint_native): static
    {
        $this->mint_native = $mint_native;

        return $this;
    }

    public function getTickerNative(): ?string
    {
        return $this->ticker_native;
    }

    public function setTickerNative(string $ticker_native): static
    {
        $this->ticker_native = $ticker_native;

        return $this;
    }

    public function getDecimalsNative(): ?int
    {
        return $this->decimals_native;
    }

    public function setDecimalsNative(int $decimals_native): static
    {
        $this->decimals_native = $decimals_native;

        return $this;
    }
}
