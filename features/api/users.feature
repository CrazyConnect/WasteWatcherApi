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
    Given I prepare a POST request on "/api/users/user/lists"
    When I send the request
    Then print the last response
    Then I should receive a 201 json response

  Scenario: Del list to user
    Given there is 1 itemList like:
      | user | name |
      | user | list_fridge |
    Given I prepare a DELETE request on "/api/users/user/lists/list_fridge"
    When I send the request
    Then print the last response
    Then I should receive a 204 response


  Scenario: Get user list
    Given there is 10 itemList like:
    | user |
    | user |
    Given I prepare a GET request on "/api/users/user/lists"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response
    And scope into the first element
    And the properties exist:
    """
    name
    """