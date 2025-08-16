<?php
namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\BufferRepository;
use App\Repository\TradeRepository;
use App\Service\UserService;
use App\Service\DepositWalletService;
use App\Service\TelegramService;
use App\Entity\Buffer;
use App\Entity\User;
use App\Entity\DepositWallet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Keyboard\Keyboard;
use ICanBoogie\Inflector;

class TelegramWebhookController extends AbstractController
{

    private const SESSION_DURATION = 3600;
    private const SECURITY_TIME = 600;

    private $authorizedLanguages = ['en', 'fr', 'de', 'es'];
    private $authorizedNetworks = [
        'solana' => [
            'name' => 'Solana',
            'symbol' => 'SOL',
        ],
        'mvx' => [
            'name' => 'MultiversX',
            'symbol' => 'EGLD',
        ],
        'polygon' => [
            'name' => 'Polygon',
            'symbol' => 'MATIC',
        ],
        'zk_sync' => [
            'name' => 'ZkSync Era',
            'symbol' => 'ETH',
        ],
        'scroll' => [
            'name' => 'Scroll',
            'symbol' => 'ETH',
        ],
        'arbitrum' => [
            'name' => 'Arbitrum',
            'symbol' => 'ETH',
        ],
        'avalanche' => [
            'name' => 'Avalanche',
            'symbol' => 'AVAX',
        ],
    ];
    private $securedActions = [];
    private $disabledActions = ['buy_gas', 'buy_gas_select'];
    private $quickActions = [];
    private ?User $user;
    private ?DepositWallet $depositWallet;
    private ?array $depositWalletBalance;
    private $request;
    private ?Keyboard $keyboard;
    private ?\DateTimeImmutable $now;

    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
        private UserRepository $repositoryUser,
        private BufferRepository $repositoryBuffer,
        private TradeRepository $repositoryTrade,
        private UserService $userService,
        private DepositWalletService $depositWalletService,
        private TelegramService $telegramService,
    )
    {
        $this->keyboard = Keyboard::make()->inline();
        $this->now = new \DateTimeImmutable();
    }

    #[Route('/telegram/webhook', name: 'app_telegram_webhook')]
    public function webhook(Request $request): Response
    {
        $this->request = $request;
        $maxSession = $this->now->modify('-' . self::SESSION_DURATION . ' second');
        $maxSecurity = $this->now->modify('-' . self::SECURITY_TIME . ' second');
        $maxBuffer = $this->now->modify('-120 second');
        $action = null;
        $param = null;
        $updatePincodeAt = true;
        $referrerCode = null;
        $chatId = null;

        // Récupérer l'objet de mise à jour
        $update = json_decode($this->request->getContent(), true);
        //$this->logger->error('update : ' . json_encode($update));

        if (empty($update['update_id'])) {
            $this->logger->error('update vide : ' . json_encode($update));

            return new Response('ok');
        }

        $buffer = $this->repositoryBuffer->findOneBy(['update_id' => $update['update_id']]);

        if (!is_null($buffer) && $buffer->getCreatedAt() > $maxBuffer) {
            return new Response('ok');
        }

        $buffer = new Buffer();
        $buffer->setCreatedAt($this->now);
        $buffer->setUpdateId($update['update_id']);
        $this->em->persist($buffer);
        $this->em->flush();

        $userTelegram = null;
        $lastMessageId = null;

        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $userTelegram = $update['message']['from'];

            if (preg_match('#/start #', $update['message']['text'])) {
                $explode = explode(' ', $update['message']['text']);

                if (!empty($explode[1])) {
                    $update['message']['text'] = '/start';
                    $referrerCode = trim($explode[1]);
                }
            }

            $this->telegramService->deleteMessage($chatId, $update['message']['message_id']);
        } elseif (isset($update['callback_query'])) {
            $chatId = $update['callback_query']['message']['chat']['id'];
            $lastMessageId = $update['callback_query']['message']['message_id'];
            $userTelegram = $update['callback_query']['from'];
        } else {
            return new Response('ok');
        }

        if (empty($userTelegram['id']) || $userTelegram['is_bot']) {
            return new Response('ok');
        }

        $this->user = $this->repositoryUser->findOneBy(array('telegram_id' => $userTelegram['id']));

        if ($this->user === null) {
            $this->user = new User();
            $this->user->setTelegramId($userTelegram['id']);
            $this->user->setBot($userTelegram['is_bot']);
            $this->user->setLanguageCode($userTelegram['language_code']);
            $this->user->setCreatedAt($this->now);
            $this->user->setLanguage(in_array($userTelegram['language_code'], $this->authorizedLanguages) ? $userTelegram['language_code'] : 'en');
        }

        if (empty($this->user->getReferralCode())) {
            $referralCode = $this->userService->generateReferralCode();
            $this->user->setReferralCode($referralCode);
        }

        if (!is_null($lastMessageId)) {
            $this->user->setLastMessageId($lastMessageId);
        }

        if (is_null($this->user->getNetwork())) {
            $this->user->setNetwork('solana');
        }

        if (!isset($this->authorizedNetworks[$this->user->getNetwork()])) {
            $this->user->setNetwork('solana');
        }

        if (is_null($this->user->getReferrer()) && !is_null($referrerCode)) {
            $referrer = $this->repositoryUser->findOneBy(array('referral_code' => $referrerCode));

            if (!is_null($referrer) && ($referrer->getId() < $this->user->getId())) {
                $this->user->setReferrer($referrer);
                $this->user->setReferrerAt($this->now);
            }
        }

        $this->user->setChatId($chatId);

        if (!empty($userTelegram['username'])) {
            $this->user->setUsername($userTelegram['username']);
        }

        if (!is_null($this->user->getLastReplyId())) {
            $this->telegramService->deleteLastReply($this->user);
            $this->user->setLastReplyId(null);
        }

        $this->user->setUpdatedAt($this->now);
        $this->em->persist($this->user);
        $this->em->flush();

        $this->depositWallet = $this->depositWalletService->getOrGenerate($this->user, $this->user->getNetwork());
        $this->depositWalletBalance = $this->depositWalletService->getBalance($this->depositWallet, $this->user->getNetwork());

        // Définir la langue de l'utilisateur comme locale de la requête
        $userLanguage = $this->user->getLanguage() ?: 'en'; // 'en' comme fallback par défaut
        $this->translator->setLocale($userLanguage);

        if (is_null($this->user->getRulesAt())) {
            if (isset($update['callback_query']['data'])) {
                $data = $update['callback_query']['data'];

                if (preg_match('#accept_rules:#', $data)) {
                    list($action, $param) = explode(':', $data);
                }
            }
            $updatePincodeAt = false;
            $action = 'accept_rules';
        } elseif (empty($this->user->getPincode())) {
            if (isset($update['message']['text']) && substr($update['message']['text'], 0, 1) !== '/') {
                $param = $update['message']['text'];
            }
            $updatePincodeAt = false;
            $action = 'pincode';
        } elseif ($this->user->isSessionEnabled() && (is_null($this->user->getPincodeAt()) || ($this->user->getPincodeAt() < $maxSession))) {
            if (isset($update['message']['text'])) {
                $param = $update['message']['text'];
            }
            $updatePincodeAt = false;
            $action = 'pincode_check';
        } elseif (isset($update['message']['text'])) {
            $text = trim($update['message']['text']);
            $userState = $this->user->getState();

            if (substr($text, 0, 1) === '/') {
                $action = substr($text, 1);
            } else {
                switch ($userState) {
                    case 'settings_pincode':
                    case 'referrer_add':
                        $action = $this->user->getState();
                        $param = $text;
                        break;
                    case 'trading_withdraw':
                        $lastParam = $this->user->getStateParam();
                        $action = $this->user->getState();

                        if (!is_null($lastParam)) {
                            if (preg_match('#|#', $lastParam)) {
                                list($lastParam) = explode('|', $lastParam);
                            }

                            $this->actionTradingWithdraw($lastParam, $text);

                            return new Response('ok');
                        } else {
                            $param = $text;
                        }
                        break;
                    case 'pincode_check':
                        $action = 'start';
                        $param = null;
                        break;
                    default:
                        if (in_array($userState, $this->securedActions)) {
                            $this->securityBeforeAction($text);
                            return new Response('ok');
                        } else {
                            return new Response('ok');
                        }
                }
            }
        } elseif (isset($update['callback_query']['data'])) {
            $data = $update['callback_query']['data'];

            if (preg_match('#:#', $data)) {
                list($action, $param) = explode(':', $data);
            } else {
                $action = $data;
            }
        }

        if (!is_null($action)) {           
            if ($this->user->isBot()) {
                $this->botDisabled();

                return new Response('ok');
            }

            if (!empty($this->quickActions[$action])) {
                $action = $this->quickActions[$action];
            }

            if (in_array($action, $this->disabledActions)) {
                $this->disabledAction();
                return new Response('ok');
            }

            if (!is_null($param)) {
                $param = trim($param);
            }

            $inflector = Inflector::get();
            $function = $inflector->camelize('action_' . $action, Inflector::DOWNCASE_FIRST_LETTER);

            if (!method_exists($this, $function)) {
                $this->soon();

                return new Response('ok');
            }

            if ($updatePincodeAt) {
                $this->user->setPincodeAt($this->now);
            }

            $this->user->setState($action);
            $this->user->setStateParam($param);
            $this->user->setStateAt($this->now);
            $this->em->persist($this->user);
            $this->em->flush();

            if (in_array($action, $this->securedActions) && $this->user->getSecurityAt() < $maxSecurity) {
                $this->securityBeforeAction();

                return new Response('ok');
            }
            //$this->logger->error('function : ' . $function);
            $this->$function($param);
        }

        return new Response('ok');
    }

    public function actionAcceptRules($param = null)
    {
        if (!is_null($param)) {
            if ($param == 1) {
                $this->user->setRulesAt($this->now);
                $this->em->persist($this->user);
                $this->em->flush();

                $message = $this->translator->trans('rules_accepted') . PHP_EOL . PHP_EOL;
                $message .= $this->translator->trans('pincode_write');

                return $this->sendMessage($message, false);
            }

            return $this->telegramService->deleteLastMessage($this->user);
        }

        $this->keyboard->row([
            Keyboard::inlineButton(['text' => $this->translator->trans('button_accept'), 'callback_data' => 'accept_rules:1']),
            Keyboard::inlineButton(['text' => $this->translator->trans('button_refuse'), 'callback_data' => 'accept_rules:0'])
        ]);

        $message = $this->translator->trans('rules_accept_title') . PHP_EOL . PHP_EOL;
        $message .= $this->translator->trans('rules_accept_content');

        return $this->sendMessage($message);
    }

    public function actionPincodeCheck($pincode = null)
    {
        if (!is_null($pincode)) {
            if (password_verify($pincode, $this->user->getPincode())) {
                $this->user->setPincodeAt($this->now);
                $this->user->setSecurityAt($this->now);
                $this->user->setState('start');
                $this->user->setStateParam('');
                $this->em->persist($this->user);
                $this->em->flush();

                return $this->actionStart();
            } else {
                $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('pincode_lost'), 'callback_data' => 'pincode_forgot'])]);

                return $this->sendMessage($this->translator->trans('pincode_wrong') . PHP_EOL . PHP_EOL . $this->translator->trans('pincode_write'), true, false);
            }
        }

        return $this->telegramService->windowPincodeCheck($this->user);
    }

    public function actionPincode($pincode = null)
    {

        if (!is_null($pincode)) {
            if (!empty($pincode) && strlen($pincode) > 4) {
                $this->user->setPincode(password_hash($pincode, PASSWORD_BCRYPT));
                $this->user->setPincodeAt($this->now);
                $this->em->persist($this->user);
                $this->em->flush();

                $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_start_bot'), 'callback_data' => 'start'])]);

                $message = $this->translator->trans('pincode_ready_title') . PHP_EOL;
                $message .= $this->translator->trans('pincode_ready_content') . PHP_EOL . PHP_EOL;

                return $this->sendMessage($message);
            } else {
                return $this->sendMessage($this->translator->trans('pincode_set'), false, false, true);
            }
        }

        return $this->sendMessage($this->translator->trans('pincode_set'), false, false, true);
    }

    public function actionChangeNetwork($network = null)
    {
        if (!is_null($network) && isset($this->authorizedNetworks[$network])) {
            $this->user->setNetwork($network);
            $this->em->persist($this->user);
            $this->em->flush();

            $this->depositWallet = $this->depositWalletService->getOrGenerate($this->user, $network);
            $this->depositWalletBalance = $this->depositWalletService->getBalance($this->depositWallet, $this->user->getNetwork());

            $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

            return $this->sendMessage('You just changed your network');
        }

        $this->keyboard
            ->row([
                Keyboard::inlineButton(['text' => 'Solana', 'callback_data' => 'change_network:solana']),
                Keyboard::inlineButton(['text' => 'MultiversX', 'callback_data' => 'change_network:mvx']),
                Keyboard::inlineButton(['text' => 'Avalanche', 'callback_data' => 'change_network:avalanche']),
            ])
            ->row([
                Keyboard::inlineButton(['text' => 'Arbitrum', 'callback_data' => 'change_network:arbitrum']),
                Keyboard::inlineButton(['text' => 'ZK Sync Era', 'callback_data' => 'change_network:zk_sync']),
                Keyboard::inlineButton(['text' => 'Scroll', 'callback_data' => 'change_network:scroll']),
            ])
            ->row([
                Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start']),
        ]);

        return $this->sendMessage('Change Network');
    }

    public function actionHelp($param = null)
    {
        //$this->uniswapSwapService->swap("", "");
        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

        $message = $this->translator->trans('help_title') . PHP_EOL . PHP_EOL;
        $message .= $this->translator->trans('help_content');

        return $this->sendMessage($message);
    }

    public function actionStart($param = null)
    {
        $this->keyboard
            ->row([
                Keyboard::inlineButton(['text' => 'Change network', 'callback_data' => 'change_network']),
            ])
            ->row([
                Keyboard::inlineButton(['text' => $this->translator->trans('button_settings'), 'callback_data' => 'settings']),
                Keyboard::inlineButton(['text' => $this->translator->trans('button_help'), 'callback_data' => 'help']),
                Keyboard::inlineButton(['text' => $this->translator->trans('button_referrals'), 'callback_data' => 'referrals']),
        ]);

        if ($this->user->isAdmin()) {
            $this->keyboard->row([
                Keyboard::inlineButton(['text' => 'Admin', 'callback_data' => 'admin'])
            ]);
        }

        return $this->sendMessage($this->translator->trans('welcome_action'));
    }
    /*
     * REFERRAL
     */

    public function actionReferrals($param = null)
    {
        if (is_null($this->user->getReferrer())) {
            $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_add_referrer'), 'callback_data' => 'referrer_add'])]);
        }

        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

        $message = $this->translator->trans('referrals_title');
        $message .= $this->translator->trans('referrals_content',
            [
                '%referral_code%' => $this->user->getReferralCode(),
                '%nb_referrals%' => sizeof($this->user->getReferrals()),
            ]
        );

        return $this->sendMessage($message);
    }

    public function actionReferrerAdd($param = null)
    {
        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_referrals'), 'callback_data' => 'referrals'])]);
        $message = "";

        if (!is_null($param)) {
            $referrer = $this->repositoryUser->findOneBy(array('referral_code' => $param));

            if (!is_null($referrer) && ($referrer->getId() < $this->user->getId())) {
                $this->user->setReferrer($referrer);
                $this->user->setReferrerAt($this->now);
                $this->em->persist($this->user);
                $this->em->flush();

                return $this->sendMessage($this->translator->trans('referrer_add_ok'));
            }

            $message .= $this->translator->trans('referrer_add_ko') . PHP_EOL;
        }

        $message .= $this->translator->trans('referrer_add');

        return $this->sendMessage($message);
    }

    /**
     * SETTINGS
     */
    public function actionSettings($param = null)
    {
         $this->keyboard->row([
                Keyboard::inlineButton(['text' => $this->translator->trans('button_settings_slippage'), 'callback_data' => 'settings_slippage']),
                Keyboard::inlineButton(['text' => $this->translator->trans('button_settings_language'), 'callback_data' => 'settings_language']),
            ])
            ->row([
                Keyboard::inlineButton(['text' => 'Set mode ' . ($this->user->getMode() === 'pro' ? 'lite' : 'pro'), 'callback_data' => 'settings_mode']),
                Keyboard::inlineButton(['text' => $this->translator->trans('button_settings_security'), 'callback_data' => 'settings_security']),
            ])
            ->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

        return $this->sendMessage($this->translator->trans('settings_title'));
    }

    public function actionSettingsLanguage($param = null)
    {
        if (!is_null($param)) {
            if (in_array($param, $this->authorizedLanguages)) {
                $this->user->setLanguage($param);
                $this->em->persist($this->user);
                $this->em->flush();

                $this->translator->setLocale($param);
                $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

                return $this->sendMessage($this->translator->trans('settings_language_updated'));
            }
        }

        $this->keyboard->row([
                Keyboard::inlineButton(['text' => 'English', 'callback_data' => 'settings_language:en']),
                Keyboard::inlineButton(['text' => 'Français', 'callback_data' => 'settings_language:fr']),
            ])
            ->row([
                Keyboard::inlineButton(['text' => 'Deutsch', 'callback_data' => 'settings_language:de']),
                Keyboard::inlineButton(['text' => 'Español', 'callback_data' => 'settings_language:es']),
            ])
            ->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

        return $this->sendMessage($this->translator->trans('settings_language_choose'));
    }

    public function actionSettingsSecurity($param = null)
    {
        $rowKeyboard = [
            Keyboard::inlineButton(['text' => $this->translator->trans('settings_security_pincode'), 'callback_data' => 'settings_pincode']),
        ];

        if ($this->user->isSessionEnabled()) {
            $rowKeyboard[] = Keyboard::inlineButton(['text' => "Disabled session", 'callback_data' => 'settings_session:0']);
        } else {
            $rowKeyboard[] = Keyboard::inlineButton(['text' => "Enabled session", 'callback_data' => 'settings_session:1']);
        }

        $this->keyboard->row($rowKeyboard)
            /*->row([
                Keyboard::inlineButton(['text' => $this->translator->trans('settings_security_recovery_sheet'), 'callback_data' => 'settings_recovery_sheet']),
            ])*/
            ->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

        return $this->sendMessage($this->translator->trans('settings_security_title'), true, false);
    }

    public function actionSettingsMode($param = null) {
        if ($this->user->getMode() == 'pro') {
            $this->user->setMode('lite');
        } else {
            $this->user->setMode('pro');
        }

        $this->em->persist($this->user);
        $this->em->flush();

        $this->keyboard
            /*->row([
                Keyboard::inlineButton(['text' => $this->translator->trans('settings_security_recovery_sheet'), 'callback_data' => 'settings_recovery_sheet']),
            ])*/
            ->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

        return $this->sendMessage('You just changed your mode in <b>' . $this->user->getMode() . '</b> !');
    }

    public function actionSettingsSession($enabled = null)
    {
        if (is_null($enabled)) {
            return $this->actionSettingsSecurity();
        }

        $this->user->setSessionEnabled($enabled);
        $this->em->persist($this->user);
        $this->em->flush();

        return $this->actionSettingsSecurity();
    }

    public function actionSettingsPincode($pincode = null)
    {
        if (!is_null($pincode)) {

            if (!empty($pincode) && strlen($pincode) < 5) {
                return $this->telegramService->openReply($this->user, "Error. Please write your new pincode (5 characters min) :");
            }

            $this->user->setPincode(password_hash($pincode, PASSWORD_BCRYPT));
            $this->em->persist($this->user);
            $this->em->flush();

            $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings_security'), 'callback_data' => 'settings_security'])]);

            return $this->sendMessage("Your pincode is updated !");
        }

        return $this->telegramService->openReply($this->user, "Please write your new pincode (5 characters min) :");
    }

    public function actionSettingsSlippage($param = null)
    {
        $this->keyboard->row([
                Keyboard::inlineButton(['text' => $this->translator->trans('settings_slippage_trading'), 'callback_data' => 'settings_slippage_trading']),
                Keyboard::inlineButton(['text' => $this->translator->trans('settings_slippage_sniping'), 'callback_data' => 'settings_slippage_sniping']),
            ])
            ->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

        return $this->sendMessage("Your slippage :\nTrading: <b>" . $this->user->getSlippageTrading() . "%</b>\nSniping: <b>" . $this->user->getSlippageSniping() . "%</b>\n\n");
    }

    public function actionSettingsSlippageTrading($slippage = null)
    {
        if (!is_null($slippage)) {
            $slippage = str_replace(',', '.', $slippage);
            $slippage = (float) $slippage;

            if ($slippage <= 0 || $slippage >= 100) {
                $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

                return $this->sendMessage("Your slippages :\n\nTrading: <b>" . $this->user->getSlippageTrading() . "%</b>\n\nSniping: <b>" . $this->user->getSlippageSniping() . "%</b>\n\nPlease write a number between 0 and 100 in order to update your slippage :");
            }

            $this->user->setSlippageTrading($slippage);
            $this->em->persist($this->user);
            $this->em->flush();

            $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

            return $this->sendMessage("Your new slippage for trading is <b>" . $slippage . "% !</b>");
        }

        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

        return $this->sendMessage("Your slippages :\n\nTrading: <b>" . $this->user->getSlippageTrading() . "%</b>\n\nSniping: <b>" . $this->user->getSlippageSniping() . "%</b>\n\nPlease write a number between 0 and 100 for your new slippage for trading :");
    }

    public function actionSettingsSlippageSniping($slippage = null)
    {
        if (!is_null($slippage)) {
            $slippage = (float) $slippage;

            if ($slippage <= 0 || $slippage >= 100) {
                $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

                return $this->sendMessage("Your slippages :\n\nTrading: <b>" . $this->user->getSlippageTrading() . "%</b>\n\nSniping: <b>" . $this->user->getSlippageSniping() . "%</b>\n\nPlease write a number between 0 and 100 foryour new slippage for trading :");
            }

            $this->user->setSlippageSniping($slippage);
            $this->em->persist($this->user);
            $this->em->flush();

            $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

            return $this->sendMessage("Your new slippage for sniping is <b>" . $slippage . "% !</b>");
        }

        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_settings'), 'callback_data' => 'settings'])]);

        return $this->sendMessage("Your slippages :\n\nTrading: <b>" . $this->user->getSlippageTrading() . "%</b>\n\nSniping: <b>" . $this->user->getSlippageSniping() . "%</b>\n\nPlease write a number between 0 and 100 for your new slippage for trading :");
    }

    /**
     * ADMIN
     */

    public function actionAdmin($param = null) {

        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

        $nbUsers = $this->repositoryUser->count(['bot' => false]);
        $nbTrades = $this->repositoryTrade->count(['tx_result' => true]);
        $volume = $this->repositoryTrade->getVolumeNative('solana');
        $totalFeesApp = $this->repositoryTrade->getSumOfFeesApp('solana');

        return $this->sendMessage("Users: <b>" . $nbUsers . "</b>\nTrades: <b>" . $nbTrades . "</b>\nVolume: <b>" . $volume . " SOL</b>\nFees App: <b>" . $totalFeesApp . " SOL</b>\n");
    }

    /**
     * TELEGRAM METHODS
     */
    public function getHeaderMessage()
    {
        $message = "Network: <b>" . $this->authorizedNetworks[$this->user->getNetwork()]['name'] . "</b>\n\n";
        $message .= "Deposit Wallet: <code>" . $this->depositWallet->getPublicKey() . "</code>\n";
        $message .= "Balance: " . round($this->depositWalletBalance['balance'], 3) . " " . $this->depositWalletBalance['ticker'] . "\n\n";
        //$message .= "Zen gas : " . $depositWalletBalance['gas'] . " ZBC\n\n";

        return $message;
    }

    public function getTradingHeaderMessage()
    {
        $message = "Trading slippage: <b>" . $this->user->getSlippageTrading() . "%</b>\n\n";

        return $message;
    }

    public function sendMessage($message, $addKeyboard = true, $addHeader = true, $reply = false)
    {
        $header = null;
        $keyboard = null;

        if ($addHeader) {
            $header = $this->getHeaderMessage();
        }

        if ($addKeyboard) {
            $keyboard = $this->keyboard;
        }

        return $this->telegramService->sendMessage($this->user, $message, $keyboard, $header, $reply);
    }

    public function botDisabled()
    {
        return $this->sendMessage("The bot is in development, please follow us on X @zenbot_io.\n\nThank you", false, false);
    }

    public function soon()
    {
        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

        return $this->sendMessage($this->translator->trans('coming_soon'), true, false);
    }

    public function disabledAction()
    {
        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

        return $this->sendMessage('This action is disabled for the moment...', true, false);
    }

    public function securityBeforeAction($pincode = null)
    {
        if (!is_null($pincode)) {
            if (password_verify($pincode, $this->user->getPincode())) {
                $this->user->setPincodeAt($this->now);
                $this->user->setSecurityAt($this->now);
                $this->em->persist($this->user);
                $this->em->flush();

                $action = $this->user->getState();
                $param = $this->user->getStateParam();

                $inflector = Inflector::get();
                $function = $inflector->camelize('action_' . $action, Inflector::DOWNCASE_FIRST_LETTER);

                if (!method_exists($this, $function)) {
                    $this->soon();

                    return true;
                }

                return $this->$function($param);
            }

            $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

            return $this->sendMessage('Wrong pincode... This action required a security check, please write your pincode to continue:');
        }

        $this->keyboard->row([Keyboard::inlineButton(['text' => $this->translator->trans('button_return_start'), 'callback_data' => 'start'])]);

        return $this->sendMessage('This action required a security check, please write your pincode to continue:');
    }
}
