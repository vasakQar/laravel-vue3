# Project Description

This project is a web application built using Laravel for the backend and Vue 3 with Vite for the frontend. It includes features such as user authentication, blog creation, and viewing.

## Prerequisites

- PHP >= 7.3
- Composer
- Node.js >= 12.x
- npm or yarn
- MySQL

## Backend Setup

1. **Clone the repository:**

    ```sh
    git clone https://github.com/vasakQar/laravel-vue3
    cd backend
    ```

2. **Install dependencies:**

    ```sh
    composer install
    ```

3. **Copy the `.env` file and update the environment variables:**

    ```sh
    cp .env.example .env
    ```

   Update the `.env` file with your database credentials and mailer credentials for mail verification to work:

    ```dotenv
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=your-email@gmail.com
    MAIL_PASSWORD=your-email-password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="your-email@gmail.com"
    MAIL_FROM_NAME="Your Name"
    ```

4. **Generate the application key:**

    ```sh
    php artisan key:generate
    ```

5. **Run the database migrations:**

    ```sh
    php artisan migrate
    ```

6. **Start the Laravel development server:**

    ```sh
    php artisan serve
    ```

## Frontend Setup

1. **Navigate to the frontend directory:**

    ```sh
    cd ../frontend
    ```

2. **Install dependencies:**

    ```sh
    npm install
    ```

3. **Start the development server:**

    ```sh
    npm run dev
    ```

## Running the Project

1. **Backend:**

   Ensure the Laravel development server is running:

    ```sh
    php artisan serve
    ```

2. **Frontend:**

   Ensure the Vite development server is running:

    ```sh
    npm run dev
    ```

3. **Access the application:**

   Open your browser and navigate to `http://localhost:5173`.
