<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240102213300 extends AbstractMigration implements ContainerAwareInterface
{
    public function getDescription(): string
    {
        return '
        - Ajoute les champs salt et iv dans les entités wallet et deposit wallet
        - Chiffre le code pin et la clé privée des données existantes
        ';
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema): void
    {
        // Ajout de colonnes à l'entité Wallet
        $this->addSql('ALTER TABLE wallet ADD salt VARCHAR(255) DEFAULT NULL, ADD iv VARCHAR(255) DEFAULT NULL');

        $users = $this->connection->fetchAllAssociative('SELECT id, pincode FROM user');
        foreach ($users as $user) {
            if ($user['pincode'] !== null) {
                $pincodeEncrypted = password_hash($user['pincode'], PASSWORD_BCRYPT);

                $this->addSql(
                    'UPDATE user SET pincode = :pincode WHERE id = :id',
                    [
                        'id' => $user['id'],
                        'pincode' => $pincodeEncrypted,
                    ]
                );
            }
        }

        // Ajout de colonnes à l'entité DepositWallet
        $this->addSql('ALTER TABLE deposit_wallet ADD salt VARCHAR(255) DEFAULT NULL, ADD iv VARCHAR(255) DEFAULT NULL');

        // Mise à jour des données existantes
        $wallets = $this->connection->fetchAllAssociative('SELECT id, private_key FROM wallet');
        foreach ($wallets as $wallet) {
            $salt = bin2hex(random_bytes(16));
            $iv = bin2hex(openssl_random_pseudo_bytes(16));
            $encryptedPrivateKey = $this->setEncryptedPrivateKey($wallet['private_key'], $salt, $iv);

            $this->addSql(
                'UPDATE wallet SET private_key = :private_key, salt = :salt, iv = :iv WHERE id = :id',
                [
                    'id' => $wallet['id'],
                    'private_key' => $encryptedPrivateKey,
                    'salt' => $salt,
                    'iv' => $iv,
                ]
            );
        }

        $depositWallets = $this->connection->fetchAllAssociative('SELECT id, private_key FROM deposit_wallet');
        foreach ($depositWallets as $wallet) {
            $salt = bin2hex(random_bytes(16));
            $iv = bin2hex(openssl_random_pseudo_bytes(16));
            $encryptedPrivateKey = $this->setEncryptedPrivateKey($wallet['private_key'], $salt, $iv);

            $this->addSql(
                'UPDATE deposit_wallet SET private_key = :private_key, salt = :salt, iv = :iv WHERE id = :id',
                [
                    'id' => $wallet['id'],
                    'private_key' => $encryptedPrivateKey,
                    'salt' => $salt,
                    'iv' => $iv,
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        // Suppression des colonnes de l'entité Wallet
        $this->addSql('ALTER TABLE wallet DROP salt, DROP iv');

        // Suppression des colonnes de l'entité DepositWallet
        $this->addSql('ALTER TABLE deposit_wallet DROP salt, DROP iv');
    }

    private function setEncryptedPrivateKey(string $privateKey, string $salt, string $ivHex): ?string
    {
        $secretValue = $this->container->getParameter('secretValue');;

        $key = hash_pbkdf2('sha256', $secretValue, $salt, 1000, 32);
        $iv = hex2bin($ivHex);
        $encryptedPrivateKey = openssl_encrypt($privateKey, 'aes-256-cbc', $key, 0, $iv);

        if ($encryptedPrivateKey === false) {
            throw new \RuntimeException('Échec du chiffrement de la clé privée.');
        }

        return $encryptedPrivateKey;
    }
}
