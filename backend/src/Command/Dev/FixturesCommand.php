<?php

namespace App\Command\Dev;

use App\Document\Session;
use App\Document\User;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:fixtures', description: 'Load fixtures')]
class FixturesCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SessionRepository $sessionRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $document = $this->userRepository->getDocumentManager();
        $document->getSchemaManager()->dropDocumentCollection(User::class);
        $document->getSchemaManager()->dropDocumentCollection(Session::class);


        $user = new User('Username_test', 'test@example.com','');

        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $this->userRepository->save($user);

        $sessions = [
          ['Anglais', '2026-05-01', '09:00', 'Paris', 20],
          ['Français', '2026-05-05', '14:00', 'Lyon', 1],
          ['Espagnol', '2026-05-10', '10:00', 'Marseille', 10],
          ['Allemand', '2026-05-15', '16:00', 'Lille', 5],
          ['Italien', '2026-05-20', '11:00', 'Toulouse', 15],
        ];

        foreach ($sessions as [$language, $date, $hour, $location, $availableSeats]) {
            $session = new Session($language, new \DateTime($date), $hour, $location, $availableSeats);
            $this->sessionRepository->save($session);
        }

        $output->writeln('Fixtures loaded successfully');

        return Command::SUCCESS;

        return 0;
    }
}
