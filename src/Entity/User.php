<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Index(name: 'user', columns: ['telegram_id'])]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'bigint')]
    private ?int $telegram_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Wallet::class, cascade: ['persist'])]
    private Collection $wallets;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $language_code = null;

    #[ORM\Column]
    private ?bool $bot = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $state_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state_param = null;

    #[ORM\Column(nullable: true, type: 'bigint')]
    private ?int $chat_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pincode = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $rules_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $last_message_id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $pincode_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recovery_key = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $language = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'referrals')]
    private ?self $referrer = null;

    #[ORM\OneToMany(mappedBy: 'referrer', targetEntity: self::class)]
    private Collection $referrals;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $referrer_at = null;

    #[ORM\Column(length: 255)]
    private ?string $referral_code = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $security_at = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DepositWallet::class, cascade: ['persist'])]
    private Collection $depositWallets;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $network = null;

    #[ORM\Column]
    private ?float $fees = 0;

    #[ORM\Column(nullable: true)]
    private ?float $slippage_trading = 5;

    #[ORM\Column(nullable: true)]
    private ?float $slippage_sniping = 5;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mode = 'basic';

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Position::class, orphanRemoval: true)]
    private Collection $positions;

    #[ORM\Column(nullable: true)]
    private ?bool $session_enabled = true;

    #[ORM\Column(nullable: true)]
    private ?bool $admin = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $last_reply_id = null;

    public function __construct()
    {
        $this->wallets = new ArrayCollection();
        $this->referrals = new ArrayCollection();
        $this->depositWallets = new ArrayCollection();
        $this->positions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTelegramId(): ?int
    {
        return $this->telegram_id;
    }

    public function setTelegramId(int $telegram_id): static
    {
        $this->telegram_id = $telegram_id;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Wallet>
     */
    public function getWallets($network = null): Collection
    {
        if (is_null($network)) {
            return $this->wallets;
        }

        $wallets = new ArrayCollection();

        foreach ($this->wallets as $wallet) {
            if ($wallet->getNetwork() == $network) {
                $wallets->add($wallet);
            }
        }

        return $wallets;
    }

    public function addWallet(Wallet $wallet): static
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets->add($wallet);
            $wallet->setUser($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): static
    {
        if ($this->wallets->removeElement($wallet)) {
            // set the owning side to null (unless already changed)
            if ($wallet->getUser() === $this) {
                $wallet->setUser(null);
            }
        }

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

    public function getLanguageCode(): ?string
    {
        return $this->language_code;
    }

    public function setLanguageCode(?string $language_code): static
    {
        $this->language_code = $language_code;

        return $this;
    }

    public function isBot(): ?bool
    {
        return $this->bot;
    }

    public function setBot(bool $bot): static
    {
        $this->bot = $bot;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getStateAt(): ?\DateTimeImmutable
    {
        return $this->state_at;
    }

    public function setStateAt(?\DateTimeImmutable $state_at): static
    {
        $this->state_at = $state_at;

        return $this;
    }

    public function getStateParam(): ?string
    {
        return $this->state_param;
    }

    public function setStateParam(?string $state_param): static
    {
        $this->state_param = $state_param;

        return $this;
    }

    public function getChatId(): ?int
    {
        return $this->chat_id;
    }

    public function setChatId(?int $chat_id): static
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getPincode(): ?string
    {
        return $this->pincode;
    }

    public function setPincode(?string $pincode): static
    {
        $this->pincode = $pincode;

        return $this;
    }

    public function getRulesAt(): ?\DateTimeImmutable
    {
        return $this->rules_at;
    }

    public function setRulesAt(?\DateTimeImmutable $rules_at): static
    {
        $this->rules_at = $rules_at;

        return $this;
    }

    public function getLastMessageId(): ?int
    {
        return $this->last_message_id;
    }

    public function setLastMessageId(?int $last_message_id): static
    {
        $this->last_message_id = $last_message_id;

        return $this;
    }

    public function getPincodeAt(): ?\DateTimeImmutable
    {
        return $this->pincode_at;
    }

    public function setPincodeAt(?\DateTimeImmutable $pincode_at): static
    {
        $this->pincode_at = $pincode_at;

        return $this;
    }

    public function getRecoveryKey(): ?string
    {
        return $this->recovery_key;
    }

    public function setRecoveryKey(?string $recovery_key): static
    {
        $this->recovery_key = $recovery_key;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getReferrer(): ?self
    {
        return $this->referrer;
    }

    public function setReferrer(?self $referrer): static
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReferrals(): Collection
    {
        return $this->referrals;
    }

    public function addReferral(self $referral): static
    {
        if (!$this->referrals->contains($referral)) {
            $this->referrals->add($referral);
            $referral->setReferrer($this);
        }

        return $this;
    }

    public function removeReferral(self $referral): static
    {
        if ($this->referrals->removeElement($referral)) {
            // set the owning side to null (unless already changed)
            if ($referral->getReferrer() === $this) {
                $referral->setReferrer(null);
            }
        }

        return $this;
    }

    public function getReferrerAt(): ?\DateTimeImmutable
    {
        return $this->referrer_at;
    }

    public function setReferrerAt(?\DateTimeImmutable $referrer_at): static
    {
        $this->referrer_at = $referrer_at;

        return $this;
    }

    public function getReferralCode(): ?string
    {
        return $this->referral_code;
    }

    public function setReferralCode(string $referral_code): static
    {
        $this->referral_code = $referral_code;

        return $this;
    }

    public function getSecurityAt(): ?\DateTimeImmutable
    {
        return $this->security_at;
    }

    public function setSecurityAt(?\DateTimeImmutable $security_at): static
    {
        $this->security_at = $security_at;

        return $this;
    }

    /**
     * @return Collection<int, DepositWallet>
     */
    public function getDepositWallets(): Collection
    {
        return $this->depositWallets;
    }

    public function addDepositWallet(DepositWallet $depositWallet): static
    {
        if (!$this->depositWallets->contains($depositWallet)) {
            $this->depositWallets->add($depositWallet);
            $depositWallet->setUser($this);
        }

        return $this;
    }

    public function removeDepositWallet(DepositWallet $depositWallet): static
    {
        if ($this->depositWallets->removeElement($depositWallet)) {
            // set the owning side to null (unless already changed)
            if ($depositWallet->getUser() === $this) {
                $depositWallet->setUser(null);
            }
        }

        return $this;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function setNetwork(?string $network): static
    {
        $this->network = $network;

        return $this;
    }

    public function getFees(): ?float
    {
        return $this->fees;
    }

    public function setFees(float $fees): static
    {
        $this->fees = $fees;

        return $this;
    }

    public function getSlippageTrading(): ?float
    {
        return $this->slippage_trading;
    }

    public function setSlippageTrading(?float $slippage_trading): static
    {
        $this->slippage_trading = $slippage_trading;

        return $this;
    }

    public function getSlippageSniping(): ?float
    {
        return $this->slippage_sniping;
    }

    public function setSlippageSniping(?float $slippage_sniping): static
    {
        $this->slippage_sniping = $slippage_sniping;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): static
    {
        $this->mode = $mode;

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
            $position->setUser($this);
        }

        return $this;
    }

    public function removePosition(Position $position): static
    {
        if ($this->positions->removeElement($position)) {
            // set the owning side to null (unless already changed)
            if ($position->getUser() === $this) {
                $position->setUser(null);
            }
        }

        return $this;
    }

    public function isSessionEnabled(): ?bool
    {
        return $this->session_enabled;
    }

    public function setSessionEnabled(?bool $session_enabled): static
    {
        $this->session_enabled = $session_enabled;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(?bool $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    public function getLastReplyId(): ?string
    {
        return $this->last_reply_id;
    }

    public function setLastReplyId(?string $last_reply_id): static
    {
        $this->last_reply_id = $last_reply_id;

        return $this;
    }
}
