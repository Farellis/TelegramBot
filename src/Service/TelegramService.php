<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TelegramService
{

    private $loadingFrame = 0;

    public function __construct(
        private Api $telegram,
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private TranslatorInterface $translator
    )
    {

    }

    /*public function test() {
        $content = [];
        $content['text'] = "test";
        $content['parse_mode'] = 'HTML';
        $content['chat_id'] = 1076057234;
        $this->telegram->sendMessage($content);
    }*/

    public function openReply(User $user, string $message) {
        $content = [];
        $content['text'] = $message;
        $content['chat_id'] = $user->getChatId();
        $content['reply_markup'] = Keyboard::forceReply();
        $ret = $this->telegram->sendMessage($content);

        if (isset($ret['message_id'])) {
            $user->setLastReplyId($ret['message_id']);
            $this->em->persist($user);
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function sendMessage(User $user, $message, Keyboard $keyboard = null, string $header = null, $reply = false)
    {
        $content = [];

        if (!empty($header)) {
            $content['text'] = $header . $message;
        } else {
            $content['text'] = $message;
        }
        $content['parse_mode'] = 'HTML';
        $content['chat_id'] = $user->getChatId();
        $content['disable_web_page_preview'] = true;

        if (!is_null($keyboard)) {
            $content['reply_markup'] = $keyboard;
        }

        if (!is_null($user->getLastMessageId())) {
            $content['message_id'] = $user->getLastMessageId();

            try {
                $ret = $this->telegram->editMessageText($content);
            } catch (\Exception $ex) {
                $this->logger->error('sendMessage ex ' . $ex->getMessage());

                /*if (preg_match('#message is not modified:#', $ex->getMessage())) {
                    return true;
                }*/

                $this->deleteLastMessage($user);

                try {
                    $ret = $this->telegram->sendMessage($content);
                } catch (\Exception $ex) {
                    if (preg_match('#object expected as reply markup#', $ex->getMessage())) {
                        unset($content['reply_markup']);

                        try {
                            $ret = $this->telegram->sendMessage($content);
                        } catch (\Exception $ex) {
                            $this->logger->error('ex 2: ' . $ex->getMessage());
                        }
                    } else {
                        $this->logger->error('ex : ' . $ex->getMessage());
                    }

                    return false;
                }
            }
        } else {
            try {
                $ret = $this->telegram->sendMessage($content);
            } catch (\Exception $ex) {
                $this->logger->error('ex 3: ' . $ex->getMessage());

                return false;
            }
        }

        if (isset($ret['message_id'])) {
            $user->setLastMessageId($ret['message_id']);
            $this->em->persist($user);
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function deleteMessage(int $chatId, $idMessage = null)
    {
        try {
            $this->telegram->deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $idMessage,
            ]);
        } catch (\Exception $ex) {

        }
    }

    public function deleteLastMessage(User $user)
    {
        return $this->deleteMessage($user->getChatId(), $user->getLastMessageId());
    }

    public function deleteLastReply(User $user)
    {
        return $this->deleteMessage($user->getChatId(), $user->getLastReplyId());
    }

    public function initLanguage(User $user)
    {
        // Définir la langue de l'utilisateur comme locale de la requête
        $userLanguage = $user->getLanguage() ?: 'en'; // 'en' comme fallback par défaut
        $this->translator->setLocale($userLanguage);
    }
    public function windowPincodeCheck(User $user)
    {
        $this->initLanguage($user);

        $keyboard = Keyboard::make()->inline()->row([Keyboard::inlineButton(['text' => $this->translator->trans('pincode_lost'), 'callback_data' => 'pincode_forgot'])]);

        return $this->sendMessage($user, $this->translator->trans('pincode_session_expired') . PHP_EOL . PHP_EOL . $this->translator->trans('pincode_write'), $keyboard);
    }

    public function loading(User $user, string $message)
    {
        $this->initLanguage($user);

        $loading = ['🌑', '🌒', '🌓', '🌔', '🌕', '🌖', '🌗', '🌘'];

        if (!isset($loading[$this->loadingFrame])) {
            $this->loadingFrame = 0;
        }

        $loader = $loading[$this->loadingFrame];
        $this->loadingFrame = $this->loadingFrame + 1;


        return $this->sendMessage($user, $message . ' ' . $loader);
    }
}
