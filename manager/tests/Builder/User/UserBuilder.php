<?php


namespace App\Tests\Builder\User;


use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;

class UserBuilder
{
    protected $id;
    protected $date;

    protected $email;
    protected $hash;
    protected $token;

    protected $confirmed;

    protected $network;
    protected $identity;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('mail@app.test');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';
        return $clone;
    }

    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? 'vk';
        $clone->identity = $identity ?? '0001';
        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function build(): User
    {
        $user = new User(
            $this->id,
            $this->date
        );

        if ($this->email) {
            $user->signUpByEmail(
                $this->email,
                $this->hash,
                $this->token
            );
        }

        if ($this->network) {
            $user->signUpByNetwork(
                $this->network,
                $this->identity
            );
        }

        if ($this->confirmed) {
            $user->confirmSignUp();
        }

        return $user;
    }
}