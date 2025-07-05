# Web_Blog

This is a simple weblog project developed as a group school project with [Mohand Hamad](https://github.com/mohandhamad).  It utilizes HTML, CSS, PHP, and JavaScript for its functionality.

## Features

* Create and modify personal profile
* Create and modify blog posts
* Search and interact with posts (Like, comment)
* Dark Mode

## Technologies Used

* HTML
* CSS
* PHP
* JavaScript
* MySQL
* Web Server (Apache)

## Setup Instructions

1. **Prerequisites:**
    * Web Server (e.g., Apache)
    * Database System (e.g., MySQL)
    * PHP installed and configured.
2. **Database Setup:**
    * Import `webblog.sql` into your MySQL database.  (Ensure you have created the database beforehand). The SQL file provided in the root folder.
3. **Configuration:**
    * Modify the database configuration in `Web_Blog/php_processes/db_connection.php` file
      ```
      $servername = "localhost";
      $username = "root"; // MySQL username
      $password = ""; // MySQL password
      $dbname = "webblog"; // database name
      ```
4. **Deployment:**
    * Upload the project files to your web server's document root.

## Preview
![](Web_Blog/preview/weblog(1).png)
![](Web_Blog/preview/weblog(2).png)
![](Web_Blog/preview/weblog(3).png)
