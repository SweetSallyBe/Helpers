<?php

namespace SweetSallyBe\Helpers\Entity\Interfaces;

interface IsAbstractEntityInterface
{
    public function getId(): ?int;

    public function getCreatedAt(): ?\DateTimeImmutable;

    public function getCreatedAtFormatted(): string;

    public function setCreatedAt(\DateTimeImmutable $createdAt): self;

    public function getUpdatedAt(): ?\DateTimeImmutable;

    public function getUpdatedAtFormatted(): string;

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self;

    public function setStartValues(array $startValues): self;

    public function toArray(): array;

    public function onPrePersist(): void;

    public function onPreUpdate(): void;
}