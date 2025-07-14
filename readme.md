# LibraryOS - Modern Library Management System

A sleek, modern web-based library management system built with PHP and featuring a responsive dark-themed interface. LibraryOS provides comprehensive tools for managing books, magazines, members, and borrowing transactions with an intuitive dashboard.

## Features

### 📚 **Book Management**

- Add new books with ISBN, author, title, and copy tracking
- Real-time inventory management
- Track available copies and borrowing status
- Comprehensive book listing with search capabilities

### 📰 **Magazine Management**

- Add magazines with issue numbers and publisher information
- Track magazine inventory and availability
- Separate management system for periodicals

### 👥 **Member Management**

- Register new library members with unique IDs
- Track member information (name, email, member ID)
- Monitor borrowing history and current borrowed items
- Member activity tracking

### 🔄 **Borrowing System**

- Issue books and magazines to registered members
- Automated due date calculation (14-day borrowing period)
- Return processing with status updates
- Overdue item tracking and notifications

### 📊 **Dashboard & Reports**

- Real-time statistics dashboard
- Visual cards showing total books, magazines, members, and borrowed items
- Recent activity feed
- Comprehensive borrowing reports with due dates and overdue status
- Member borrowing history

### 🎨 **Modern UI Features**

- Dark-themed responsive interface
- Mobile-friendly design with collapsible sidebar
- Smooth animations and transitions
- Font Awesome icons throughout
- Grid-based layout system

## Technical Requirements

- **Web Server**: Apache (via XAMPP recommended)
- **PHP**: Version 7.4 or higher
- **Browser**: Modern web browser (Chrome, Firefox, Safari, Edge)
- **Dependencies**: Font Awesome 6.0+ (loaded via CDN)

## Installation

### Quick Setup with XAMPP

1. **Install XAMPP**

   ```bash
   # Download from https://www.apachefriends.org/
   # Install and start Apache service
   ```

2. **Deploy LibraryOS**

   ```bash
   # Clone or download the project
   git clone [repository-url] c:\xampp\htdocs\Librarysystem

   # Or extract ZIP file to:
   c:\xampp\htdocs\Librarysystem\
   ```

3. **Start Services**

   - Open XAMPP Control Panel
   - Start Apache service
   - (MySQL not required - uses session-based storage)

4. **Access Application**
   ```
   http://localhost/Librarysystem
   ```

## Project Structure

```
Librarysystem/
├── classes/
│   ├── Library.php          # Main library management class
│   ├── Book.php            # Book entity class
│   ├── Magazine.php        # Magazine entity class
│   └── Member.php          # Member entity class
├── index.php               # Main application file
├── readme.md              # This file
└── [additional assets]
```

## Class Architecture

### Library Class

- **Book Management**: `addBook()`, `getAllBooks()`
- **Magazine Management**: `addMagazine()`, `getAllMagazines()`
- **Member Management**: `registerMember()`, `getAllMembers()`
- **Borrowing System**: `borrowItem()`, `returnItem()`
- **Reporting**: `getBorrowedItemsReport()`

### Entity Classes

- **Book**: ISBN, title, author, issue number, available copies
- **Magazine**: Title, publisher, issue number, available copies
- **Member**: Member ID, name, email, borrowed items tracking

## Default Sample Data

LibraryOS comes with sample data for demonstration:

**Books:**

- The Great Gatsby by F. Scott Fitzgerald
- To Kill a Mockingbird by Harper Lee
- The Catcher in the Rye by J.D. Salinger

**Magazines:**

- National Geographic (Issue 202)
- Time Magazine (Issue 45)
- Forbes (Issue 88)

**Members:**

- John Doe (M001)
- Jane Smith (M002)
- Robert Johnson (M003)

## Usage Guide

### 1. Dashboard

- View real-time statistics
- Monitor recent borrowing activity
- Check system overview

### 2. Adding Books

- Navigate to "Books" section
- Fill in book details (title, author, ISBN, copies)
- Submit form to add to inventory

### 3. Adding Magazines

- Go to "Magazines" section
- Enter magazine information
- Specify issue number and available copies

### 4. Member Registration

- Access "Members" section
- Provide member ID, name, and email
- Register new library members

### 5. Borrowing Items

- Use "Borrow" section
- Select item type (book/magazine)
- Enter item ID and member ID
- System automatically sets 14-day due date

### 6. Returning Items

- Navigate to "Return" section
- Specify item type and IDs
- Process return and update availability

### 7. Reports

- View "Reports" section for comprehensive borrowing data
- Monitor overdue items
- Track member activity

## Mobile Responsiveness

LibraryOS features a fully responsive design:

- Collapsible sidebar navigation on mobile
- Touch-friendly interface elements
- Optimized layouts for tablets and phones
- Responsive data tables with horizontal scrolling

**LibraryOS v1.0** - A modern approach to library management
