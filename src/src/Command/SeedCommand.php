<?php

namespace App\Command;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:seed',
    description: 'Add a short description for your command',
)]
class SeedCommand extends Command
{
    private DocumentManager $dm;
    private UserPasswordHasherInterface $hasher;

    public function __construct(DocumentManager $dm, UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
        $this->dm = $dm;
        $this->hasher = $hasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = 'admin@example.com';
        $plainPassword = 'admin';

        $existingUser = $this->dm->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            $io->warning("El usuario admin ya existe con email: $email");
            return Command::SUCCESS;
        }

        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);

        $this->dm->persist($user);
        $this->dm->flush();

        $io->success([
            'Usuario admin creado correctamente 🎉',
            "Email: $email",
            "Password: $plainPassword",
        ]);

        return Command::SUCCESS;
    }
}
