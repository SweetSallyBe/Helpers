<?php

namespace SweetSallyBe\Helpers\Service\Interfaces;

use SweetSallyBe\Helpers\Model\NewsletterSubscriber;

interface Newsletter
{
    public function find(string $email, ?string $list = null): ?NewsletterSubscriber;

    public function getContacts(?string $list = null): array;

    public function subscribe(NewsletterSubscriber $contact, ?string $list = null): void;

    public function update(NewsletterSubscriber $contact, ?string $newFirstname = null, ?string $newLastname = null,
        ?string $list = null
    ): void;
}