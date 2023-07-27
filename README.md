## Laravel CRUD API with Auth
Basic Laravel CRUD API application included with Authentication Module & Example. It's included with JWT authentication and Swagger API format.

----

### Language & Framework Used:
1. PHP-8
1. Laravel-9

### Older Versions (if Needed):
1. Laravel 8.x - https://github.com/laravel/laravel

### Architecture Used:
1. Laravel 9.x
1. Interface-Repository Pattern
1. Model Based Eloquent Query
1. Swagger API Documentation - https://github.com/DarkaOnLine/L5-Swagger
1. JWT Auth - https://github.com/tymondesigns/jwt-auth
1. PHP Unit Testing - Some basic unit testing added.

### API List:
##### Authentication Module
1. [x] Register User API with Token
1. [x] Login API with Token
1. [x] Authenticated User Profile
1. [x] Refresh Data
1. [x] Logout

##### Product Module
1. [x] Product List
1. [x] Product List [Public]
1. [x] Create Product
1. [x] Edit Product
1. [x] View Product
1. [x] Delete Product



##### Customer Module
1. [x] Customer List
1. [x] Create Customer
1. [x] Edit Customer
1. [x] View Customer
1. [x] Delete Customer

##### Invoice Module
1. [x] Invoice List
1. [x] Create Invoice
1. [x] Edit Invoice
1. [x] View Invoice
1. [x] Delete Invoice

### How to Run:
1. Clone Project - 

```bash
git clone https://github.com/jauharimalik/indosatt.git
```
1. Go to the project drectory by `cd indosatt` & Run the
2. Create `.env` file & Copy `.env.example` file to `.env` file
3. Create a database called - `testindosat`.
4. Install composer packages - `composer install`.
5. Now migrate database, open testindosat.sql.sql file inside folder database
6. Copy All Content from testindosat.sql into your sql command
7. Generate Swagger API
``` bash
php artisan l5-swagger:generate
```
7. Run the server -
``` bash
php artisan serve
```
8. Open Browser -
http://127.0.0.1:8000 & go to API Documentation -
http://127.0.0.1:8000/api/documentation
9. You'll see a Swagger Panel.


### Procedure
1. First Login with the given credential or any other user credential
1. Set bearer token to Swagger Header or Post Header as Authentication
1. Hit Any API, You can also hit any API, before authorization header data set to see the effects.


### Demo
video in : https:/erp-choice.com/indosatt.mp4

### Test
1. Test with Postman - https://www.getpostman.com/collections/5642915d135f376b84af [Click to open with post man]
1. Test with Swagger.
1. Swagger Limitation: Image can not be uploaded throw Swagger, it can be uploaded throw Postman.
