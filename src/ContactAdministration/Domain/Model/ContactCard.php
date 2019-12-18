<?php
declare(strict_types=1);

namespace App\ContactAdministration\Domain\Model;

use App\ContactAdministration\Domain\Event\ContactCardCreated;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class ContactCard extends AggregateRoot
{
    /**
     * @var ContactCardId
     */
    private $id;

    /**
     * @var FirstName
     */
    private $firstName;

    /**
     * @var LastName
     */
    private $lastName;

    /**
     * @var CompanyName|null
     */
    private $companyName;

    /**
     * @var BankAccount|null
     */
    private $bankAccount;

    public static function create(ContactCardId $id, array $payload): self
    {
        $contactCard = new self();
        $contactCard->recordThat(ContactCardCreated::occur($id->toString(), $payload));

        return $contactCard;
    }

    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    protected function apply(AggregateChanged $event): void
    {
        if ($event instanceof ContactCardCreated) {
            $this->onContactCardCreated($event);
        }
    }

    private function onContactCardCreated(ContactCardCreated $event): void
    {
        $this->id = $event->id();
        $this->firstName = $event->firstName();
        $this->lastName = $event->lastName();
        $this->companyName = $event->companyName();
        $this->bankAccount = $event->bankAccount();
    }

    /**
     * @return ContactCardId
     */
    public function id(): ContactCardId
    {
        return $this->id;
    }

    /**
     * @return FirstName
     */
    public function firstName(): FirstName
    {
        return $this->firstName;
    }

    /**
     * @return LastName
     */
    public function lastName(): LastName
    {
        return $this->lastName;
    }

    /**
     * @return CompanyName|null
     */
    public function companyName(): ?CompanyName
    {
        return $this->companyName;
    }

    /**
     * @return BankAccount|null
     */
    public function bankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }
}
