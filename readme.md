# Library Management System

A comprehensive web-based library management system built with PHP and MySQL. This system allows librarians to manage books, members, and borrowing transactions efficiently.

## Features

- **Book Management**

  - Add, edit, and delete books
  - Search books by title, author, or ISBN
  - Track book availability and status
  - Categorize books by genre

- **Member Management**

  - Register new library members
  - Update member information
  - Track member borrowing history
  - Manage member status (active/inactive)

- **Borrowing System**

  - Issue books to members
  - Return book processing
  - Track due dates and overdue books
  - Generate borrowing reports

- **Administrative Features**
  - User authentication and authorization
  - Dashboard with system statistics
  - Generate various reports
  - System settings and configuration

## Requirements

- **Web Server**: Apache (via XAMPP)
- **PHP**: Version 7.4 or higher
- **Database**: MySQL 5.7 or higher
- **Browser**: Modern web browser (Chrome, Firefox, Safari, Edge)

## Installation

1. **Download and Install XAMPP**

   - Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Install and start Apache and MySQL services

2. **Clone or Download the Project**

   ```bash
   git clone https://github.com/yourusername/library-system.git
   # OR download and extract the ZIP file
   ```

3. **Move Files to XAMPP Directory**

   - Copy the project folder to `c:\xampp\htdocs\Librarysystem`

4. **Database Setup**

   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create a new database named `library_system`
   - Import the SQL file: `database/library_system.sql`

5. **Configure Database Connection**

   - Edit `config/database.php` with your database credentials:

   ```php
   $host = 'localhost';
   $username = 'root';
   $password = '';
   $database = 'library_system';
   ```

6. **Access the Application**
   - Open your browser and go to: `http://localhost/Librarysystem`

## Default Login Credentials

- **Admin Username**: admin
- **Admin Password**: admin123

_Please change these credentials after first login for security._

## Project Structure

```
Librarysystem/
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── css/
│   └── style.css
├── js/
│   └── script.js
├── images/
├── admin/
│   ├── dashboard.php
│   ├── books.php
│   ├── members.php
│   └── reports.php
├── database/
│   └── library_system.sql
├── index.php
├── login.php
└── readme.md
```

## Usage

1. **Login**: Access the admin panel using the default credentials
2. **Add Books**: Navigate to Books section to add new books to the library
3. **Register Members**: Use the Members section to register new library members
4. **Issue Books**: Use the borrowing system to issue books to members
5. **Return Books**: Process book returns and update availability
6. **Generate Reports**: Access various reports for library statistics

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

### Version 1.0.0

- Initial release
- Basic book and member management
- Borrowing system implementation
- Admin dashboard

---

**Note**: This is a local development setup. For production deployment, ensure proper security measures are implemented including SSL certificates, secure database credentials, and regular backups.
