# Vesta

Vesta is a comprehensive property rental platform connecting travelers with hosts, offering a seamless experience for booking accommodations. Whether you're looking for a cozy apartment, a spacious villa, or a traditional Riad, Vesta helps you find your perfect stay.

## 🚀 Features

- **User Roles & Authentication**:
  - Secure Login and Registration.
  - Role-based access: **Voyageur** (Traveler), **Hote** (Host), and **Admin**.
- **For Travelers (Voyageurs)**:
  - Search properties by destination.
  - View detailed property listings with images, prices, and descriptions.
  - Book reservations for specific dates.
  - Add properties to favorites.
  - Leave reviews and ratings for stays.
- **For Hosts (Hotes)**:
  - Dashboard to manage listings.
  - Add new accommodations with photos and details.
  - View and manage reservations.
- **For Admins**:
  - System-wide oversight and management.
- **Additional Features**:
  - Messaging system between users.
  - Notifications system.
  - Reclamation/Support ticketing.

## 🛠️ Tech Stack

- **Backend**: PHP (Custom MVC Architecture)
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Dependencies**: Data handling via PDO, Emailing via PHPMailer (Composer)

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

- PHP (7.4 or higher)
- MySQL
- Composer

## 🔧 Installation

1.  **Clone the Repository**

    ```bash
    git clone https://github.com/bahaztariq/Vesta.git
    cd Vesta
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    ```

3.  **Database Setup**

    - Create a new MySQL database named `Vesta`.
    - Import the provided SQL schema to create tables and seed initial data:
      ```bash
      mysql -u root -p Vesta < database/database.sql
      ```
    - _Alternatively, you can import `database/database.sql` using tools like phpMyAdmin or MySQL Workbench._

4.  **Configuration**

    - Check `config/DataBase.php` to ensure the database connection credentials (host, username, password, dbname) match your local environment.

5.  **Run the Application**
    - You can use the built-in PHP server for development:
      ```bash
      php -S localhost:8000
      ```
    - Open your browser and navigate to `http://localhost:8000`.

## 👤 Usage Sample Users

The database comes seeded with sample users for testing:

| Role         | Username        | Email                 |
| :----------- | :-------------- | :-------------------- |
| **Admin**    | `admin` | `admin@example.com` |
| **Host**     | `host`    | `Hote@example.com`   |
| **Traveler** | `travel` | `Voyageur@example.com`   |

_(Note: Passwords in the sample data are hashed, you may need to reset them or create a new user to log in if you don't know the plain text versions corresponding to the hashes)._

## 📄 License

This project is open-source.
