<?php
require_once 'classes/Library.php';

// Initialize library
$library = new Library();
$message = '';
$messageType = '';


// Default section and track current section
$currentSection = 'dashboard';

// Handle form submissions
if ($_POST) {

      // Get current section from form if available
    if (isset($_POST['current_section'])) {
        $currentSection = $_POST['current_section'];
    }
    try {
        switch ($_POST['action']) {
            case 'add_book':
                $book = new Book($_POST['title'], $_POST['author'], $_POST['issue_number'], $_POST['available_copies'], $_POST['isbn']);
                if ($library->addBook($book)) {
                    $message = "Book added successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Book already exists!";
                    $messageType = 'error';
                }
                break;
                
            case 'add_magazine':
                $magazine = new Magazine($_POST['title'], $_POST['author'], $_POST['issue_number'], $_POST['available_copies']);
                if ($library->addMagazine($magazine)) {
                    $message = "Magazine added successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Magazine already exists!";
                    $messageType = 'error';
                }
                break;
                
            case 'register_member':
                $member = new Member($_POST['member_id'], $_POST['name'], $_POST['email']);
                if ($library->registerMember($member)) {
                    $message = "Member registered successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Member already exists!";
                    $messageType = 'error';
                }
                break;
                
            case 'borrow_item':
                $result = $library->borrowItem($_POST['item_id'], $_POST['member_id'], $_POST['item_type']);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                break;
                
            case 'return_item':
                $result = $library->returnItem($_POST['item_id'], $_POST['member_id'], $_POST['item_type']);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
                break;
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Add some sample data for demonstration
if (empty($library->getAllBooks()) && empty($library->getAllMagazines()) && empty($library->getAllMembers())) {
    try {
        //simple book
       $library->addBook(new Book("The Great Gatsby", "F. Scott Fitzgerald", 1, 3, "978-0-7432-7356-5"));
       $library->addBook(new Book("To Kill a Mockingbird", "Harper Lee", 1, 2, "978-0-06-112008-4"));
       $library->addBook(new Book("The Catcher in the Rye", "J.D. Salinger", 1, 4, "978-0-316-76948-0"));
       
       // Sample magazines
       $library->addMagazine(new Magazine("National Geographic", "Various", 202, 5));
       $library->addMagazine(new Magazine("Time", "Various", 45, 3));
       $library->addMagazine(new Magazine("Forbes", "Various", 88, 2));

       // Sample members
       $library->registerMember(new Member("M001", "John Doe", "john@example.com"));
       $library->registerMember(new Member("M002", "Jane Smith", "jane@example.com"));
       $library->registerMember(new Member("M003", "Robert Johnson", "robert@example.com"));
    } catch (Exception $e) {
        // Ignore errors for sample data
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraryOS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
            --card: #1e293b;
            --card-darker: #0f172a;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --success-bg: rgba(16, 185, 129, 0.1);
            --error-bg: rgba(239, 68, 68, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--darker);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
            grid-template-rows: auto 1fr;
            grid-template-areas:
                "sidebar header"
                "sidebar main";
        }

        .sidebar {
            grid-area: sidebar;
            background-color: var(--dark);
            border-right: 1px solid var(--border);
            padding: 1.5rem;
            height: 100vh;
            position: fixed;
            width: 280px;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0.5rem;
        }

        .logo-icon {
            font-size: 1.8rem;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 0.75rem;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 0.5rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.1), transparent);
            color: var(--primary);
            border-left: 3px solid var(--primary);
        }

        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            width: 1.5rem;
            text-align: center;
        }

        .header {
            grid-area: header;
            padding: 1.5rem 2rem;
            background-color: var(--dark);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .theme-toggle, .menu-toggle {
            background: none;
            border: none;
            color: var(--text);
            font-size: 1.2rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .theme-toggle:hover, .menu-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-toggle {
            display: none;
        }

        .main-content {
            grid-area: main;
            padding: 2rem;
            overflow-y: auto;
        }

        .section {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message {
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            border-left: 4px solid transparent;
        }

        .message.success {
            background-color: var(--success-bg);
            border-left-color: var(--secondary);
            color: var(--secondary);
        }

        .message.error {
            background-color: var(--error-bg);
            border-left-color: var(--danger);
            color: var(--danger);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--card);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 500;
        }

        .stat-card .icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 1.5rem;
            opacity: 0.5;
            color: var(--primary);
        }

        .card {
            background-color: var(--card);
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
        }

        .data-table th {
            font-weight: 500;
            color: var(--text-muted);
            background-color: var(--card-darker);
            position: sticky;
            top: 0;
        }

        .data-table tr {
            border-bottom: 1px solid var(--border);
            transition: background-color 0.2s;
        }

        .data-table tr:last-child {
            border-bottom: none;
        }

        .data-table tr:hover {
            background-color: rgba(255, 255, 255, 0.03);
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-book {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .badge-magazine {
            background-color: rgba(236, 72, 153, 0.1);
            color: #ec4899;
        }

        .badge-overdue {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .badge-due-soon {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: var(--card-darker);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            color: var(--text);
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-success {
            background-color: var(--secondary);
            color: white;
        }

        .btn-success:hover {
            background-color: #0ca678;
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn i {
            margin-right: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--text-muted);
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-muted);
        }

        @media (max-width: 992px) {
            body {
                grid-template-columns: 1fr;
                grid-template-areas:
                    "header"
                    "main";
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .menu-toggle {
                display: flex;
            }

            .header {
                padding: 1rem;
            }

            .main-content {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 1rem;
            }

            .data-table {
                display: block;
                overflow-x: auto;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon"><i class="fas fa-book"></i></div>
            <div class="logo-text">LibraryOS</div>
        </div>

        <nav>
            <ul class="nav-links">
                <li>
                    <a class="nav-link <?php echo ($currentSection === 'dashboard') ? 'active' : ''; ?>" onclick="showSection('dashboard')">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link  <?php echo ($currentSection === 'add-books') ? 'active' : ''; ?>" onclick="showSection('add-books')">
                        <i class="fas fa-book"></i>
                        <span>Books</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link <?php echo ($currentSection === 'add-magazines') ? 'active' : ''; ?>" onclick="showSection('add-magazines')">
                        <i class="fas fa-newspaper"></i>
                        <span>Magazines</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link <?php echo ($currentSection === 'members') ? 'active' : ''; ?>" onclick="showSection('members')">
                        <i class="fas fa-users"></i>
                        <span>Members</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link <?php echo ($currentSection === 'borrow-items') ? 'active' : ''; ?>" onclick="showSection('borrow-items')">
                        <i class="fas fa-hand-holding"></i>
                        <span>Borrow</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link <?php echo ($currentSection === 'return-items') ? 'active' : ''; ?>" onclick="showSection('return-items')">
                        <i class="fas fa-undo-alt"></i>
                        <span>Return</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link <?php echo ($currentSection === 'reports') ? 'active' : ''; ?>" onclick="showSection('reports')">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Header -->
    <header class="header">
        <button class="menu-toggle" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 id="page-title">Dashboard</h1>
        <div class="header-actions">
            <button class="theme-toggle" id="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <?php if ($message): ?>
            <div class="message <?php echo htmlspecialchars($messageType); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Dashboard Section -->
        <section id="dashboard" class="section <?php echo ($currentSection === 'dashboard') ? 'active' : ''; ?>">
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-book"></i></div>
                    <h3><?php echo count($library->getAllBooks()); ?></h3>
                    <p>Total Books</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-newspaper"></i></div>
                    <h3><?php echo count($library->getAllMagazines()); ?></h3>
                    <p>Total Magazines</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <h3><?php echo count($library->getAllMembers()); ?></h3>
                    <p>Total Members</p>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                    <h3><?php echo count($library->getBorrowedItemsReport()); ?></h3>
                    <p>Items Borrowed</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Recent Activity</h2>
                </div>
                <?php $report = $library->getBorrowedItemsReport(); ?>
                <?php if (!empty($report)): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($report, 0, 5) as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['member']); ?></td>
                                        <td><?php echo htmlspecialchars($item['item']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo strtolower($item['type']); ?>">
                                                <?php echo $item['type']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $item['dueDate']->format('Y-m-d'); ?></td>
                                        <td>
                                            <?php if ($item['daysOverdue'] > 0): ?>
                                                <span class="badge badge-overdue">Overdue</span>
                                            <?php else: ?>
                                                <span class="badge badge-due-soon">Active</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="icon"><i class="fas fa-book-open"></i></div>
                        <h3>No active borrowings</h3>
                        <p>All items are currently available in the library.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Books Section -->
        <section id="add-books" class="section <?php echo ($currentSection === 'add-books') ? 'active' : ''; ?> ">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Add New Book</h2>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="add_book">
                    <input type="hidden" name="current_section" value="add-books">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="book_title">Title</label>
                            <input type="text" id="book_title" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="book_author">Author</label>
                            <input type="text" id="book_author" name="author" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="book_issue">Issue Number</label>
                            <input type="number" id="book_issue" name="issue_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="book_copies">Available Copies</label>
                            <input type="number" id="book_copies" name="available_copies" class="form-control" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="book_isbn">ISBN</label>
                            <input type="text" id="book_isbn" name="isbn" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Book
                    </button>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Book Inventory</h2>
                </div>
                <?php if (!empty($library->getAllBooks())): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Issue</th>
                                    <th>Available</th>
                                    <th>ISBN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($library->getAllBooks() as $book): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($book->title); ?></td>
                                        <td><?php echo htmlspecialchars($book->author); ?></td>
                                        <td><?php echo $book->issueNumber; ?></td>
                                        <td><?php echo $book->getDetails()['availableCopies']; ?></td>
                                        <td><?php echo htmlspecialchars($book->isbn); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="icon"><i class="fas fa-book"></i></div>
                        <h3>No books added yet</h3>
                        <p>Add your first book to the library.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Magazines Section -->
        <section id="add-magazines" class="section  <?php echo ($currentSection === 'add-magazines') ? 'active' : ''; ?>">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Add New Magazine</h2>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="add_magazine">
                    <input type="hidden" name="current_section" value="add-magazines">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="mag_title">Title</label>
                            <input type="text" id="mag_title" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mag_author">Author/Publisher</label>
                            <input type="text" id="mag_author" name="author" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mag_issue">Issue Number</label>
                            <input type="number" id="mag_issue" name="issue_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mag_copies">Available Copies</label>
                            <input type="number" id="mag_copies" name="available_copies" class="form-control" min="0" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Magazine
                    </button>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Magazine Inventory</h2>
                </div>
                <?php if (!empty($library->getAllMagazines())): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Publisher</th>
                                    <th>Issue</th>
                                    <th>Available</th>
                                    <th>ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($library->getAllMagazines() as $magazine): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($magazine->title); ?></td>
                                        <td><?php echo htmlspecialchars($magazine->author); ?></td>
                                        <td><?php echo $magazine->issueNumber; ?></td>
                                        <td><?php echo $magazine->getDetails()['availableCopies']; ?></td>
                                        <td><?php echo htmlspecialchars($magazine->title . '_' . $magazine->issueNumber); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="icon"><i class="fas fa-newspaper"></i></div>
                        <h3>No magazines added yet</h3>
                        <p>Add your first magazine to the library.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Members Section -->
        <section id="members" class="section  <?php echo ($currentSection === 'members') ? 'active' : ''; ?>">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Register New Member</h2>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="register_member">
                    <input type="hidden" name="current_section" value="members">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="member_id">Member ID</label>
                            <input type="text" id="member_id" name="member_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="member_name">Name</label>
                            <input type="text" id="member_name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="member_email">Email</label>
                            <input type="email" id="member_email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Register Member
                    </button>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Registered Members</h2>
                </div>
                <?php if (!empty($library->getAllMembers())): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Member ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Borrowed Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($library->getAllMembers() as $member): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($member->memberId); ?></td>
                                        <td><?php echo htmlspecialchars($member->name); ?></td>
                                        <td><?php echo htmlspecialchars($member->email); ?></td>
                                        <td><?php echo $member->getBorrowedItemsCount(); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="icon"><i class="fas fa-users"></i></div>
                        <h3>No members registered yet</h3>
                        <p>Register your first library member.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Borrow Section -->
        <section id="borrow-items" class="section  <?php echo ($currentSection === 'borrow-items') ? 'active' : ''; ?>">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Borrow Item</h2>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="borrow_item">
                    <input type="hidden" name="current_section" value="borrow-items">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="borrow_item_type">Item Type</label>
                            <select id="borrow_item_type" name="item_type" class="form-control" required>
                                <option value="book">Book</option>
                                <option value="magazine">Magazine</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="borrow_item_id">Item ID</label>
                            <input type="text" id="borrow_item_id" name="item_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="borrow_member_id">Member ID</label>
                            <input type="text" id="borrow_member_id" name="member_id" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-hand-holding"></i> Borrow Item
                    </button>
                </form>
            </div>
        </section>

        <!-- Return Section -->
        <section id="return-items" class="section  <?php echo ($currentSection === 'return-items') ? 'active' : ''; ?>">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Return Item</h2>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="return_item">
                    <input type="hidden" name="current_section" value="return-items">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="return_item_type">Item Type</label>
                            <select id="return_item_type" name="item_type" class="form-control" required>
                                <option value="book">Book</option>
                                <option value="magazine">Magazine</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="return_item_id">Item ID</label>
                            <input type="text" id="return_item_id" name="item_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="return_member_id">Member ID</label>
                            <input type="text" id="return_member_id" name="member_id" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-undo-alt"></i> Return Item
                    </button>
                </form>
            </div>
        </section>

        <!-- Reports Section -->
        <section id="reports" class="section">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Borrowed Items Report</h2>
                </div>
                <?php $report = $library->getBorrowedItemsReport(); ?>
                <?php if (!empty($report)): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Member ID</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($report as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['member']); ?></td>
                                        <td><?php echo htmlspecialchars($item['memberId']); ?></td>
                                        <td><?php echo htmlspecialchars($item['item']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo strtolower($item['type']); ?>">
                                                <?php echo $item['type']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $item['borrowDate']->format('Y-m-d'); ?></td>
                                        <td><?php echo $item['dueDate']->format('Y-m-d'); ?></td>
                                        <td>
                                            <?php if ($item['daysOverdue'] > 0): ?>
                                                <span class="badge badge-overdue"><?php echo $item['daysOverdue']; ?> days overdue</span>
                                            <?php else: ?>
                                                <span class="badge badge-due-soon">On time</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                        <h3>No borrowed items</h3>
                        <p>All items are currently available in the library.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        // Show selected section
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => section.classList.remove('active'));
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Update page title
            const pageTitle = document.getElementById('page-title');
            pageTitle.textContent = sectionId.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase());
            
            // Update active nav link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => link.classList.remove('active'));
            
            // Find and activate the clicked nav link
            const activeLink = Array.from(navLinks).find(link => 
                link.getAttribute('onclick').includes(sectionId)
            );
            if (activeLink) {
                activeLink.classList.add('active');
            }
            

             // Update all forms with the current section
            const sectionInputs = document.querySelectorAll('input[name="current_section"]');
            sectionInputs.forEach(input => {
                input.value = sectionId;
            });
            


            // Close mobile menu if open
            if (window.innerWidth <= 992) {
                document.querySelector('.sidebar').classList.remove('active');
            }
        }
        
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.getElementById('menu-toggle');
            
            if (window.innerWidth <= 992 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target))
                 {
                sidebar.classList.remove('active');
            }
        });
        
        // Light/Dark mode toggle 
        // document.getElementById('theme-toggle').addEventListener('click', function() {
        //     const icon = this.querySelector('i');
        //     if (icon.classList.contains('fa-moon')) {
        //         icon.classList.remove('fa-moon');
        //         icon.classList.add('fa-sun');
        //     } else {
        //         icon.classList.remove('fa-sun');
        //         icon.classList.add('fa-moon');
        //     }
        // });
    </script>
</body>
</html>