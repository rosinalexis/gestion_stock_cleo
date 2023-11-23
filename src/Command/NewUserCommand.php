<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:new-user',
    description: 'Ajouter un nouvelle utilisateur',
)]
class NewUserCommand extends Command
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userRepo = $userRepository;
        $this->userPasswordHasher = $userPasswordHasherInterface;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User email.')
            ->addArgument('password', InputArgument::REQUIRED, 'User plainPassword')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $io = new SymfonyStyle($input, $output);


        if (is_null($email)) {
            $email = $io->ask('user email', 'admin@cloud-rh.fr');
        }

        if (is_null($password)) {
            $password = $io->askHidden('user password ?');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setFirstname('admin');
        $user->setLastname('admin');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $user->setRoles(User::ROLE_ADMIN);
        $this->userRepo->save($user, true);
        return Command::SUCCESS;

    }
}