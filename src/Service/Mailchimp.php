<?php


namespace SweetSallyBe\Helpers\Service;

use GuzzleHttp\Exception\ClientException;
use MailchimpMarketing\ApiClient;
use SweetSallyBe\Helpers\Model\NewsletterSubscriber;
use SweetSallyBe\Helpers\Service\Helper;
use SweetSallyBe\Helpers\Service\Interfaces\Newsletter;

/**
 * Class Mailchimp
 *
 * @package App\Service
 */
class Mailchimp implements Newsletter
{
    private ApiClient $mailchimpClient;

    public function __construct(ApiClient $mailchimpClient, Helper $helper)
    {
        $config = $helper->getConfig('mailchimp');
        $mailchimpClient->setConfig([
            'apiKey' => $config['main']['apikey'],
            'server' => $config['main']['serverPrefix']
        ]);
        $this->mailchimpClient = $mailchimpClient;
    }

    public function testConnection(): bool
    {
        $response = $this->mailchimpClient->ping->get();
        return ($response->health_status === 'Everything\'s Chimpy!');
    }

    /**
     * @param string|null $list
     *
     * @return NewsletterSubscriber[]
     */
    public function getContacts(?string $list = null): array
    {
        $limit = 10;
        $offset = 0;
        $results = [];
        do {
            $response = $this->mailchimpClient->lists->getListMembersInfo($list, null, null, $limit, $offset);
            if (count($results) < $response->total_items) {
                $offset += count($results);
                $hasNextPage = true;
            } else {
                $hasNextPage = false;
            }
            foreach ($response->members as $row) {
                $contact = $this->responsToNewsletterSubscriber($row);
                $results[] = $contact;
            }
        } while ($hasNextPage);

        return $results;
    }

    public function find(string $email, ?string $list = null): ?NewsletterSubscriber
    {
        try {
            $response = $this->mailchimpClient->lists->getListMember($list, $this->getHashForEmail($email));
            return $this->responsToNewsletterSubscriber($response);
        } catch (ClientException $e) {
            return null;
        }
    }

    public function subscribe(NewsletterSubscriber $contact, ?string $list = null): void
    {
        try {
            $data = ['email_address' => $contact->getEmail(),
                     'double_optin' => false,
                     'update_existing' => true,
                     'status' => 'subscribed',
                     'status_if_new' => 'subscribed',
                     'language' => 'nl',
                     'merge_fields' => ['FNAME' => $contact->getFirstName(), 'LNAME' => $contact->getLastName()]];
            $this->mailchimpClient->lists->setListMember($list, $this->getHashForEmail($contact->getEmail()), $data);
        } catch (ClientException $e) {
            dd(__FILE__ . ' - regel: ' . __LINE__, $e->getMessage());
        }
    }

    public function update(NewsletterSubscriber $contact, ?string $newFirstname = null, ?string $newLastname = null,
        ?string $list = null
    ): void {
        try {
            $this->mailchimpClient->lists->setListMember(
                $list,
                $this->getHashForEmail($contact->getEmail()),
                ['merge_fields' => ['FNAME' => $newFirstname, 'LNAME' => $newLastname]]
            );
        } catch (ClientException $e) {
            dd(__FILE__ . ' - regel: ' . __LINE__, $e);
        }
    }

    public function delete(NewsletterSubscriber $contact, ?string $list = null): void
    {
        try {
            $this->mailchimpClient->lists->deleteListMemberPermanent(
                $list,
                $this->getHashForEmail($contact->getEmail())
            );
        } catch (ClientException $e) {
            dd(__FILE__ . ' - regel: ' . __LINE__, $e->getMessage());
        }

    }

    private function responsToNewsletterSubscriber($response): NewsletterSubscriber
    {
        $contact = new NewsletterSubscriber(
            $response->email_address,
            $response->id,
            $response->merge_fields->FNAME,
            $response->merge_fields->LNAME,
        );
        return $contact;
    }

    private function getHashForEmail(string $email): string
    {
        return md5(strtolower($email));
    }
}