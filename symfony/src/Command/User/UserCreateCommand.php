<?php

namespace App\Command\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'user:create',
    description: 'Create a new user.',
)]
class UserCreateCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepository $userRepository,
        protected UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new user.')
            ->addArgument('email', InputArgument::REQUIRED, 'The email')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
            ->setHelp(
                implode(
                    "\n",
                    [
                        'The <info>user:create</info> command create a user:',
                        '<info>php %command.full_name% user@test.com</info>',
                    ]
                )
            )
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $questions = [];

        if (!$input->getArgument('email')) {
            $question = new Question('Please enter an email:');
            $question->setValidator(
                function ($email) {
                    if (empty($email)) {
                        throw new \Exception('Email can not be empty');
                    }

                    return $email;
                }
            );
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please enter the new password:');
            $question->setValidator(
                function ($password) {
                    if (empty($password)) {
                        throw new \Exception('password can not be empty');
                    }

                    return $password;
                }
            );
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        try {
            $user = (new User())->setEmail($email)
                ->setFirstname('')
                ->setLastname('')
                ->setEnabled(true)
            ;

            $user->setPassword($this->passwordHasher->hashPassword($user, $password));

            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            $io->error($exception->getCode());

            return Command::FAILURE;
        }

        $io->success(sprintf('The user %s was successfully created.', $user->getId()->toString()));

        return Command::SUCCESS;
    }
}
