<?php
namespace Specification\FeeOffice;

use App\FeeOffice\Domain\Event\ApartmentAddedToBuilding;
use App\FeeOffice\Domain\Exception\ApartmentAttributeRequiredException;
use App\FeeOffice\Domain\Exception\UnnecessaryApartmentAttributeException;
use App\FeeOffice\Domain\Model\Apartment\Apartment;
use App\FeeOffice\Domain\Model\Apartment\ApartmentId;
use App\FeeOffice\Domain\Model\Apartment\ApartmentNumber;
use App\FeeOffice\Domain\Model\Apartment\Attribute;
use App\FeeOffice\Domain\Model\Apartment\AttributesCollection;
use App\FeeOffice\Domain\Model\Apartment\AttributeValue;
use App\FeeOffice\Domain\Model\Apartment\AttributeValuesMap;
use App\FeeOffice\Domain\Model\Building\BuildingId;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Specification\Core\AbstractAggregateSpec;

final class AddApartmentToBuildingSpec extends AbstractAggregateSpec implements Context
{
    /**
     * @var ApartmentId
     */
    private $apartmentId;

    /**
     * @var Apartment
     */
    private $apartment;

    /**
     * @var BuildingId
     */
    private $buildingId;

    /**
     * @var int
     */
    private $entranceNumber = 1;

    private $attributes;

    /**
     * @var \Exception
     */
    private $lastException;

    /**
     * AddApartmentToBuildingSpec constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->apartmentId = ApartmentId::generate();
        $this->buildingId = BuildingId::generate();
    }

    /**
     * @Given there is a building with entrances
     */
    public function thereIsABuildingWithEntrances()
    {
    }

    /**
     * @Given There are no attributes
     */
    public function thereAreNoAttributes()
    {
        $this->attributes = new AttributesCollection();
    }

    /**
     * @When an apartment without any attribute is tried to be added to the building
     */
    public function apartmentWithoutAnyAttributeIsAdded()
    {
        $this->apartment = null;
        $this->lastException = null;
        try {
            $this->apartment = Apartment::addToBuilding(
                $this->apartmentId,
                ApartmentNumber::fromString('AN1'),
                $this->buildingId,
                $this->entranceNumber,
                new AttributeValuesMap(),
                $this->attributes
            );

        } catch (\Exception $exception) {
            $this->lastException = $exception;
        }
    }

    /**
     * @Given there are mandatory attributes along with occupied apartment attributes
     */
    public function thereAreMandatoryAttributesAndOccupiedApartmentAttributes()
    {
        $this->attributes = new AttributesCollection(Attribute::area(), Attribute::bodyCount());
    }

    /**
     * @Then it should raise that inadequate attributes are provided to be added to the building
     */
    public function shouldRaiseInadequateAttributes()
    {
        Assert::assertInstanceOf(ApartmentAttributeRequiredException::class, $this->lastException);
    }

    /**
     * @When a vacant apartment with occupied apartment attributes is added to the building
     */
    public function vacantApartmentWithOccupiedApartmentAttributesIsAdded()
    {
        $this->apartment = null;
        $this->lastException = null;
        try {
            $this->apartment = Apartment::addToBuilding(
                $this->apartmentId,
                ApartmentNumber::fromString('AN1'),
                $this->buildingId,
                $this->entranceNumber,
                new AttributeValuesMap(
                    AttributeValue::forAttribute(Attribute::bodyCount(), 2),
                    AttributeValue::forAttribute(Attribute::area(), 1500)
                ),
                $this->attributes
            );

        } catch (\Exception $exception) {
            $this->lastException = $exception;
        }
    }

    /**
     * @Then it should raise that some attributes are unnecessary
     */
    public function shouldRaiseUnnecessaryAttributes()
    {
        Assert::assertInstanceOf(UnnecessaryApartmentAttributeException::class, $this->lastException);
    }

    /**
     * @Then the apartment should be added to the building
     */
    public function apartmentShouldBeAddedToBuilding()
    {
        $events = $this->popRecordedEvents($this->apartment);

        Assert::assertCount(1, $events);
        Assert::assertInstanceOf(ApartmentAddedToBuilding::class, $events[0]);

        Assert::assertEquals($this->apartmentId, $this->apartment->id());
        Assert::assertEquals($this->buildingId, $this->apartment->buildingId());
        Assert::assertEquals($this->entranceNumber, $this->apartment->entranceNumber());
    }
}
