# How to build
To download dependecies run `composer install`

Create a file in the root directory named .env, and fill the following variables
```
    DB_HOST=[HOST NAME]
    DB_NAME=[SCHEMA NAME]
    DB_USER=[MYSQL USER]
    DB_PASS=[MYSQL PASSWORD]
    UPLOADS_PATH=[BASE PATH WHERE THE FILES WILL BE UPLOADED]
    MAILGUN_APIKEY=[MAILGUN API KEY]
    MAILGUN_DOMAIN=[MAILGUN CONFIGURED DOMAIN]
    MAILGUN_FROM=[FROM EMAIL]
```
    
# Running with docker
Follow build instructions and then run `docker-compose up`, it will create a mysql database with test data and 
an apache server configured to listen on port 8080
