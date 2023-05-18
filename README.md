<h1>IstEscape</h1>

## About This Project

This project is a challenge given to me by a company I'm applying for. It was a fun test of my knowledge in backend development and creating a RESTful API with moderately complex DB schemas. The idea is an escape room with an authentication system that allows the user to see all escape rooms and their timeslots, create bookings for a specific date and timeslot, and delete it if needed. There's a lot more to it including a seeder and about 11 tests.

## How to Set Up

Setting this up is pretty simple and just like any project. I will assume you know how to use homestead, valet, or whatever to run this project on your local machine.

1. git clone into your projects directory
2. create 2 databases, first is the main one for the api requests, and the second one is for the tests
3. set up the .env and .env.testing with corresponding DB data
4. run `php artisan migrate:fresh --seed` to build the main database and populate the data using the seeder

And that should be it. You have a fully functioning project now.

## How to make the API requests

I have personally chosen Postman as my API dev platform, but you can use whatever. Here are the requests you can make with the correct body parameters:

**Auth:**

1. Register:
    - Method: POST
    - URL: istescape.test/api/register
    - Description: Register a new user into the system
    - Body data example:
        - name: Jane Doe
        - email: janedoe@gmail.com
        - password: password
        - dob: 1994/04/02
2. Log In:
    - Method: POST
    - URL: istescape.test/api/login
    - Description: Log in into the system as an existing user
    - Body data example:
        - email: janedoe@gmail.com
        - password: password
3. Log Out:
    - Method: POST
    - URL: istescape.test/api/logout
    - Description: Log out from the current user and delete token
    - Authorization necessary (Bearer token)

**Escape Rooms:**

4. Retrieve All Escape Rooms:
    - Method: GET
    - URL: istescape.test/api/escape-rooms
    - Description: View all Escape Rooms in the system

5. Show a Specific Escape Room
    - Method: GET
    - URL: istescape.test/api/escape-rooms/{id}
    - Description: Show details about a specific escape room

6. Show a Specific Escape Room
    - Method: GET
    - URL: istescape.test/api/escape-rooms/{id}/time-slots
    - Description: Show timeslots for specific escape room

**Bookings:**

7. Retrieve All Bookings For User
    - Method: GET
    - URL: istescape.test/api/bookings
    - Description: Lists all bookings the currently logged in user has booked
    - Authorization necessary

8. Create New Booking
    - Method: POST
    - URL: istescape.test/api/bookings
    - Description: Creates a new booking for the currently logged in user
    - Authorization necessary
    - Body data example:
        - escape_room_id: 3
        - begins_at: 2023/05/20 17:30:00
9. Delete Booking
    - Method: DELETE
    - URL: istescape.test/api/bookings/{id}
    - Description: Delete a booking in the currently logged in user
    - Authorization necessary


Notes: 
- The header must contain key "Accept" and value "application/json"
- The authorized routes will need you to use the token you got while registering/logging-in as the bearer token of the request in the authorization header

## How to run the tests

This one is pretty straight forward. Just run the command `php artisan test` and if you have set up your DB and .env.testing right, all 11 tests will be green. I have listed them all as feature tests and added no unit tests, although it's debatable where they fit in more.
