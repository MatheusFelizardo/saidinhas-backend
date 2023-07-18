## About
Project created to study backend and frontend in integration with <a href="https://github.com/MatheusFelizardo/saidinhas">this front-end repo</a>.

On this project I used laravel and mysql to practice concepts like routes, api, deployment and database.

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
  - GET {{ _.base_url }}/api/expenses: get all expenses
  - GET {{ _.base_url }}/api/expenses/{id}: get the expense info to the {id}
  - DELETE {{ _.base_url }}/api/expenses/{id}: delete the expense {id}
  - POST {{ _.base_url }}/api/expenses: create a new expense.
    - format: 
    ```JSON 
    {
      "title":"coffee",
      "description":"bought in iLoveCoffee",
      "amount":"1",
      "currency":"EUR"
    } 
    ```
  - PUT {{ _.base_url }}/api/expenses/{id}: edit the expense {id}, same format to create but you can send just what you want to edit
    - example:
    ```JSON 
    {
      "title":"coke"
    } 
    ```
  - POST {{ _.base_url }}/api/register: create a new user. You can send another parameter "profile_picture" as a file but need to send as form data.
    - format: 
      ```JSON 
      {
        "name":"Matheus Felizardo",
        "email":"matheus.felizardo@gmail.com",
        "password":"123456",
      } 
      ```
  - POST {{ _.base_url }}/api/update: Update the user data. Need to send the id in the request, parameter 'id'
  - DELETE {{ _.base_url }}/api/user/delete/{id}: Delete the user {id}
  - POST {{ _.base_url }}/api/login: Login the user to save the token.
     - format: 
      ```JSON 
        {
          "email":"matheus.felizardo@gmail.com",
          "password":"123456"
        }
      ```
  - POST {{ _.base_url }}/api/logout: Disconnect the logged user. Necessary to send the bearer token.
  - POST {{ _.base_url }}/api/user: Get information of the logged user. Necessary to send the bearer token.
  
## Updates

- ~~feature: login (It's already configurated but I will need to make some database updates)~~ Done 21/06/23
- ~~feature: connection between expense and user in the database~~ Done 22/06/23 
- ~~feature: profile picture for the users~~ Done 23/06/23 
- ~~feature: create route to update user infos~~ Done 25/06/23
- ~~feature: create route to delete the user and associated expenses~~ Done 26/06/23
- feature: category for expenses
- feature: filters (by category, low to high and high to low)
- ~~feature: return data to create the chart comparing the expenses in the last 3 months~~ Done 18/07/2023
- ~~feature: selected currency to the user~~ Done 09/07/2023
- bug: route update user is not working after change method to put or path
- ~~bug: laravel converting empty strings to null~~ Done 30/06/2023
- ~~feature: currency model to simulate a currency api~~ Done 09/07/2023

### Usefull commands
- php artisan migrate:refresh && php artisan db:seed UsersTableSeeder && php artisan db:seed ExpensesTableSeeder: reset the database and seed the tables User and Expenses

<br/>
<p>Check the <a href="https://github.com/MatheusFelizardo/saidinhas">Front-end repo</a></p>
