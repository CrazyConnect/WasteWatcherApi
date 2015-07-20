Feature: Access to the api

  Background:
    Given the following user:
      | username | plainPassword | roles | enabled |
      | user | user | ROLE_API | true |
    Given I specified the following request http basic credentials:
      | username | user |
      | password | user |

  Scenario: Add list to user
    Given I specified the following request body:
    """
    {
        "name": "list_fridge"
    }
    """
    Given I prepare a POST request on "/api/list"
    When I send the request
    Then print the last response
    Then I should receive a 201 json response

  Scenario: Del list to user
    Given there is 1 itemList like:
      | user | name |
      | user | list_fridge |
    Given I prepare a DELETE request on "/api/list/list_fridge"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response


  Scenario: Get user list
    Given there is 10 itemList like:
    | user |
    | user |
    Given I prepare a GET request on "/api/list"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response
    And scope into the first element
    And the properties exist:
    """
    name
    """

  Scenario: Get user items in a list
    Given there is 1 itemList like:
      | user | name |
      | user | list_fridge |
    Given there is 10 item like:
      | list |
      | list_fridge |
    Given I prepare a GET request on "/api/list/list_fridge/items"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response
    And scope into the first element
    And the properties exist:
    """
    quantity
    """

  Scenario: Post user items in a list
    Given there is 1 itemList like:
      | user | name |
      | user | list_fridge |
    Given there is 10 item like:
      | list |
      | list_fridge |
    Given I specified the following request body:
    """
    {
        "quantity":3,
        "expiration_date":"2015-07-17T00:00:00+0200",
        "creation_date":"2015-07-08T00:00:00+0200",
        "name":"DarkfsfsdfqssdqdSalmon",
        "productId":38
    }
    """
    Given I prepare a POST request on "/api/list/list_fridge/items"
    When I send the request
    Then print the last response
    Then I should receive a 201 json response
