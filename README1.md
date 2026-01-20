# How to set up Apache
1. Install apache link [https://httpd.apache.org/docs/2.4/configuring.html]
2. install httpd.exe -k install
3. Make sure Apache is running by looking up: http://localhost/

4. Create directory in htdocs

5. Create .php files and using localhost link like this: [http://localhost/CyberSecurity_Final/login.php]

# Directory structure
- Root file: "C:\Users\emros\Apache24\Apache24\htdocs\CyberSecurity_Final"
### `/Classes/` - Course Management
Organized by subject areas with course-specific resources.
- Classes/
├── Networking/
│   ├── NET101/               # Networking 101 course
│   │   ├── Assignments/      # Course assignments
│   │   ├── FacultyNotes/     # Instructor notes
│   │   └── LectureNotes/     # Lecture materials
│   └── NET202/               # Advanced Networking course
│       ├── Assignments/
│       ├── FacultyNotes/
│       └── LectureNotes/
│
├── People/
│   ├── Faculty/              # Faculty member information
│   └── Students/             # Student information
│
├── Programming/
│   ├── PRO101/               # Programming 101 course
│   │   ├── Assignments/
│   │   ├── FacultyNotes/
│   │   └── LectureNotes/
│   └── PRO202/               # Advanced Programming course
│       ├── Assignments/
│       ├── FacultyNotes/
│       └── LectureNotes/
│
├── Programming_A/
│   ├── Assignments/          # Programming alternative course
│   └── FacultyNotes/
│
└── Security/
    ├── SEC101/               # Cybersecurity 101 course
    │   ├── Assignments/
    │   ├── FacultyNotes/
    │   └── LectureNotes/
    └── SEC202/               # Advanced Cybersecurity course
        ├── Assignments/
        ├── FacultyNotes/
        └── LectureNotes/
###  Lib - Library & Dependencies
Python packages and site-packages directory for project dependencies.
Lib/
├── site-packages/            # Python installed packages
│   └── pip/                  # Python package manager
│       ├── __internal/       # Internal pip modules
│       ├── cli/              # Command-line interface
│       ├── commands/         # Pip commands
│       ├── distributions/    # Distribution handling
│       ├── index/            # Index management
│       ├── locations/        # Path configurations
│       ├── metadata/         # Package metadata
│       ├── models/           # Data models
│       ├── network/          # Network operations
│       ├── operations/       # Package operations
│       ├── req/              # Requirements handling
│       ├── resolution/       # Dependency resolution
│       ├── utils/            # Utility functions
│       ├── vcs/              # Version control systems
│       └── _vendor/          # Vendored dependencies
└── Scripts/
    ├── pip.exe               # Pip executable
    ├── pip3.exe              # Pip 3 executable
    └── pip3.14.exe           # Pip 3.14 executable

## File Organization
Each course  section directory has:
- **Assignments/** - Course assignments and projects
- **FacultyNotes/** - Instructor-created study materials
- **LectureNotes/** - Lecture slides and notes
### People [groups]
- **Faculty/**: Faculty member profiles and information
- **Students/**: Student profiles and enrollment data
- **Public/**: Accessable to anyone

# Permissions and groups:
- Group 1: Students [students.php] only have assess to things based on their student credentials (They have Read & Execute access to classes they are added to. If they have access to the class, they have read, execute and write permissions)
- Group 2: Teachers [teacher.php] only have assess to things based on their teacher credentials (They have Full permissions)
- Group 3: Public [public.php] only have access to what people with no credentials can see (Only have read and execute permissions)


# Security:
Authentication & passwords:
- `hashing.php` and `users_hashed.json`  Keep passcodes safe & hidden 
-  `login.php` / `Logout.php` allows for a fixed begining and end of a session [`session_regenerate_id()]
- Correct role, password and username is required to access specific directories and interact with them. 


## Other information

1. User Roles 
Student: See THEIR classes, see/submit assignments, see lecture notes
Teacher: See all classes, add students to classes, upload and see assignments/lecture notes/faculty notes
Other: View public files

2. Steps to Complete
Set up Apache with PHP and pip
Make folders 
"C:\Users\emros\Apache24\Apache24\htdocs\CyberSecurity_Final\Classes"
"C:\Users\emros\Apache24\Apache24\htdocs\CyberSecurity_Final\Classes\Networking"
"C:\Users\emros\Apache24\Apache24\htdocs\CyberSecurity_Final\Classes\Programming"
"C:\Users\emros\Apache24\Apache24\htdocs\CyberSecurity_Final\Classes\Security"
** Section folders→ in subfolders: Assignments, Faculty Notes, Lecture Notes
3. Groups 
- see Stripling_Web_Final.pdf
- see Stripling_Web_Final(1).pdf
4. Authentication & Authorization & Role-Based Access Control (RBAC)
Hashing implemented
User name + password + role → stored in .json file [sqlite3 is something I wanted to use]
Access to student.php or teacher.php is based on role in .json file 
Faculty: ‘Teacher.php’ special version of ‘assigment.php’, ‘upload.php’, have to add students to classes before they can see. Can upload and see faculty notes on a class. 
All based on a specific Section of a class. 
Student: Can see the classes the teacher added them to. Can see + upload assignments for classes they’ve been added to. Can see lecture notes for classes they’ve been added to
Others: Just portal with public uploads that don't need a username or passcode
Used if-else statements to ensure role specific access for each session. 
️ 5. Functional Requirements
Login: see hashing.php, login.php, users.json, users_hashed.json, Logout.php
Role based nav: see student.php, teacher.php, public.php
File Upload & Download: See upload.php, assignment.php, faculty_notes.php, lecture_notes.php, student_upload.php
File Listing: Students can see classes based on: teachers adding to Classes.php and see_classes.php, class_students.json
Security Measures: permissions by group. Permission by role. URL hidden

6. Extra credit? 
Hashing through hashing.php → user.json → output of users_hashed.json
Uploads/downloads in folders of directory → uploads shown
Search: All tabs have searching by class and section 
All sections have searching based on assignments, lecture notes or faculty notes based on access. 
