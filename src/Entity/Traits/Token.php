<?php


namespace SweetSallyBe\Helpers\Entity\Traits;


trait Token
{
    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): \SweetSallyBe\Helpers\Entity\Interfaces\Token
    {
        $this->token = $token;

        return $this;
    }

    public function updateToken(): string
    {
        $this->setToken(bin2hex(random_bytes(64)));
        return $this->token;
    }
}