<?php
// src/Service/EncryptService.php
namespace App\Service;

use App\Entity\DepositWallet;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Key;
use Psr\Log\LoggerInterface;
use App\Entity\Wallet;

class EncryptService
{

    private $publicKey;
    private $privateKey;
    private const METHOD = 'aes-256-cbc';

    public function __construct(private LoggerInterface $logger)
    {
        $this->publicKey = file_get_contents(__DIR__ . '/../../config/keys/public.pem');
        $this->privateKey = file_get_contents(__DIR__ . '/../../config/keys/private.pem');
    }

    public static function encrypt(string $data): string
    {
        $ivSize = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $encrypted = openssl_encrypt($data, self::METHOD, $_ENV['secret'], 0, $iv);

        return base64_encode($iv . $encrypted);
    }

    public function decrypt($encryptedData)
    {
        try {
            $asciiSafeKey = file_get_contents(__DIR__ . '/../../config/keys/ascii_safe_key.txt');
            $key = Key::loadFromAsciiSafeString($asciiSafeKey);

            return Crypto::decrypt($encryptedData, $key);
        } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $e) {
            $this->logger->error('Failed to decrypt data: ' . $e->getMessage());
            return null;
        } catch (BadFormatException $e) {
            $this->logger->error('BadFormatException: ' . $e->getMessage());
        } catch (EnvironmentIsBrokenException $e) {
            $this->logger->error('EnvironmentIsBrokenException: ' . $e->getMessage());
        }
    }

    public function encryptOpenssl($data)
    {
        openssl_public_encrypt($data, $encrypted, $this->publicKey);
        return base64_encode($encrypted);
    }

    public function decryptOpenssl($data)
    {
        openssl_private_decrypt(base64_decode($data), $decrypted, $this->privateKey);
        return $decrypted;
    }

    public function setEncryptedPrivateKey(string $privateKey, string $salt, string $iv): ?string
    {
        $secretValue = $_ENV['SECRET_VALUE'];
        $key = hash_pbkdf2('sha256', $secretValue, $salt, 1000, 32);
        $encryptedPrivateKey = openssl_encrypt($privateKey, 'aes-256-cbc', $key, 0, hex2bin($iv));

        return $encryptedPrivateKey;
    }

    public function getDecryptedPrivateKey(Wallet|DepositWallet $wallet): ?string
    {
        $secretValue = $_ENV['SECRET_VALUE'];
        $key = hash_pbkdf2('sha256', $secretValue, $wallet->getSalt(), 1000, 32);
        $decryptedPrivateKey = openssl_decrypt($wallet->getPrivateKey(), 'aes-256-cbc', $key, 0, hex2bin($wallet->getIv()));

        return $decryptedPrivateKey;
    }
}
