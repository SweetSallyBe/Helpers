<?php

namespace SweetSallyBe\Helpers\Tests\Service;

use SweetSallyBe\Helpers\Model\NewsletterSubscriber;
use SweetSallyBe\Helpers\Service\Helper;
use SweetSallyBe\Helpers\Service\Mailchimp;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailchimpTest extends KernelTestCase
{
    private static ?Mailchimp $mailchimpServiceStatic = null;
    private static ?string $listId = null;
    private static array $stack = [];
    private static int $numberOfContacts = 0;
    private static bool $contactsAreDeleted = false;


    public static function setUpBeforeClass(): void
    {
        self::$mailchimpServiceStatic = self::getContainer()->get(Mailchimp::class);
        for ($i = 1; $i <= 4; $i++) {
            $lastName = uniqid('TEST_USER_' . $i . '_' . time(), true);
            $mail = $lastName . '@sweetsally.be';
            self::$stack[] = new NewsletterSubscriber($mail, null, 'User', $lastName);
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (!self::$contactsAreDeleted) {
            foreach (self::$stack as $subscriber) {
                self::$mailchimpServiceStatic->delete($subscriber, self::$listId);
            }
        }
    }

    /**
     * @test
     */
    public function service()
    {
        $this->assertInstanceOf(Mailchimp::class, self::$mailchimpServiceStatic);
        $this->assertTrue(self::$mailchimpServiceStatic->testConnection());
    }

    /**
     * @test
     */
    public function config(): string
    {
        $helper = $this->getContainer()->get(Helper::class);
        $config = $helper->getConfig('mailchimp');
        $this->assertArrayHasKey('main', $config);
        $this->assertArrayHasKey('apikey', $config['main']);
        $this->assertArrayHasKey('url', $config['main']);
        $this->assertArrayHasKey('serverPrefix', $config['main']);
        $this->assertArrayHasKey('listid', $config['main']);
        self::$listId = $config['main']['listid'];
        return $config['main']['listid'];
    }

    /**
     * @test
     * @depends config
     */
    public function contacts(string $listId): void
    {
        $contacts = self::$mailchimpServiceStatic->getContacts($listId);
        self::$numberOfContacts = count($contacts);
        $this->assertIsArray($contacts);
        $contact = reset($contacts);
        $this->assertInstanceOf(NewsletterSubscriber::class, $contact);
        $this->assertObjectHasAttribute('email', $contact);
    }

    /**
     * @test
     * @depends config
     */
    public function subscribeContact(string $listId): void
    {
        foreach (self::$stack as $contact) {
            self::$mailchimpServiceStatic->subscribe($contact, $listId);
        }
        $newNumberOfContacts = self::$numberOfContacts + count(self::$stack);
        $allContacts = self::$mailchimpServiceStatic->getContacts($listId);
        $this->assertCount($newNumberOfContacts, $allContacts);
    }

    /**
     * @test
     */
    public function findContact(): void
    {
        foreach (self::$stack as $contact) {
            $subscriber = self::$mailchimpServiceStatic->find($contact->getEmail(), self::$listId);
            $this->assertInstanceOf(NewsletterSubscriber::class, $subscriber);
            $this->assertEquals($contact->getFirstName(), $subscriber->getFirstName());
            $this->assertEquals($contact->getLastName(), $subscriber->getLastName());
        }
    }

    /**
     * @testss
     */
    public function updateContact(): void
    {
        foreach (self::$stack as $i => $contact) {
            $originalFirstName = $contact->getFirstName();
            $originalLastName = $contact->getLastName();
            $newFirstname = $originalFirstName . '-' . $i;
            $newLastname = $originalLastName . '-' . $i;
            self::$mailchimpServiceStatic->update($contact, $newFirstname, $newLastname, self::$listId);
            $subscriber = self::$mailchimpServiceStatic->find($contact->getEmail(), self::$listId);
            $this->assertEquals($newFirstname, $subscriber->getFirstName());
            $this->assertEquals($newLastname, $subscriber->getLastName());
        }
    }

    /**
     * @test
     */
    public function deleteContact(): void
    {
        foreach (self::$stack as $contact) {
            self::$mailchimpServiceStatic->delete($contact, self::$listId);
        }
        $allContacts = self::$mailchimpServiceStatic->getContacts(self::$listId);
        $this->assertCount(self::$numberOfContacts, $allContacts);
        self::$contactsAreDeleted = true;
    }

}
