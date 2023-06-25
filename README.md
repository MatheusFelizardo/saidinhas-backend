## About
Project created to study backend and frontend in integration with <a href="https://github.com/MatheusFelizardo/saidinhas">this front-end repo</a>.

On this project I used laravel and mysql to practice concepts like routes, api, deployment and database.

Still in development:

<strong>Next backend step</strong>: 

- ~~feature: login (It's already configurated but I will need to make some database updates)~~ Done 21/06/23
- ~~feature: connection between expense and user in the database~~ Done 22/06/23 
- ~~feature: profile picture for the users~~ Done 23/06/23 
- ~~feature: create route to update user infos~~ Done 25/06/23
- feature: category for expenses
- feature: filters (by category, low to high and high to low)
- feature: return data to create the chart comparing the expenses in the last 3 months
- feature: selected currency to the user

## Requirements
- php (tested in the version 8.2)
- composer (tested in version 2.2.9)
- mysql

## How to setup
 - use the .env to configure your database
 - run: composer install && npm install
 - run: php artisan migrate (it will connect to your database config and create the necessary tables )
 - run: php artisan serve

## Routes
  The base url in Laravel usually is localhost:8000
  - GET ${baseUrl}/api/expenses: get all expenses
  - GET ${baseUrl}/api/expenses/{id}: get the expense info to the {id}
  - DELETE ${baseUrl}/api/expenses/{id}: delete the expense {id}
  - POST ${baseUrl}/api/expenses: create a new expense.
    - format: 
    ```JSON 
    {
      "title":"coffee",
      "description":"bought in iLoveCoffee",
      "amount":"1",
      "currency":"EUR"
    } 
    ```
  - PUT ${baseUrl}/api/expenses/{id}: edit the expense {id}, same format to create but you can send just what you want to edit
    - example:
    ```JSON 
    {
      "title":"coke"
    } 
    ```

<br/>
<p>Check the <a href="https://github.com/MatheusFelizardo/saidinhas">Front-end repo</a></p>
