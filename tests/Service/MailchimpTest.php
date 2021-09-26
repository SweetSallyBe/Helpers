<?php

namespace SweetSallyBe\Helpers\Tests\Service;

use MailchimpMarketing\ApiClient;
use SweetSallyBe\Helpers\Model\NewsletterSubscriber;
use SweetSallyBe\Helpers\Service\Helper;
use SweetSallyBe\Helpers\Service\Mailchimp;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MailchimpTest extends KernelTestCase
{
    private static ?Mailchimp $mailchimpService = null;
    private static ?string $listId = null;
    private static array $stack = [];
    private static int $numberOfContacts = 0;
    private static bool $contactsAreDeleted = false;

    public static function setUpBeforeClass(): void
    {
        $container = self::getContainer();
        $helperService = new Helper(
            $container->get(KernelInterface::class),
            $container->get(ParameterBagInterface::class)
        );
        $apiClient = new ApiClient();
        self::$mailchimpService = new Mailchimp($apiClient, $helperService);
        self::$listId = '2a2b75c7f3';
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
                self::$mailchimpService->delete($subscriber, self::$listId);
            }
        }
    }

    /**
     * @test
     */
    public function service()
    {
        $this->assertInstanceOf(Mailchimp::class, self::$mailchimpService);
        $response = self::$mailchimpService->testConnection();
        $this->assertTrue($response);
    }

    /**
     * @test
     */
    public function contacts(): void
    {
        $contacts = self::$mailchimpService->getContacts(self::$listId);
        self::$numberOfContacts = count($contacts);
        $this->assertIsArray($contacts);
        $contact = reset($contacts);
        $this->assertInstanceOf(NewsletterSubscriber::class, $contact);
        $this->assertObjectHasAttribute('email', $contact);
    }

    /**
     * @test
     * @depends contacts
     */
    public function subscribeContact(): void
    {
        foreach (self::$stack as $contact) {
            self::$mailchimpService->subscribe($contact, self::$listId);
        }
        $newNumberOfContacts = self::$numberOfContacts + count(self::$stack);
        $allContacts = self::$mailchimpService->getContacts(self::$listId);
        $this->assertCount($newNumberOfContacts, $allContacts);
    }

    /**
     * @test
     */
    public function findContact(): void
    {
        foreach (self::$stack as $contact) {
            $subscriber = self::$mailchimpService->find($contact->getEmail(), self::$listId);
            $this->assertInstanceOf(NewsletterSubscriber::class, $subscriber);
            $this->assertEquals($contact->getFirstName(), $subscriber->getFirstName());
            $this->assertEquals($contact->getLastName(), $subscriber->getLastName());
        }
    }

    /**
     * @test
     */
    public function updateContact(): void
    {
        foreach (self::$stack as $i => $contact) {
            $originalFirstName = $contact->getFirstName();
            $originalLastName = $contact->getLastName();
            $newFirstname = $originalFirstName . '-' . $i;
            $newLastname = $originalLastName . '-' . $i;
            self::$mailchimpService->update($contact, $newFirstname, $newLastname, self::$listId);
            $subscriber = self::$mailchimpService->find($contact->getEmail(), self::$listId);
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
            self::$mailchimpService->delete($contact, self::$listId);
        }
        $allContacts = self::$mailchimpService->getContacts(self::$listId);
        $this->assertCount(self::$numberOfContacts, $allContacts);
        self::$contactsAreDeleted = true;
    }

}
