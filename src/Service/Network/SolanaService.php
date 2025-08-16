<?php
namespace App\Service\Network;

use App\Entity\Token;
use App\Entity\User;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use App\Service\TelegramService;
use Tighten\SolanaPhpSdk\SolanaRpcClient;
use Tighten\SolanaPhpSdk\Keypair;
use Tighten\SolanaPhpSdk\Programs\SystemProgram;
use Tighten\SolanaPhpSdk\PublicKey;
use Tighten\SolanaPhpSdk\Connection;
use Tighten\SolanaPhpSdk\Transaction;
use StephenHill\Base58;

class SolanaService
{

    private Client $client;
    private string $nodeUrl;
    private $maxRetry = 5;

    public function __construct(
        private LoggerInterface $logger,
        private TelegramService $telegramService,
    )
    {
        $this->client = new Client();
        $this->nodeUrl = 'https://rough-distinguished-meme.solana-mainnet.quiknode.pro/71d6e04586b45a8fc40384cee0915f4d026075f0/';
    }

    function getTransaction($transactionId, $retry = 0)
    {
        $body = [
            'json' => [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'getTransaction',
                'params' => [
                    $transactionId,
                    [
                        'encoding' => 'jsonParsed',
                        'maxSupportedTransactionVersion' => 0
                    ]
                ]
            ]
        ];

        try {
            $response = $this->client->request('POST', $this->nodeUrl, $body);
        } catch (\Exception $ex) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getTransaction($transactionId, $retry + 1);
            }

            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['result'])) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getTransaction($transactionId, $retry + 1);
            }

            return null;
        }

        return $data['result'];
    }

    public function getTokensSwapFromTransaction($transaction)
    {
        $signer = $this->findSignerInTransaction($transaction);
        $result = ['sent' => 0, 'received' => 0];

        if (!empty($transaction['meta']['innerInstructions'][1]['instructions'])) {
            $innerInstruction = $transaction['meta']['innerInstructions'][1];

            foreach ($innerInstruction['instructions'] as $instruction) {
                if (empty($instruction['parsed']['type'])) {
                    continue;
                }
                if ($instruction['parsed']['type'] != 'transferChecked') {
                    continue;
                }

                if ($instruction['parsed']['info']['authority'] == $signer) {
                    $result['sent'] = $instruction['parsed']['info']['tokenAmount']['amount'];
                } else {
                    $result['received'] = $instruction['parsed']['info']['tokenAmount']['amount'];
                }
            }
        }

        return $result;
    }

    public function findSignerInTransaction($transaction)
    {
        if (!empty($transaction['transaction']['message']['accountKeys'])) {
            $accounts = $transaction['transaction']['message']['accountKeys'];

            foreach ($accounts as $account) {
                if ($account['signer'] == true) {
                    return $account['pubkey'];
                }
            }
        }

        return null;
    }

    public function getBalance(string $walletAddress, $retry = 0)
    {
        $body = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'getBalance',
            'params' => [
                $walletAddress,
                [
                    'encoding' => 'jsonParsed',
                    'maxSupportedTransactionVersion' => 0
                ]
            ]
        ];

        try {
            $response = $this->client->request('POST', $this->nodeUrl, ['json' => $body]);
        } catch (\Exception $e) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getBalance($walletAddress, $retry + 1);
            }
            return 0;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['result'])) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getBalance($walletAddress, $retry + 1);
            }
        }

        if (!empty($data['result']['value'])) {
            return $data['result']['value'] / pow(10, 9);
        }

        return 0;
    }

    public function isValidWallet(string $walletAddress)
    {
        $accountInfo = $this->getAccountInfo($walletAddress);

        if (empty($accountInfo)) {
            return false;
        }

        if (!empty($accountInfo['data']['program']) && $accountInfo['data']['program'] == 'spl-token') {
            return false;
        }

        return true;
    }

    public function getWalletTokens(string $walletAddress, $retry = 0)
    {
        $tokens = [];

        $userTokens = $this->getTokenAccountsByOwner($walletAddress);

        if (empty($userTokens)) {
            return $tokens;
        }

        foreach ($userTokens as $userToken) {
            if (empty($userToken['account']['data']['parsed']['info']['tokenAmount']['amount'])) {
                continue;
            }

            if (!empty($userToken['account']['data']['program']) && $userToken['account']['data']['program'] == 'spl-token') {
                $tokenAddress = $userToken['account']['data']['parsed']['info']['mint'];
                $tokens[$tokenAddress]['amount'] = $userToken['account']['data']['parsed']['info']['tokenAmount']['amount'];
            }
        }

        return $tokens;
    }

    public function getTokenAccountsByOwner(string $walletAddress, $retry = 0)
    {
        $body = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'getTokenAccountsByOwner',
            'params' => [
                $walletAddress,
                ['programId' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA'],
                [
                    'commitment' => 'finalized',
                    'encoding' => 'jsonParsed'
                ],
            ],
        ];

        try {
            $response = $this->client->request('POST', $this->nodeUrl, ['json' => $body]);
        } catch (\Exception $e) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getTokenAccountsByOwner($walletAddress, $retry + 1);
            }
            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['result'])) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getTokenAccountsByOwner($walletAddress, $retry + 1);
            }
        }

        if (empty($data['result']['value'])) {
            return null;
        }

        return $data['result']['value'];
    }

    public function getAccountInfo(string $walletAddress, $retry = 0)
    {
        $body = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'getAccountInfo',
            'params' => [
                $walletAddress,
                [
                    'encoding' => 'jsonParsed'
                ],
            ],
        ];

        try {
            $response = $this->client->request('POST', $this->nodeUrl, ['json' => $body]);
        } catch (\Exception $e) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getAccountInfo($walletAddress, $retry + 1);
            }
            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['result'])) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getAccountInfo($walletAddress, $retry + 1);
            }
        }

        if (empty($data['result']['value'])) {
            return null;
        }

        return $data['result']['value'];
    }

    public function getTokenInfo(string $contractAddress)
    {
        $data = $this->getAccountInfo($contractAddress);

        if (empty($data)) {
            return false;
        }

        if (empty($data['data']['program']) || $data['data']['program'] !== 'spl-token') {
            return false;
        }

        return $data;
    }

    public function getTokenSupply(string $contractAddress, $retry = 0)
    {
        $body = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'getTokenSupply',
            'params' => [
                $contractAddress,
                ['commitment' => 'finalized',]
            ]
        ];

        try {
            $response = $this->client->request('POST', $this->nodeUrl, ['json' => $body]);
        } catch (\Exception $e) {
            // Gestion des erreurs ou logging
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getTokenSupply($contractAddress, $retry + 1);
            }
            return null;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['result'])) {
            if ($retry < $this->maxRetry) {
                sleep(1);

                return $this->getTokenSupply($contractAddress, $retry + 1);
            }
        }

        if (empty($data['result']['value'])) {
            return null;
        }

        return $data['result']['value']['amount'];
    }

    /**
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function swap(User $user, Token $token, $privateKey, $amount, $action = 'buy', $slippage = 0.5)
    {
        $this->telegramService->sendMessage($user, "Initialisation for swapping SOL to " . $token->getTicker() . '...');

        $command = sprintf(
            'node ../nodeapp/solana.js --wallet %s --action %s --slip ' . $slippage . ' --quantity ' . $amount . ' --contract %s',
            escapeshellarg($privateKey),
            escapeshellarg($action),
            escapeshellarg($token->getContractAddress())
        );

        $this->logger->error('swap : ' . $command);

        $process = Process::fromShellCommandline($command);
        $processResult = false;

        if ($action == 'buy') {
            $this->telegramService->sendMessage($user, "Swapping SOL to " . $token->getTicker() . '...');
        } else {
            $this->telegramService->sendMessage($user, "Swapping " . $token->getTicker() . " to SOL...");
        }
        
        $process->start();

        // Boucle de vérification de l'état du processus
        while ($process->isRunning()) {           
            usleep(100);
        }
        
        $this->telegramService->sendMessage($user, "Swap done...");

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

    public function withdraw(string $recipientPrivateKey, string $recipientAddress, float $amount)
    {
        $rpcClient = new SolanaRpcClient(SolanaRpcClient::MAINNET_ENDPOINT);
        $connection = new Connection($rpcClient);
        $lamportsToSend = $amount * pow(10, 9);

        try {
            $base58 = new Base58();
            $fromPublicKey = Keypair::fromSecretKey($base58->decode($recipientPrivateKey));
            $recipientPublicKey = new PublicKey($recipientAddress);
            $instruction = SystemProgram::transfer(
                    $fromPublicKey->getPublicKey(),
                    $recipientPublicKey,
                    $lamportsToSend
            );

            $transaction = new Transaction(null, null, $fromPublicKey->getPublicKey());
            $transaction->add($instruction);

            $txHash = $connection->sendTransaction($transaction, [$fromPublicKey]);

            $this->logger->error("Transaction envoyée avec succès, signature : $txHash\n");

            return $txHash;
        } catch (\Exception $e) {
            $this->logger->error("Erreur lors de l'envoi de la transaction : " . $e->getMessage());
        }

        return false;
    }
}
