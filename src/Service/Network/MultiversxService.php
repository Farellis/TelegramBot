<?php
namespace App\Service\Network;

use App\Entity\Token;
use App\Entity\User;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use App\Service\TelegramService;

class MultiversxService
{

    private Client $client;

    public function __construct(
        private LoggerInterface $logger,
        private TelegramService $telegramService,
    )
    {
        $this->client = new Client();
    }

    function getTransaction($transactionId, $retry = 0)
    {
        return null;
    }

    public function getTokensSwapFromTransaction($transaction)
    {
        return null;
    }

    public function findSignerInTransaction($transaction)
    {
        return null;
    }

    public function getBalance(string $walletAddress, $retry = 0)
    {
        return null;
    }

    public function isValidWallet(string $walletAddress)
    {
        return false;
    }

    public function getWalletTokens(string $walletAddress, $retry = 0)
    {
        $tokens = [];

        return $tokens;
    }

    public function getTokenAccountsByOwner(string $walletAddress, $retry = 0)
    {
        return null;
    }

    public function getAccountInfo(string $walletAddress, $retry = 0)
    {
        return null;
    }

    public function getTokenInfo(string $contractAddress)
    {
        return false;
    }

    public function getTokenSupply(string $contractAddress, $retry = 0)
    {
        return null;
    }

    /**
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function swap(User $user, Token $token, $privateKey, $amount, $action = 'buy', $slippage = 0.5)
    {
        //$encryptedPrivateKey = $this->encryptService->encrypt($privateKey);

        $command = sprintf(
            'node ../nodeapp/multiversx.js --wallet %s --action %s --slip ' . $slippage . ' --quantity ' . $amount . ' --contract %s',
            escapeshellarg($privateKey),
            escapeshellarg($action),
            escapeshellarg($token->getContractAddress())
        );

        $this->logger->error('swap : ' . $command);

        $process = Process::fromShellCommandline($command);
        $processResult = false;

        $process->start();

        // Boucle de vérification de l'état du processus
        while ($process->isRunning()) {
            // Ici, vous pouvez envoyer un message périodique ou effectuer une autre action
            if ($action == 'buy') {
                $this->telegramService->loading($user, "Swapping EGLD to " . $token->getTicker());
            } else {
                $this->telegramService->loading($user, "Swapping " . $token->getTicker() . " to EGLD...");
            }

            sleep(1); // Pause d'une seconde
        }

        // Après la fin du processus
        try {
            $processResult = $process->getOutput();
        } catch (\Exception $exception) {
            $this->logger->error('Swap exception : ' . json_encode($exception->getMessage()));
            return false;
        }

        $this->logger->error('Swap ok : ' . json_encode($processResult));

        if (empty($processResult)) {
            return false;
        }

        $json = json_decode($processResult, true);

        if (!$json['result']) {
            return false;
        }

        return $json;
    }

    public function withdraw(string $recipientPrivateKey, string $recipientAddress, float $amount) {
        return false;
    }
}
