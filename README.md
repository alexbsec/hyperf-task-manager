# This one is for you Leni

# Task Management System

This is a simple Task Management System application using the Hyperf framework. This application allows users to register, log in, and manage their tasks. It serves as a starting place for those looking to get their feet wet with Hyperf Framework.

# Requirements

Hyperf has some requirements for the system environment, it can only run under Linux and Mac environment, but due to the development of Docker virtualization technology, Docker for Windows can also be used as the running environment under Windows.

The various versions of Dockerfile have been prepared for you in the [hyperf/hyperf-docker](https://github.com/hyperf/hyperf-docker) project, or directly based on the already built [hyperf/hyperf](https://hub.docker.com/r/hyperf/hyperf) Image to run.

When you don't want to use Docker as the basis for your running environment, you need to make sure that your operating environment meets the following requirements:  

 - PHP >= 8.1
 - Any of the following network engines
   - Swoole PHP extension >= 5.0，with `swoole.use_shortname` set to `Off` in your `php.ini`
   - Swow PHP extension >= 1.3
 - JSON PHP extension
 - Pcntl PHP extension
 - OpenSSL PHP extension
 - PDO PHP extension
 - Redis PHP extension
 - Protobuf PHP extension

# Installation using Composer

First, you need to clone the repository with

```bash
git clone https://github.com/your-username/task-manager.git
cd task-manager
```

Once you have cloned the repository, you need to install the project dependencies using Composer. If you don't have Composer installed, you can follow the [Composer installation guide](https://getcomposer.org/download/).

```bash
composer install
```

Copy the .env.example file to create a new .env file and configure it with your database and other settings:

```env
# Environment
APP_NAME=TaskManager
APP_ENV=dev
SCAN_CACHEABLE=false
APP_DEBUG=true

# Database Configuration
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=yourpassword

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_AUTH=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_TIMEOUT=0.0
REDIS_READ_TIMEOUT=0.0
REDIS_MAX_IDLE_TIME=60

# Other Configuration
LOG_CHANNEL=stack
```

Finally, run the migration:

```bash
php bin/hyperf.php migrate
```

And start the server:

```bash
php bin/hyperf.php start
```

# Example Usage


## Register new user

To register a new user to the task manager, send the POST request:

```bash
curl -X POST -H "Content-Type: application/json" -d '{"name": "John Doe", "email": "john@example.com", "password": "secret"}' http://localhost:9501/register
```

## Login

To login as an existing user, send the POST request:

```bash
curl -X POST -H "Content-Type: application/json" -d '{"email": "john@example.com", "password": "secret"}' http://localhost:9501/login
```

The response will give you the authorization bearer token.

## Create a new Task

To create a new task, you need the authorization token fetched on login.

```bash
curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer your_token_here" -d '{"title": "New Task", "description": "Task description"}' http://localhost:9501/tasks
```

## List tasks

To list tasks, you must send the GET request:

```bash
curl -X GET -H "Authorization: Bearer your_token_here" http://localhost:9501/tasks
```

## Update tasks

To update a given task, you send the PUT request:

```bash
curl -X PUT -H "Content-Type: application/json" -H "Authorization: Bearer your_token_here" -d '{"title": "Updated Task", "description": "Updated description"}' http://localhost:9501/tasks/<task-number>
```

## Delete tasks

To delete a task, you send the DELETE request:

```bash
curl -X DELETE -H "Authorization: Bearer your_token_here" http://localhost:9501/tasks/<task-number>
```