<?php
declare(strict_types=1);

namespace App\ContactAdministration\Domain\Model;

use App\ContactAdministration\Domain\Exception\InvalidSwiftCodeException;
use App\Core\Domain\ValueObjectInterface;
use Assert\Assertion;
use Assert\AssertionFailedException;

final class BankAccount implements ValueObjectInterface
{
    /**
     * International bank code that identifies particular banks worldwide
     *
     * @var string
     */
    private $swiftCode;

    /**
     * @var integer
     */
    private $accountNumber;

    public function __construct(string $swiftCode, int $accountNumber)
    {
        $this->validateSwiftCode($swiftCode);
        $this->swiftCode = $swiftCode;
        $this->accountNumber = $accountNumber;
    }

    /**
     * Must be either 8 or 11 characters
     *
     * @param string $swiftCode
     * @return bool
     */
    private function validateSwiftCode(string $swiftCode)
    {
        try {
            Assertion::length($swiftCode, 11);
            return true;
        } catch (AssertionFailedException $exception) {
            try {
                Assertion::length($swiftCode, 8);
                return true;
            } catch (AssertionFailedException $exception) {
                throw new InvalidSwiftCodeException();
            }
        }
    }

    public function sameValueAs($object): bool
    {
        return $object instanceof self && $object->swiftCode == $this->swiftCode && $object->accountNumber == $this->accountNumber;
    }
}
