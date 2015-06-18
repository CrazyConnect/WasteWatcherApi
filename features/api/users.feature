Feature: Access to the api

  Scenario: List users
    Given I prepare a GET request on "/api/users"
    When I send the request
    Then I should receive a 200 json response
    And scope into the first "data" property
    Then print the last response
    And the properties exist:
    """
    username
    """
