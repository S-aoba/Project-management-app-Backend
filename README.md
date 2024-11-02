# Project Management app / Backend
>  This app is designed to help teams effectively plan, execute, and track their projects in a user-friendly environment. Whether you’re working on small tasks or large-scale projects, this app provides the necessary tools to enhance collaboration and streamline workflows.

Frontend Repository: [https://github.com/S-aoba/Project-management-app-Frontend
](https://github.com/S-aoba/frontend-PM-app?tab=readme-ov-file)

## Key Stack
| Category             | Technology              |
|----------------------|-------------------------|
| **Programming Language** | PHP 8.2                  |
| **Framework**        | Laravel 11.9       |
| **Authentication**   | Laravel Sanctum 4.0        |
| **Development Tool** | Laravel Tinker 2.9          |

# Quick Start
### Precondition
- Have Docker Desktop (https://www.docker.com/) or a similar Docker enviroment installed and running.

### Setting Up
1. Clone the Repository
```Bash
git clone https://github.com/S-aoba/backend-PM-app.git
```
2. Create a .env file
```
# Example .env file
You can use the provided .env.example file as atemplate and modify only the necessary values.

APP_LOCALE=ja
APP_FALLBACK_LOCALE=ja
APP_FAKER_LOCALE=ja_JP
-------------------------------------------------------------------
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD="Please specify your desired password"
-------------------------------------------------------------------
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```
3. Install dependencies by running the following command.
```docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

4. Start the Docker containers(Please check current workin directory)
```
./vendor/bin/sail up -d
```

5. Once the container is up, run the following command to migrate the database.
```bash
./vendor/bin/sail artisan migrate
```

By following these steps, you'll have a local development environment set up for the backend of your Project Management app.







