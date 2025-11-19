<?php

namespace SweetSallyBe\Helpers\Tests\Model;

use PHPUnit\Framework\TestCase;
use SweetSallyBe\Helpers\Model\NewsletterSubscriber;

class NewsletterSubscriberTest extends TestCase
{
    public function testConstructorSetsAllFields(): void
    {
        $subscriber = new NewsletterSubscriber(
            'test@example.com',
            123,
            'Tim',
            'Brouckaert'
        );

        $this->assertSame('test@example.com', $subscriber->getEmail());
        $this->assertSame('123', $subscriber->getId());
        $this->assertSame('Tim', $subscriber->getFirstName());
        $this->assertSame('Brouckaert', $subscriber->getLastName());
    }

    public function testConstructorWithOnlyEmail(): void
    {
        $subscriber = new NewsletterSubscriber('test@example.com');
        $this->assertSame('test@example.com', $subscriber->getEmail());
        $this->assertSame('', $subscriber->getId());        // default waarde
        $this->assertSame('', $subscriber->getFirstName()); // default waarde
        $this->assertSame('', $subscriber->getLastName());  // default waarde
    }

    public function testConstructorWithPartialData(): void
    {
        $subscriber = new NewsletterSubscriber('test@example.com', null, 'Tim');

        $this->assertSame('test@example.com', $subscriber->getEmail());
        $this->assertSame('', $subscriber->getId());        // id niet meegegeven
        $this->assertSame('Tim', $subscriber->getFirstName());
        $this->assertSame('', $subscriber->getLastName());  // lastName niet meegegeven
    }
}