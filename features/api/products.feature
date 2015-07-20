Feature: Access to the api
  Background:
    Given the following user:
      | username | plainPassword | roles | enabled |
      | user | user | ROLE_API | true |
    Given I specified the following request http basic credentials:
      | username | user |
      | password | user |

  Scenario: List products
    Given there is 10 products
    Given I prepare a GET request on "/api/products"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response
    And scope into the first element
    And the properties exist:
    """
    name
    barcode
    """

  Scenario: List products by barcode
    Given there is 10 products
    Given the following products:
      | barcode |
      | 1234566    |
    Given I prepare a GET request on "/api/products?barcode=1234566"
    When I send the request
    Then print the last response
    Then I should receive a 200 json response
    And scope into the first element
    And the properties exist:
    """
    name
    barcode
    """

  Scenario: post product
    Given there is 10 products
    Given I specified the following request body:
    """
    {
        "barcode": "12345678",
        "name": "steak"
    }
    """
    Given I prepare a POST request on "/api/products"
    When I send the request
    Then print the last response
    Then I should receive a 201 json response