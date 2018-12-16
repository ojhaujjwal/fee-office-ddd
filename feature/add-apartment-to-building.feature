Feature: App Apartment
  Scenario: Add apartment to a building with no mandatory attributes
    Given there is a building with entrances
    And There are no attributes
    When an apartment without any attribute is tried to be added to the building
    Then the apartment should be added to the building

  Scenario: Adding apartment without all the required attributes to a building returns error
    Given there is a building with entrances
    And there are mandatory attributes along with occupied apartment attributes
    When an apartment without any attribute is tried to be added to the building
    Then it should raise that inadequate attributes are provided to be added to the building

  Scenario: Adding apartment with attributes specifics to occupied apartments returns error
    Given there is a building with entrances
    And there are mandatory attributes along with occupied apartment attributes
    When a vacant apartment with occupied apartment attributes is added to the building
    Then it should raise that some attributes are unnecessary

