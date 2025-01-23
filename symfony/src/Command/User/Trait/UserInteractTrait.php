<?php

namespace App\Command\User\Trait;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

trait UserInteractTrait
{
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

        if (!$input->getArgument('role')) {
            $question = new Question('Please enter the new role:');
            $question->setValidator(
                function ($role) {
                    if (empty($role)) {
                        throw new \Exception('role can not be empty');
                    }

                    return $role;
                }
            );
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
