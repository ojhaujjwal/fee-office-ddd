<?php
declare(strict_types=1);

namespace App\ContactAdministration\Domain\Event;

use App\ContactAdministration\Domain\Model\BankAccount;
use App\ContactAdministration\Domain\Model\CompanyName;
use App\ContactAdministration\Domain\Model\ContactCardId;
use App\ContactAdministration\Domain\Model\FirstName;
use App\ContactAdministration\Domain\Model\LastName;
use Prooph\EventSourcing\AggregateChanged;

final class ContactCardCreated extends AggregateChanged
{
    public function id(): ContactCardId
    {
        return ContactCardId::fromString($this->aggregateId());
    }

    public function firstName(): FirstName
    {
        return new FirstName($this->payload()['first_name']);
    }

    public function lastName(): LastName
    {
        return new LastName($this->payload()['last_name']);
    }

    public function companyName(): ?CompanyName
    {
        if (!array_key_exists('company_name', $this->payload)) {
            return null;
        }

        return new CompanyName($this->payload()['company_name']);
    }

    public function bankAccount(): ?BankAccount
    {
        if (!array_key_exists('bank_account', $this->payload)) {
            return null;
        }

        $bankAccountDetails = $this->payload()['bank_account'];

        return new BankAccount($bankAccountDetails['swift_code'], $bankAccountDetails['account_number']);
    }
}
