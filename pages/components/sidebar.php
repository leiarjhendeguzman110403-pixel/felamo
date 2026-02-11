<?php
// pages/components/sidebar.php
$currentUserRole = $user['role'] ?? 'guest';
$currentUserName = $user['name'] ?? 'User';
$isSuperAdmin = $currentUserRole === 'super_admin';
$current_page = basename($_SERVER['PHP_SELF']); 
?>

<aside class="sidebar">
    <div class="sidebar-profile">
        <img src="../backend/storage/assets/logo.png" alt="Profile"> 
        <div>
            <a href="profile.php" style="color: inherit; text-decoration: none;">
                <h5><?= htmlspecialchars($currentUserName) ?></h5>
            </a>
            <small style="opacity: 0.8; font-size: 0.7rem;">
                <?= $isSuperAdmin ? 'SUPER ADMIN' : strtoupper($currentUserRole) ?>
            </small>
        </div>
    </div>

    <nav class="nav flex-column">
        <a href="home.php" class="nav-link-custom <?= $current_page == 'home.php' ? 'active' : '' ?>">
            <i class="bi bi-house-door-fill"></i> HOME
        </a>
        
        <?php if ($currentUserRole == "teacher") { ?>
            <a href="levels.php" class="nav-link-custom <?= $current_page == 'levels.php' ? 'active' : '' ?>">
                <i class="bi bi-journal-bookmark-fill"></i> MARKAHAN
            </a>
            <a href="my_sections.php" class="nav-link-custom <?= $current_page == 'my_sections.php' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> SECTIONS
            </a>
            <a href="students.php" class="nav-link-custom <?= $current_page == 'students.php' ? 'active' : '' ?>">
                <i class="bi bi-person-badge-fill"></i> STUDENTS
            </a>
            <a href="notifications.php" class="nav-link-custom <?= $current_page == 'notifications.php' ? 'active' : '' ?>">
                <i class="bi bi-bell-fill"></i> NOTIFICATIONS
            </a>
        <?php } ?>

        <?php if ($currentUserRole == "super_admin") { ?>
            <a href="teachers.php" class="nav-link-custom <?= ($current_page == 'teachers.php' || $current_page == 'assign_sections.php' || $current_page == 'assign_students-v2.php') ? 'active' : '' ?>">
                <i class="bi bi-person-video3"></i> TEACHER
            </a>
            <a href="students.php" class="nav-link-custom <?= ($current_page == 'students.php' || $current_page == 'section_students.php') ? 'active' : '' ?>">
                <i class="bi bi-person-fill"></i> STUDENT
            </a>
        <?php } ?>
    </nav>

    <button class="logout-btn" type="button" data-bs-toggle="modal" data-bs-target="#logoutModal">
        LOG OUT
    </button>
</aside>

<div class="sidebar-toggle" id="sidebarToggleBtn">
    <i class="bi bi-chevron-left"></i>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-0 fs-5">Are you sure you want to log out?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="../logout.php" class="btn btn-danger">Yes, Log Out</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* --- SIDEBAR --- */
    .sidebar { 
        width: 280px !important;            
        background: linear-gradient(180deg, #a71b1b 0%, #880f0b 100%) !important; 
        color: white !important; 
        display: flex !important; 
        flex-direction: column !important; 
        padding: 20px !important; 
        position: fixed !important; 
        top: 0 !important; bottom: 0 !important; left: 0 !important; 
        z-index: 1000 !important;           
        transition: transform 0.3s ease !important;
    }

    /* --- THE BUTTON (FIXED TO SCREEN, NOT SIDEBAR) --- */
    .sidebar-toggle { 
        position: fixed !important; /* Fixed to the viewport */
        left: 280px !important;     /* Starts exactly where sidebar ends */
        top: 50% !important;
        width: 30px !important; 
        height: 60px !important; 
        background-color: #FFC107 !important; 
        border: 1px solid #b38f00 !important; 
        border-left: none !important;
        border-radius: 0 8px 8px 0 !important; 
        display: flex !important; 
        align-items: center; 
        justify-content: center; 
        cursor: pointer !important; 
        color: #000 !important;             
        z-index: 9999 !important;   /* Above everything */
        transition: left 0.3s ease !important; /* Animate the slide */
        box-shadow: 4px 0 5px rgba(0,0,0,0.2) !important;
    }

    /* --- CLOSED STATE --- */
    
    /* 1. Slide Sidebar Off-Screen */
    .dashboard-wrapper.toggled .sidebar { 
        transform: translateX(-280px) !important; 
    }

    /* 2. Slide Button to Left Edge (0px) */
    .dashboard-wrapper.toggled .sidebar-toggle { 
        left: 0px !important; 
    }

    /* 3. Adjust Content Gap */
    .dashboard-wrapper.toggled .main-content { 
        margin-left: 0 !important; 
    }

    /* 4. Flip Arrow */
    .dashboard-wrapper.toggled .sidebar-toggle i { 
        transform: rotate(180deg) !important; 
    }
</style>