<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style.css">
   
    <title>School Event Tracking System</title>
    <style>
        
    </style>
</head>
<body>


     <!-- Separate to index -->
    <!-- Login Page -->
    <div id="loginPage" class="login-container">
        <div class="login-box">
            <div class="login-left">
                <div class="login-icon">🎓</div>
                <h1>EventTrack</h1>
                <p>Your comprehensive school event management system. Track attendance, manage events, and stay connected with your school community.</p>
            </div>
            <div class="login-right">
                <div class="login-header">
                    <h2>Welcome Back!</h2>
                    <p>Sign in to continue to your account</p>
                </div>

                <div class="role-switch">
                    <button class="role-btn active" onclick="switchRole('student')">Student</button>
                    <button class="role-btn" onclick="switchRole('admin')">Admin</button>
                </div>

                <form id="loginForm" onsubmit="handleLogin(event)">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" placeholder="Enter your username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
                        </div>
                    </div>

                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" id="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
     
                    <button type="submit" class="login-btn">Sign In</button>
                
                </form>

                <div class="divider">OR</div>

                <div class="signup-link">
                    Don't have an account? <a href="register.html">Sign up now</a>
                </div>
            </div>
        </div>
    </div>
     



    <!-- -->
    <!-- Dashboard -->
    <div id="dashboardPage" class="dashboard">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

        <div class="sidebar">
            <div class="logo">🎓 EventTrack</div>
            
            <div class="user-info">


                
                 <!--profile picture -->
                <div class="profile-photo-wrapper">
               <img id="sidebarProfilePhoto" src="uploads/profile/default_profile.png" alt="Profile Photo">
                </div>
                <div class="user-name" id="displayUserName">Student Name</div>
                <div class="user-role" id="displayUserRole">Student</div>
            </div>

            <div class="nav-item active" onclick="showPage('dashboard')">
                <span class="nav-icon">📊</span>
                Dashboard
            </div>
            <div class="nav-item" onclick="showPage('students')" id="navStudents">
                <span class="nav-icon">👥</span>
                Students
            </div>
            <div class="nav-item" onclick="showPage('events')" id="navEvents">
                <span class="nav-icon">📅</span>
                Events
            </div>
            <div class="nav-item" onclick="showPage('attendance')">
                <span class="nav-icon">✓</span>
                Attendance
            </div>
            <div class="nav-item" onclick="showPage('organizers')" id="navOrganizers">
                <span class="nav-icon">👤</span>
                Organizers
            </div>
            <div class="nav-item" onclick="showPage('feedback')">
                <span class="nav-icon">💬</span>
                Feedback
            </div>
            <div class="nav-item" onclick="showPage('announcements')">
                <span class="nav-icon">📢</span>
                Announcements
            </div>

            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>

        
        <div class="main-content">



            <!-- Dashboard Page -->
            <div class="page-content active" id="dashboard">
                <div class="header">
                    <h1 id="welcomeMessage">Welcome to Event Tracking</h1>
                    <p>Manage your school events efficiently</p>
                </div>

                <div class="stats-grid" id="dashboardStats">
                    <div class="stat-card" style="animation-delay: 0s;">
                        <h3>Total Students</h3>
                        <div class="number" id="statTotalStudents">0</div>
                        <div class="label">Registered</div>
                    </div>
                    <div class="stat-card" style="animation-delay: 0.1s;">
                        <h3>Active Events</h3>
                        <div class="number" id="statActiveEvents">0</div>
                        <div class="label">This Month</div>
                    </div>
                    <div class="stat-card" style="animation-delay: 0.2s;">
                        <h3>Attendance Rate</h3>
                        <div class="number" id="statAttendanceRate">0%</div>
                        <div class="label">Overall</div>
                    </div>
                    <div class="stat-card" style="animation-delay: 0.3s;">
                        <h3>Organizers</h3>
                        <div class="number" id="statOrganizers">0</div>
                        <div class="label">Staff & Admin</div>
                    </div>
                </div>

                <div class="content-section">
                    <h2 class="section-title">📅 Upcoming Events</h2>
                    <div class="table-container">
                        <table id="upcomingEventsTable">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Registered</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- dynamically filled -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="content-section">
                    <h2 class="section-title">🏫 Organizations</h2>
                    <div class="table-container">
                        <table id="dashboardOrgsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Members</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- dynamically filled -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- Students Page -->
            <div class="page-content" id="students">
                <div class="header">
                    <h1>Student Management</h1>
                    <p>View and manage student records</p>
                </div>
                <div class="content-section">
                    <h2 class="section-title">👥 Student List</h2>
                    <div class="table-container">
                        <div style="margin-bottom: 12px;">
                            <label>Select Organization: </label>
                            <select id="studentsOrgFilter"></select>
                        </div>
                        <table id="studentsTable">
                            <thead>
                                <tr>
                                    <th>Student No</th>
                                    <th>Full Name</th>
                                    <th>Course</th>
                                    <th>Section</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Events Page -->
            <div class="page-content" id="events">
                <div class="header">
                    <h1>Event Management</h1>
                    <p>Create and manage school events</p>
                </div>
                <div class="content-section">
                    <h2 class="section-title">📅 Create Event</h2>
                    <div style="display:grid;gap:12px;grid-template-columns: repeat(auto-fit,minmax(220px,1fr));margin-bottom:12px;">
                        <select id="eventOrgSelect"></select>
                        <input id="eventName" placeholder="Event name" />
                        <input id="eventDate" type="date" />
                        <input id="eventLocation" placeholder="Location" />
                        <button class="btn btn-primary" onclick="createEvent()">Create</button>
                    </div>
                    <h2 class="section-title">📋 All Events</h2>
                    <div class="table-container">
                        <table id="eventsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Organization</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- Attendance Page -->
            <div class="page-content" id="attendance">
                <div class="header">
                    <h1>Attendance Tracking</h1>
                    <p>Monitor event attendance records</p>
                </div>
                <div class="content-section">
                    <h2 class="section-title">✓ Attendance Checklist</h2>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px;">
                        <select id="attOrgSelect"></select>
                        <select id="attEventSelect"></select>
                        <button class="btn btn-primary" onclick="loadAttendanceRoster()">Load Roster</button>
                        <button class="btn btn-primary" onclick="saveAttendance()">Save Attendance</button>
                    </div>
                    <div class="table-container">
                        <table id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>Student No</th>
                                    <th>Name</th>
                                    <th>Present</th>
                                    <th>Late</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- Organizers Page -->
            <div class="page-content" id="organizers">
                <div class="header">
                    <h1>Organizer Management</h1>
                    <p>Manage event organizers, classes/organizations, and staff</p>
                </div>
                <div class="content-section">
                    <h2 class="section-title">🏫 Create Organization / Class</h2>
                    <div style="display:grid;gap:12px;grid-template-columns: repeat(auto-fit,minmax(220px,1fr));margin-bottom:12px;">
                        <input id="orgName" placeholder="Organization/Class name" />
                        <input id="orgDescription" placeholder="Description" />
                        <button class="btn btn-primary" onclick="createOrganization()">Create</button>
                    </div>

                    <h2 class="section-title">👥 Add Member (Student)</h2>
                    <div style="display:grid;gap:12px;grid-template-columns: repeat(auto-fit,minmax(220px,1fr));margin-bottom:12px;">
                        <select id="orgSelectForMember"></select>
                        <input id="memberStudentNo" placeholder="Student Number (e.g., 2025-001)" />
                        <button class="btn btn-primary" onclick="addMemberToOrganization()">Add</button>
                    </div>

                    <h2 class="section-title">📦 Organizations</h2>
                    <div class="table-container">
                        <table id="orgsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Members</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- Feedback Page -->
            <div class="page-content" id="feedback">
                <div class="header">
                    <h1>Event Feedback</h1>
                    <p>View student feedback and ratings</p>
                </div>
                <div class="content-section">
                    <h2 class="section-title">💬 Recent Feedback</h2>
                    <p>Feedback interface coming soon...</p>
                </div>
            </div>



            <!-- Announcements Page -->
            <div class="page-content" id="announcements">
                <div class="header">
                    <h1>Announcements</h1>
                    <p>Create and manage event announcements</p>
                </div>
                <div class="content-section">
                    <h2 class="section-title">📢 Latest Announcements</h2>
                    <p>Announcements interface coming soon...</p>
                </div>
            </div>
        </div>

        <button class="floating-add" title="Add New">+</button>
    
    
    </div>

  
</div>
<div id="logoutAlert" class="logout-alert hidden">
  <div class="logout-alert-content">
    <h3>Confirm Logout</h3>
    <p>Are you sure you want to logout?</p>
    <div class="logout-alert-buttons">
      <button id="confirmLogout">Yes, Logout</button>
      <button id="cancelLogout">Cancel</button>
    </div>
  </div>
</div>


      <script src="scripts.js" defer></script>
</body>
</html>