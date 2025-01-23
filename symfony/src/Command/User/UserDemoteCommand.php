<?php

namespace App\Command\User;

use App\Command\User\Trait\UserInteractTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'user:demote',
    description: 'Demote role from a user.',
)]
class UserDemoteCommand extends Command
{
    use UserInteractTrait;

    protected UserRepository $userRepo;

    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepo = $userRepository;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this->setName(self::$defaultName)
            ->setDescription('Demote a user by removing a role')
            ->addArgument('email', InputArgument::REQUIRED, 'The email')
            ->addArgument('role', InputArgument::REQUIRED, 'The new role')
            ->setHelp(
                implode(
                    "\n",
                    [
                        'The <info>user:demote'.
                        '</info> command remove role to a user:',
                        '<info>php %command.full_name% user@test.com</info>',
                        'This interactive shell will first ask you for a role.',
                        'You can alternatively specify the role as a second argument:',
                        '<info>php %command.full_name% user@test.com ROLE_ADMIN</info>',
                    ]
                )
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');
        $user = $this->userRepo->findOneBy(['email' => $email]);

        $roles = $user->getRoles();

        if (!\in_array($role, $roles, true)) {
            $io->error(sprintf('The user %s has not role %s.', $email, $role));

            return 1;
        }

        array_splice($roles, array_search($role, $roles, true), 1);
        $user->setRoles($roles);
        $this->em->flush();
        $io->success(sprintf('The role %s has been removed to user %s.', $role, $email));

        return 0;
    }
}
