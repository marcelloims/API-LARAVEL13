Disini saya menggunakan postman:

# Login

method:post
endpoint:http://127.0.0.1:8000/api/auth/login
Body->raw
{
"email": "admin@example.com",
"password": "password"
}

<!-- --------------------------------------------- -->

# Logout

method:post
endpoint:http://127.0.0.1:8000/api/auth/logout with token

<!-- --------------------------------------------- -->

# Register

method:post
http://127.0.0.1:8000/api/auth/register
Body->raw
{
"name": "Marcell",
"email": "marcelloimanuel25@gmail.com"
}

<!-- --------------------------------------------- -->

# POST - Fetch

method:get
endpoint: http://127.0.0.1:8000/api/post/fetch with token
Body->raw
{
"name": "Marcell",
"email": "marcelloimanuel25@gmail.com"
}

<!-- --------------------------------------------- -->

# POST - getList

method:get
endpoint: http://127.0.0.1:8000/api/post/get with token

<!-- --------------------------------------------- -->

# POST - getList

method:get
endpoint: http://127.0.0.1:8000/api/post/detail/{id} with token

<!-- --------------------------------------------- -->

# POST - store

method:post
endpoint: http://127.0.0.1:8000/api/post/save with token
Body->raw:
{
"user_id": 1,
"title": "title title"
}

<!-- --------------------------------------------- -->

# POST - update

method:patch
endpoint: http://127.0.0.1:8000/api/post/update/{id} with token
Body->raw:
{
"user_id": 1,
"title": "title title"
}

<!-- --------------------------------------------- -->

# POST - delete

method:delete
endpoint: http://127.0.0.1:8000/api/post/delete/{id} with token

<!-- --------------------------------------------- -->

# Weather - index

method:get
endpoint: http://127.0.0.1:8000/api/weather/index with token
Body-raw:
{
"city":"Perth"
}

<!-- --------------------------------------------- -->
<!-- COMMENDS -->

php artisan weather:update-cache |
php artisan email:test-welcome riancihuuy@gmail.com "Marcell" |
php artisan queue:work
