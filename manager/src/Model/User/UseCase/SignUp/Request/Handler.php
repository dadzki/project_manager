<?php

declare(strict_types = 1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\SignUpConfirmTokenizer;
use App\Model\User\Service\ConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;


class Handler
{
    protected $users;
    protected $hasher;
    protected $tokenizer;
    protected $sender;
    protected $flusher;

    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param PasswordHasher $hasher
     * @param SignUpConfirmTokenizer $tokenizer
     * @param ConfirmTokenSender $sender
     * @param Flusher $flusher
     */
    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        SignUpConfirmTokenizer $tokenizer,
        ConfirmTokenSender $sender,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->flusher = $flusher;
    }


    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exist.');
        }

        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate()
        );

        $this->users->add($user);
        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
