Feature: Access to the api
  Scenario: List users
    Given there is 10 users
    Given I prepare a GET request on "/api/users"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response
    And scope into the first "data" property
    And the properties exist:
    """
    username
    """

  Scenario: Show an user
    Given the following users:
      | username |
      | joe86    |
    Given I prepare a GET request on "/api/users/joe86"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response
    And scope into the "data" property
    And the properties exist:
    """
    username
    lon
    lat
    """

  Scenario: List users filter in square area
    Given there is 10 users like:
      | lon | lat |
      | 1 | 1 |
    Given I prepare a GET request on "/api/users?lat1=1&lon1=1&lat2=2&lon2=2"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response
    And scope into the first "data" property
    And the properties exist:
    """
    username
    """