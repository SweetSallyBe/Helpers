<?php

namespace SweetSallyBe\Helpers\Model;

class NewsletterSubscriber
{
    private ?string $email;
    private ?string $id = '';
    private ?string $firstName = '';
    private ?string $lastName = '';

    public function __construct(string $email, ?string $id = null, ?string $firstName = null, ?string $lastName = null)
    {
        $this->email = $email;
        if ($id) {
            $this->id = $id;
        }
        if ($firstName) {
            $this->firstName = $firstName;
        }
        if ($lastName) {
            $this->lastName = $lastName;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}