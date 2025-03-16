# News Aggregator - Laravel API

## Overview
This is a **News Aggregator** API built with Laravel. It fetches news from multiple sources and provides a RESTful API for users to access news based on their preferences.

## Prerequisites
Ensure you have the following installed:
- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/)

## Getting Started

### 1️⃣ Clone the Repository
```sh
git clone https://github.com/kirubha7/News-Aggregator.git
cd News-Aggregator
```

### 2️⃣ Configure Environment Variables
Copy the `.env.example` file and rename it to `.env`:
```sh
cp .env.example .env
```
Then, update database credentials to match Docker's MySQL service:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3307
DB_DATABASE=laravel
DB_USERNAME=laravel_user
DB_PASSWORD=secret
```

### 3️⃣ Start the Application with Docker
Run the following command to build and start the containers:
```sh
docker-compose up -d --build
```
This will start:
- Laravel app (`app` service)
- MySQL (`db` service)
- Nginx (`nginx` service)

### 4️⃣ Install Dependencies
Once the containers are up, run:
```sh
docker exec -it laravel_app composer install
```

### 5️⃣ Generate Application Key
```sh
docker exec -it laravel_app php artisan key:generate
```

### 6️⃣ Run Database Migrations & Seeders
```sh
docker exec -it laravel_app php artisan migrate:fresh --seed
```

### 7️⃣ Access the Application
- API will be available at: [http://localhost:8080](http://localhost:8080)
- Swagger documentation at: `http://localhost:8080/api/documentation`

### 8️⃣ Running Tests
To run the test suite:
```sh
docker exec -it laravel_app php artisan test
```

## Stopping the Application
To stop the running containers:
```sh
docker-compose down
```
To stop and remove all data volumes (use with caution!):
```sh
docker-compose down -v
```

## Troubleshooting
- If MySQL connection fails, try restarting the database container:
  ```sh
  docker restart laravel_db
  ```
- Check logs for errors:
  ```sh
  docker logs laravel_app
  ```
- If permission issues arise, run:
  ```sh
  sudo chmod -R 777 storage bootstrap/cache
  ```

## License
This project is licensed under the MIT License.

