<?php
// src/Service/DepositWalletService.php
namespace App\Service;

use App\Entity\DepositWallet;
use App\Entity\User;
use App\Entity\Position;
use App\Repository\DepositWalletRepository;
use App\Service\WalletService;
use App\Service\Network\SolanaService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DepositWalletService
{

    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private WalletService $walletService,
        private SolanaService $solanaService,
        private DepositWalletRepository $depositWalletRepository,
        private EncryptService $encryptService
    )
    {

    }

    public function getOrGenerate(User $user, string $network)
    {
        if (in_array($network, $this->walletService->evmNetworks)) {
            $network = 'evm';
        }

        $depositWallet = $this->depositWalletRepository->findOneBy(['user' => $user, 'network' => $network]);

        if (!empty($depositWallet)) {
            return $depositWallet;
        }

        $keys = $this->walletService->generateKeys($network);

        if (!empty($keys)) {
            $salt = bin2hex(random_bytes(16));
            $iv = bin2hex(openssl_random_pseudo_bytes(16));
            $now = new  \DateTimeImmutable();

            $depositWallet = new DepositWallet();
            $depositWallet
                ->setNetwork($network)
                ->setUser($user)
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setPrivateKey(
                    $this->encryptService->setEncryptedPrivateKey(
                        $keys['private'],
                        $salt,
                        $iv,
                    )
                )
                ->setSalt($salt)
                ->setIv($iv)
                ->setPublicKey($keys['public']);
            if (isset($keys['mnemonic'])) {
                $depositWallet->setMnemonic($keys['mnemonic']);
            }
            $this->em->persist($depositWallet);
            $this->em->flush();

            return $depositWallet;
        }

        return null;
    }

    public function getBalance($depositWallet, $network = null)
    {
        if (is_null($network)) {
            $network = $depositWallet->getNetwork();
        }

        $balance = [
            'ticker' => '',
            'balance' => 0,
            'gas' => 0
        ];

        switch ($network) {
            case 'solana':
                $balance['ticker'] = 'SOL';
                $balance['balance'] = $this->solanaService->getBalance($depositWallet->getPublicKey());
                break;
            case 'avalanche':
                $balance['ticker'] = 'AVAX';
                break;
            case 'polygon':
                $balance['ticker'] = 'MATIC';
                break;
            case 'zk_sync':
            case 'arbitrum':
            case 'scroll':
                $balance['ticker'] = 'ETH';
                break;
            case 'multiversx':
                $balance['ticker'] = 'EGLD';
                break;
        }

        return $balance;
    }

    public function withdraw(DepositWallet $depositWallet, $withdrawAddress, $network = null) {

    }
}
