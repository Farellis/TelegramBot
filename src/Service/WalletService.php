<?php
// src/Service/WalletService.php
namespace App\Service;

use App\Entity\User;
use App\Entity\Wallet;
use Tighten\SolanaPhpSdk\Keypair;
use FurqanSiddiqui\BIP39\BIP39;
use Tatum\Sdk;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use kornrunner\Keccak;
use Elliptic\EC;

class WalletService
{
    public $evmNetworks = ['polygon', 'zk_sync', 'scroll', 'arbitrum', 'avalanche'];

    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private EncryptService $encryptService
    )
    {

    }

    public function createWallet(User $user, string $network)
    {
        $keys = $this->generateKeys($network);

        if (!empty($keys)) {
            $now = new \DateTimeImmutable();
            $salt = bin2hex(random_bytes(16));
            $iv = bin2hex(openssl_random_pseudo_bytes(16));

            $wallet = new Wallet();
            $wallet
                ->setUser($user)
                ->setPrivateKey(
                    $this->encryptService->setEncryptedPrivateKey(
                        $keys['private'],
                        $salt,
                        $iv,
                    )
                )
                ->setSalt($salt)
                ->setIv($iv)
                ->setPublicKey($keys['public'])
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setNetwork($network);
            if (isset($keys['mnemonic'])) {
                $wallet->setMnemonic($keys['mnemonic']);
            }

            $this->em->persist($wallet);
            $this->em->flush();

            return $wallet;
        }

        return null;
    }

    public function generateKeys($network)
    {
        $keys = null;

        $this->logger->error('network : ' . $network);

        switch ($network) {
            case 'solana':
                $keys = $this->generateKeysSolana();
                break;
            case 'evm':
                $keys = $this->generateKeysEvm();
                break;
            case 'mvx':
                $keys = $this->generateKeysMvx();
                break;
            default:
                if (in_array($network, $this->evmNetworks)) {
                    $keys = $this->generateKeysEvm();
                }
        }

        return $keys;
    }

    public function generateKeysSolana()
    {
        $keypair = Keypair::generate();
        $private = $keypair->getSecretKey()->toBase58String();
        $public = $keypair->getPublicKey()->toBase58();

        return ['private' => $private, 'public' => $public];
    }

    public function generateKeysEvm()
    {
        $ec = new EC('secp256k1');
        $keyPair = $ec->genKeyPair();
        $privKey = $keyPair->getPrivate('hex');
        $pubKey = $keyPair->getPublic(false, 'hex');
        // Suppression du préfixe '04' qui indique une clé publique non compressée
        $pubKey = substr($pubKey, 2);

        // Hashage Keccak-256 de la clé publique
        $pubKeyHash = Keccak::hash(hex2bin($pubKey), 256);

        // Préparation de l'adresse Ethereum en prenant les 20 derniers octets
        $address = '0x' . substr($pubKeyHash, -40);

            $this->logger->error('private : ' .  $privKey . ' public : '. $address);

        return ['private' => $privKey, 'public' => $address];
    }

    public function generateKeysMvx()
    {
        // Set your API Keys 👇 here
        $sdk = new Sdk();

        // 🐛 Enable debugging on the MainNet
        $sdk->mainnet()->config()->setDebug(true);
        $mnemonic = BIP39::Generate(24)->words;

        try {
            $keys = [];

            /**
             * GET /v3/egld/wallet
             *
             * @var \Tatum\Model\EgldGenerateWallet200Response $response
             */
            $response = $sdk->mainnet()
                ->api()
                ->elrond()
                ->egldGenerateWallet(implode(' ', $mnemonic));

            if (empty($response['mnemonic'])) {
                return null;
            }

            $keys['mnemonic'] = $response['mnemonic'];

            $argPrivKeyRequest = (new \Tatum\Model\PrivKeyRequest())
                // Derivation index of private key to generate.
                ->setIndex(0)

                // Mnemonic to generate private key from.
                ->setMnemonic(implode(' ', $mnemonic));

            /**
             * POST /v3/egld/wallet/priv
             *
             * @var \Tatum\Model\PrivKey $response
             */
            $response = $sdk->mainnet()
                ->api()
                ->elrond()
                ->egldGenerateAddressPrivateKey($argPrivKeyRequest);

            if (empty($response['key'])) {
                return null;
            }

            $keys['private'] = $response['key'];

            /**
             * GET /v3/egld/address/{mnemonic}/{index}
             *
             * @var \Tatum\Model\EgldGenerateAddress200Response $response
             */
            $response = $sdk->mainnet()
                ->api()
                ->elrond()
                ->egldGenerateAddress(implode(' ', $mnemonic), 0);

            if (empty($response['address'])) {
                return null;
            }

            $keys['public'] = $response['address'];

            return $keys;
        } catch (\Tatum\Sdk\ApiException $apiExc) {
            $this->logger->error($apiExc->getMessage());
        } catch (\Exception $exc) {
            $this->logger->error($exc->getMessage());
        }

        return null;
    }
}
