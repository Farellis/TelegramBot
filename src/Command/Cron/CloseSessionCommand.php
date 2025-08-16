<?php
// src/Command/Cron/CloseSessionCommand.php
namespace App\Command\Cron;

use App\Entity\User;
use Telegram\Bot\Api;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use App\Repository\UserRepository;
use App\Service\TelegramService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

// the "name" and "description" arguments of AsCommand replace the
// static $defaultName and $defaultDescription properties
#[AsCommand(
        name: 'app:cron:session:close',
        description: 'Close inactive sessions.',
        hidden: false,
        aliases: ['app:cron:session:close']
    )]
class CloseSessionCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
        private Api $telegram,
        private UserRepository $repositoryUser,
        private TelegramService $telegramService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable();
        $users = $this->repositoryUser->findWithInactiveSession();

        /** @var User $user */
        foreach ($users as $user) {
            if (!$user->isSessionEnabled()) {
                continue;
            }

            $user->setState('pincode_check');
            $user->setStateParam(null);
            $user->setStateAt($now);
            $this->em->persist($user);
            $this->em->flush();

            $this->telegramService->windowPincodeCheck($user);
        }

        return Command::SUCCESS;
    }
}
