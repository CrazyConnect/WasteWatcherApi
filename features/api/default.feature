Feature: Access to the api

  Scenario: Get response
    Given I prepare a GET request on "/api"
    When I send the request
    Then I should receive a 200 response