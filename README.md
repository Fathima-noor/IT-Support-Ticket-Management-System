# IT Support Ticket Management System

A web-based application built using PHP and MySQL that helps users report IT problems and allows administrators to manage and track support tickets.

## Features

- **User Registration and Login** – Users can create accounts and log in securely.
- **Ticket Creation** – Users can submit IT support requests with a title, description, category, and priority.
- **Ticket Tracking** – Users can view their submitted tickets and check their status.
- **Admin Dashboard** – Administrators can view and manage all support tickets.
- **Ticket Status Management** – Admins can update ticket statuses to Open, In Progress, or Resolved.
- **Priority Management** – Tickets can be assigned Low, Medium, or High priority.
- **Database Integration** – MySQL stores user accounts and ticket information.
- **Responsive Interface** – CSS styling for a clean, user-friendly interface.

## Technologies Used

- PHP
- MySQL
- MySQL Workbench
- HTML5
- CSS3


## Project Structure

```text
IT Support Ticket Management System/
├── db.php
├── index.php
├── register.php
├── login.php
├── logout.php
├── dashboard.php
├── create_ticket.php
├── view_ticket.php
├── update_ticket.php
├── admin.php
└── style.css
```

## Database Setup

1. Open MySQL Workbench.
2. Create a database named `it_support_db`.
3. Create the `users` and `tickets` tables using the SQL schema provided with the project.
4. Configure the database connection in `db.php` using your local MySQL settings.
5. Keep database credentials private.

## User Roles

**User**
- Register and log in.
- Create support tickets.
- View personal tickets and their statuses.

**Administrator**
- View all support tickets.
- Manage ticket statuses.
- Monitor support requests.

## Project Objective

The main objective is to simplify IT issue reporting and ticket tracking by providing a centralized system for users and administrators.


