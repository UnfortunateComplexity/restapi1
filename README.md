# restapi1
* Attempt at creating a create REST API using PHP PDO. Through this API, simple CRUD Operations can be performed on users in the database.
* Covers user registration and login.


# How to get it working:
1. Start Apache and MySQL servers (recommended using a stack such as XAMPP).
2. Have an API development client such as Postman.
3. Open a web browser, go to http://localhost/phpmyadmin/ and import the database 'database1.sql'.
4. Start Postman, make a request to http://localhost/auth3/api/login.php or http://localhost/auth3/api/create_user.php to test the user login and creation respectively.

# Working so far:
* Create user with username, usertype and password.
* Login created user in the database with username and password.
* Delete user with their ID (registered in db that is...).
* Display users registered in the database (Read inserted data).

# To be done:
* Implement UPDATE user correctly.
* Apply some error handling.
