<?php


namespace SweetSallyBe\Helpers\Entity\Interfaces;


Interface Token
{
    public function updateToken(): string;

    public function getToken(): ?string;

    public function setToken(string $token): self;
}