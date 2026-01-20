# School Directory Project

**Class:** Cybersecurity Final Project  
**Course:** Web Application Security & Development

## Overview

The School Directory Project is a comprehensive PHP-based web application that implements role-based access control (RBAC) for managing course materials, student enrollments, and file uploads in an educational environment. The system provides secure authentication, authorization, and file management capabilities for three distinct user roles: Students, Teachers, and Public users.

## Table of Contents

- [Skills Learned](#skills-learned)
- [Project Structure](#project-structure)
- [Setup Instructions](#setup-instructions)
- [User Roles & Permissions](#user-roles--permissions)
- [Security Features](#security-features)
- [Functional Requirements](#functional-requirements)
- [Extra Features](#extra-features)
- [Key Files](#key-files)
- [Future Improvements](#future-improvements)
- [Project Repositories](#project-repositories)
- [License](#license)

## Skills Learned
1. **Web Security**
   - Password hashing and secure authentication
   - Session management and timeout handling
   - Role-based access control (RBAC)
   - Input validation and sanitization

2. **PHP Development**
   - Server-side scripting with PHP
   - File system operations
   - JSON data handling
   - Session management

3. **Web Application Architecture**
   - MVC-like structure organization
   - Separation of concerns
   - Role-based UI rendering

4. **Apache Configuration**
   - Apache HTTP Server setup
   - PHP integration
   - Directory structure management

5. **File Management**
   - Secure file upload handling
   - Directory traversal prevention
   - File organization and access control
  
## Project Structure

### Root Directory
```
C:\Users\emros\Apache24\Apache24\htdocs\CyberSecurity_Final
```

### Core Components

#### `/Classes/` - Course Management
Organized by subject areas with course-specific resources:
- **Networking/** - NET101, NET202 courses
- **Programming/** - PRO101, PRO202 courses  
- **Security/** - SEC101, SEC202 courses
- **Programming_A/** - Alternative programming course

Each course section contains:
- **Assignments/** - Course assignments and projects
- **FacultyNotes/** - Instructor-created study materials
- **LectureNotes/** - Lecture slides and notes

#### `/People/` - User Groups
- **Faculty/** - Faculty member information
- **Students/** - Student profiles and enrollment data
- **Public/** - Accessible to anyone

#### `/Lib/` - Dependencies
Python packages and site-packages directory for project dependencies.

## Setup Instructions

### Prerequisites
- Apache HTTP Server 2.4+
- PHP 7.4+ with session support
- Python 3.x (for pip dependencies)

### Installation Steps

1. **Install Apache**
   - Download from: [https://httpd.apache.org/docs/2.4/configuring.html](https://httpd.apache.org/docs/2.4/configuring.html)
   - Install using: `httpd.exe -k install`
   - Verify installation: Navigate to `http://localhost/`

2. **Create Project Directory**
   - Create directory in Apache `htdocs` folder:
     ```
     C:\Users\emros\Apache24\Apache24\htdocs\CyberSecurity_Final
     ```

3. **Set Up Course Structure**
   Create the following folders:
   ```
   Classes/
   Classes/Networking/
   Classes/Programming/
   Classes/Security/
   ```
   Each course section should have subfolders: `Assignments/`, `FacultyNotes/`, `LectureNotes/`

4. **Access Application**
   - Navigate to: `http://localhost/CyberSecurity_Final/login.php`

## User Roles & Permissions

### Group 1: Students (`student.php`)
- **Access:** Based on student credentials
- **Permissions:** 
  - Read & Execute access to classes they are enrolled in
  - Read, Execute, and Write permissions for enrolled classes
  - Can view and submit assignments
  - Can view lecture notes
  - Can see only classes they've been added to by teachers

### Group 2: Teachers (`teacher.php`)
- **Access:** Based on teacher credentials
- **Permissions:**
  - Full permissions to all classes
  - Can add students to classes
  - Can upload assignments, lecture notes, and faculty notes
  - Can view all course materials
  - Can manage student enrollments

### Group 3: Public (`public.php`)
- **Access:** No credentials required
- **Permissions:**
  - Read and Execute permissions only
  - Access to public files only

## Security Features

### Authentication & Password Security
- **Password Hashing:** `hashing.php` converts plaintext passwords to secure hashes stored in `users_hashed.json`
- **Session Management:** `login.php` and `Logout.php` implement secure session handling with `session_regenerate_id()`
- **Session Timeout:** 5-minute inactivity timeout with automatic logout
- **Role-Based Access:** Correct username, password, and role required for directory access

### Authorization
- Role-based access control (RBAC) implemented through session variables
- If-else statements ensure role-specific access for each session
- URL protection prevents unauthorized access

### Data Storage
- User credentials stored in `users_hashed.json` (hashed passwords)
- Class-student enrollments stored in `class_students.json`
- Note: SQLite3 was considered but JSON files were used for this implementation

## Functional Requirements

### Login System
- **Files:** `hashing.php`, `login.php`, `users.json`, `users_hashed.json`, `Logout.php`
- Secure password hashing and verification
- Session-based authentication
- Role-based redirection after login

### Role-Based Navigation
- **Files:** `student.php`, `teacher.php`, `public.php`
- Different interfaces based on user role
- Role-specific menu options and capabilities

### File Upload & Download
- **Files:** `upload.php`, `assigment.php`, `faculty_notes.php`, `lecture_notes.php`, `student_upload.php`
- Role-based upload permissions
- Organized file storage by class and section
- File listing and download capabilities

### File Listing & Class Management
- **Files:** `Classes.php`, `see_classes.php`, `class_students.json`
- Teachers can add students to classes
- Students can see only their enrolled classes
- Dynamic class and section detection

### Security Measures
- Permissions by group
- Permissions by role
- URL protection
- Session timeout and regeneration

## Extra Features

1. **Password Hashing:** Secure password storage using PHP's `password_hash()` function
2. **File Organization:** Uploads/downloads organized in folder structure with proper display
3. **Search Functionality:** All tabs support searching by class and section
4. **Section-Based Search:** Search based on assignments, lecture notes, or faculty notes based on access level

## Key Files

### Authentication
- `login.php` - User authentication and session management
- `Logout.php` - Session termination
- `hashing.php` - Password hashing utility
- `users_hashed.json` - Hashed user credentials

### User Interfaces
- `student.php` - Student portal
- `teacher.php` - Teacher portal
- `public.php` - Public access portal

### File Management
- `upload.php` - File upload interface (teachers)
- `student_upload.php` - Student file upload
- `assigment.php` - Assignment management
- `faculty_notes.php` - Faculty notes management
- `lecture_notes.php` - Lecture notes management

### Class Management
- `Classes.php` - Add students to classes (teachers)
- `see_classes.php` - View enrolled classes (students)
- `class_students.json` - Class enrollment data

### Utilities
- `db.php` - Database helper functions
- `style.css` - Application styling
- `reset_class.php` - Reset class/section selection


## Future Improvements

If continuing this project, the following improvements would be implemented:

1. **Database Migration**
   - Migrate from JSON files to SQLite3 or MySQL
   - Implement proper database schema
   - Add database connection pooling

2. **Enhanced Security**
   - Implement CSRF protection tokens
   - Add rate limiting for login attempts
   - Implement password strength requirements
   - Add two-factor authentication (2FA)
   - Implement file type validation and virus scanning

3. **User Experience**
   - Improve UI/UX with modern CSS framework (Bootstrap/Tailwind)
   - Add responsive design for mobile devices
   - Implement AJAX for dynamic content loading
   - Add file preview functionality

4. **Features**
   - Email notifications for assignments and grades
   - Grade management system
   - Discussion forums for each class
   - Calendar integration for due dates
   - File versioning and history

5. **Code Quality**
   - Refactor code into classes and functions
   - Implement proper error handling and logging
   - Add unit tests and integration tests
   - Follow PSR coding standards

6. **Performance**
   - Implement caching mechanisms
   - Optimize file upload handling
   - Add pagination for large file lists
   - Database query optimization

7. **Documentation**
   - Add inline code documentation
   - Create API documentation
   - Add user manuals
   - Create deployment guides

## Project Repositories

This project is organized into two main repositories:

1. **Authentication & Authorization System** - Core security and user management
2. **File Management & Course System** - Course materials and file handling

See individual repository READMEs for detailed information.

## License

This project was developed as part of a Cybersecurity course final project.

