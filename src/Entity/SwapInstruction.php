<?php

namespace App\Entity;

class SwapInstruction
{
    private string $userWalletAddress;
    private string $raydiumSwapContractAddress;
    private string $sourceWalletAddress;
    private string $destinationWalletAddress;
    private float $amountToSwap;

    public function __construct(string $userWalletAddress, string $raydiumSwapContractAddress, string $sourceWalletAddress, string $destinationWalletAddress, float $amountToSwap)
    {
        $this->userWalletAddress = $userWalletAddress;
        $this->raydiumSwapContractAddress = $raydiumSwapContractAddress;
        $this->sourceWalletAddress = $sourceWalletAddress;
        $this->destinationWalletAddress = $destinationWalletAddress;
        $this->amountToSwap = $amountToSwap;
    }

    public function getUserWalletAddress(): string
    {
        return $this->userWalletAddress;
    }

    public function getRaydiumSwapContractAddress(): string
    {
        return $this->raydiumSwapContractAddress;
    }

    public function getSourceWalletAddress(): string
    {
        return $this->sourceWalletAddress;
    }

    public function getDestinationWalletAddress(): string
    {
        return $this->destinationWalletAddress;
    }

    public function getAmountToSwap(): float
    {
        return $this->amountToSwap;
    }
}
