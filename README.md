# Sales Management System

## Overview
The Sales Management System is a web-based application designed to streamline the process of managing and tracking sales data. Built with PHP, MySQL, and Bootstrap, the system provides a comprehensive interface for entering, validating, and storing sales information securely.

## Features
- **Data Entry Form**: User-friendly form to input sales data including username, BDM name, customer name, and more.
- **Validation**: Ensures that all required fields are filled and valid before submission.
- **Database Integration**: Data is securely stored in a PostgreSQL database.
- **Responsive Design**: Built with Bootstrap for a responsive and modern user interface.
- **Error Handling**: Provides user feedback for validation errors and database issues.

## Technologies Used
- **Frontend**: HTML, CSS, Bootstrap
- **Backend**: PHP
- **Database**: PostgreSQL
- **Server**: Apache (or any suitable PHP server)

## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/sales-management-system.git
   cd sales-management-system
   ```

2. **Set Up Database**
   - Create a PostgreSQL database.
   - Import the provided SQL schema to set up the `salesdata` table.

3. **Configuration**
   - Update the `dbconfig.php` file with your database credentials:
     ```php
     <?php
     $host = 'your_host';
     $db   = 'your_database';
     $user = 'your_user';
     $pass = 'your_password';
     $charset = 'utf8mb4';

     $dsn = "pgsql:host=$host;dbname=$db;charset=$charset";
     $options = [
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES   => false,
     ];

     try {
         $pdo = new PDO($dsn, $user, $pass, $options);
     } catch (\PDOException $e) {
         throw new \PDOException($e->getMessage(), (int)$e->getCode());
     }
     ?>
     ```

4. **Run the Application**
   - Deploy the application on a local or remote PHP server.
   - Access the application via your browser at `http://localhost/sales-management-system/insert_form.php`.

## Usage
- **Insert Data**: Navigate to `insert_form.php` to enter and submit sales data.
- **View Errors**: The form provides error messages for validation issues or database errors.
- **Success Confirmation**: Upon successful data entry, users are redirected with a success message.

## Contributing
- Fork the repository and create a feature branch for any improvements or fixes.
- Submit a pull request with a detailed description of your changes.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
