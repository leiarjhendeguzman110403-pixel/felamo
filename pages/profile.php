<?php 
// 1. Include the global header (starts session, loads user data)
include("components/header.php"); 

// 2. Define role and user variables safely
$isSuperAdmin = isset($user['role']) && $user['role'] === 'super_admin'; 
$currentUserId = isset($user['id']) ? $user['id'] : (isset($auth_user_id) ? $auth_user_id : 0);
?>

<input type="hidden" id="hidden_user_id" value="<?= $currentUserId ?>">

<style>
    /* --- SHARED CSS (Matches Home.php) --- */
    
    /* 1. Page & Layout Reset */
    .navbar, header, .main-header { display: none !important; }
    body { background-color: #f4f6f9; overflow-x: hidden; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .dashboard-wrapper { display: flex; min-height: 100vh; width: 100%; overflow-x: hidden; position: relative; }

    /* 2. SIDEBAR STYLING (Copied from Home.php) */
    .sidebar { 
        width: 280px; 
        background: linear-gradient(180deg, #a71b1b 0%, #880f0b 100%); 
        color: white; 
        display: flex; 
        flex-direction: column; 
        padding: 20px; 
        position: fixed; 
        height: 100vh; 
        z-index: 1000; 
        left: 0; 
        transition: all 0.3s ease; 
        overflow: visible !important; 
    }

    /* Sidebar Profile */
    .sidebar-profile { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.5); }
    .sidebar-profile img { width: 80px !important; height: 80px !important; border-radius: 50%; object-fit: cover; border: 2px solid white; max-width: 100%; display: block; }
    .sidebar-profile h5 { font-weight: bold; margin: 0; font-size: 1.2rem; text-transform: uppercase; color: white; }
    .sidebar-profile small { color: rgba(255,255,255,0.8); }

    /* Sidebar Links */
    .nav-link-custom { display: flex; align-items: center; padding: 12px 15px; color: white; text-decoration: none; font-weight: 600; margin-bottom: 10px; transition: 0.3s; border-radius: 5px; }
    .nav-link-custom:hover { background-color: rgba(255, 255, 255, 0.2); color: white; }
    .nav-link-custom.active { background-color: #FFC107; color: #440101; }
    .nav-link-custom i { margin-right: 15px; font-size: 1.2rem; }

    /* Sidebar Buttons (Logout & Toggle) */
    .logout-btn { margin-top: auto; background-color: #FFC107; color: black; font-weight: bold; border: none; width: 100%; padding: 12px; border-radius: 25px; text-align: center; text-decoration: none; cursor: pointer; }
    .logout-btn:hover { background-color: #e0a800; color: black; }

    .sidebar-toggle { position: absolute; right: -30px; top: 50%; width: 30px; height: 60px; background-color: #FFC107; border-radius: 0 4px 4px 0; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #333; transition: right 0.3s ease; z-index: 1001; }
    .sidebar-toggle i { transition: transform 0.3s ease; }

    /* Toggled State */
    .dashboard-wrapper.toggled .sidebar { left: -280px; }
    .dashboard-wrapper.toggled .sidebar-toggle { right: -30px; }
    .dashboard-wrapper.toggled .sidebar-toggle i { transform: rotate(180deg); }
    .dashboard-wrapper.toggled .main-content { margin-left: 0; }

    /* 3. MAIN CONTENT STYLING */
    .main-content { 
        flex: 1; 
        margin-left: 280px; /* Pushes content right */
        padding: 30px 40px; 
        transition: all 0.3s ease; 
        width: calc(100% - 280px);
    }

    /* 4. PROFILE SPECIFIC STYLES */
    .profile-header-banner {
        background: linear-gradient(90deg, #a71b1b 0%, #d32f2f 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(167, 27, 27, 0.3);
        font-weight: 800;
        font-size: 1.8rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        display: flex;
        align-items: center;
    }
    .profile-header-banner i { margin-right: 15px; font-size: 2rem; }

    .profile-card {
        background: white;
        border-radius: 12px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        padding: 35px;
        margin-bottom: 30px;
    }

    .section-title {
        color: #a71b1b;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
    }

    /* Form Styles */
    .input-group-text { background-color: #f8f9fa; border: 1px solid #e0e0e0; border-right: none; color: #a71b1b; font-size: 1.1rem; border-radius: 8px 0 0 8px; }
    .form-control-custom { background-color: #f8f9fa; border: 1px solid #e0e0e0; border-left: none; padding: 12px 15px; font-weight: 500; color: #495057; border-radius: 0 8px 8px 0; transition: all 0.3s; }
    .form-control-custom:focus { background-color: #fff; box-shadow: 0 0 0 3px rgba(167, 27, 27, 0.1); border-color: #a71b1b; }
    .input-group { margin-bottom: 25px; }
    .form-label { font-size: 0.85rem; font-weight: 600; color: #6c757d; margin-bottom: 8px; text-transform: uppercase; }

    .btn-update {
        background: linear-gradient(90deg, #a71b1b 0%, #c62828 100%);
        color: white;
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        border: none;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        margin-top: 15px;
        box-shadow: 0 4px 6px rgba(167, 27, 27, 0.2);
    }
    .btn-update:hover {
        background: linear-gradient(90deg, #8e1616 0%, #b71c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(167, 27, 27, 0.3);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) { 
        .main-content { margin-left: 0; width: 100%; padding: 20px; }
        .dashboard-wrapper.toggled .sidebar { left: 0; } /* Show sidebar when toggled on mobile */
        .sidebar { left: -280px; } /* Hide by default on mobile */
        .sidebar-toggle { right: -30px; }
    }
</style>

<div class="dashboard-wrapper">
    
    <?php include("components/sidebar.php"); ?>

    <main class="main-content">
        <div class="profile-header-banner">
            <i class="bi bi-person-badge"></i> User Profile
        </div>

        <div class="profile-card">
            <div class="section-title">
                <i class="bi bi-pencil-square"></i> Edit Account Details
            </div>

            <form id="editUserForm">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" id="name" name="name" class="form-control form-control-custom" required placeholder="Enter your full name" value="<?= isset($user['name']) ? htmlspecialchars($user['name']) : '' ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" id="email" name="email" class="form-control form-control-custom" required placeholder="Enter your email" value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>">
                        </div>
                    </div>

                    <div class="col-12"><hr class="my-4" style="opacity: 0.1;"></div>

                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" id="newPassword" name="newPassword" class="form-control form-control-custom" placeholder="Leave blank to keep current">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-check-circle-fill"></i></span>
                            <input type="password" id="newPassword2" name="newPassword2" class="form-control form-control-custom" placeholder="Repeat new password">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-update" id="btnUpdate">
                    <i class="bi bi-save me-2"></i> Save Changes
                </button>
            </form>
        </div>
    </main>
</div>

<?php include("components/footer-scripts.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        
        // --- 1. SIDEBAR TOGGLE LOGIC ---
        // Matches Home.php logic exactly
        $(document).on('click', '.sidebar-toggle', function() {
            $(".dashboard-wrapper").toggleClass("toggled");
        });

        // --- 2. PROFILE UPDATE LOGIC ---
        $("#editUserForm").on('submit', function(e) {
            e.preventDefault();
            
            const btn = $("#btnUpdate");
            const originalText = btn.html();
            
            const userId = $("#hidden_user_id").val();
            const name = $("#name").val().trim();
            const email = $("#email").val().trim();
            const p1 = $("#newPassword").val();
            const p2 = $("#newPassword2").val();

            if(name === "" || email === "") {
                Swal.fire('Error', 'Name and Email are required.', 'error');
                return;
            }

            if(p1 !== "" && p1 !== p2) {
                Swal.fire('Error', 'Passwords do not match!', 'error');
                return;
            }

            // Prepare Data for WEB API
            let dataToSend = {
                requestType: 'EditUser',
                auth_user_id: userId,
                name: name,
                email: email,
                newPassword: p1
            };

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

            $.ajax({
                url: '../backend/api/web/auth.php', 
                type: 'POST',
                data: dataToSend,
                dataType: 'json', 
                success: function(response) {
                    btn.prop('disabled', false).html(originalText);
                    
                    if (response.status === 'success' || response.status === 200) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message || 'Profile updated successfully.',
                            icon: 'success',
                            confirmButtonColor: '#a71b1b'
                        }).then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire('Error', response.message || 'Failed to update profile.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    btn.prop('disabled', false).html(originalText);
                    console.error("Error:", error);
                    let errorMsg = 'An unexpected error occurred.';
                    if(xhr.responseText) {
                        let tempDiv = document.createElement('div');
                        tempDiv.innerHTML = xhr.responseText;
                        errorMsg += " Server says: " + (tempDiv.innerText.substring(0, 50) + "...");
                    }
                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });
    });
</script>

<?php include("components/footer.php"); ?>