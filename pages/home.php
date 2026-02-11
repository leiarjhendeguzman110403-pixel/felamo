<?php
include("components/header.php");
$isSuperAdmin = $user['role'] === 'super_admin';
?>

<input type="hidden" id="hidden_user_id" value="<?= $auth_user_id ?>">
<input type="hidden" id="hidden_is_super_admin" value="<?= $isSuperAdmin ? 'true' : 'false' ?>">

<style>
    /* --- FORCE RESET --- */
    .navbar { display: none !important; }
    body { background-color: #f4f6f9; overflow-x: hidden; }
    
    /* Wrapper Layout */
    .dashboard-wrapper { 
        display: flex; 
        min-height: 100vh; 
        width: 100%; 
        overflow-x: hidden; 
        transition: all 0.3s ease;
    }

    /* --- SIDEBAR STYLES (NUCLEAR FIX) --- */
    .sidebar { 
        width: 280px !important;            /* Force fixed width */
        background: linear-gradient(180deg, #a71b1b 0%, #880f0b 100%); 
        color: white; 
        display: flex; 
        flex-direction: column; 
        padding: 20px; 
        position: fixed; 
        height: 100vh; 
        z-index: 9000 !important;           /* Very high Z-Index */
        left: 0 !important; 
        transition: left 0.3s ease !important; 
        overflow: visible !important;       /* CRITICAL: Lets the button stick out */
    }

    /* --- BUTTON STYLES (THE YELLOW ARROW) --- */
    .sidebar-toggle { 
        position: absolute !important;
        right: -30px !important;            /* Sticks out 30px to the right */
        top: 50% !important;
        width: 30px !important; 
        height: 60px !important; 
        background-color: #FFC107 !important; /* Bright Yellow */
        border: 2px solid #880f0b !important; /* Red Border to make it pop */
        border-left: none !important;
        border-radius: 0 8px 8px 0 !important; 
        display: flex !important; 
        align-items: center; 
        justify-content: center; 
        cursor: pointer; 
        color: #000 !important;             /* Black Arrow */
        z-index: 9999 !important;           /* HIGHEST Z-Index (Above Sidebar) */
        box-shadow: 4px 0 5px rgba(0,0,0,0.2) !important;
    }

    /* --- CLOSED STATE (WHEN YOU CLICK) --- */
    /* 1. Move Sidebar completely off screen (-280px) */
    .dashboard-wrapper.toggled .sidebar { 
        left: -280px !important; 
    }

    /* 2. Since button is 'right: -30px', it will sit at screen edge (0px to 30px) */
    
    /* 3. Adjust Content Margin */
    .dashboard-wrapper.toggled .main-content { 
        margin-left: 0 !important; 
    }

    /* 4. Flip the arrow */
    .dashboard-wrapper.toggled .sidebar-toggle i { 
        transform: rotate(180deg); 
    }

    /* --- REST OF DASHBOARD STYLES --- */
    .sidebar-profile { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.5); }
    .sidebar-profile img { width: 80px !important; height: 80px !important; border-radius: 50%; object-fit: cover; border: 2px solid white; max-width: 100%; display: block; }
    .sidebar-profile h5 { font-weight: bold; margin: 0; font-size: 1.2rem; text-transform: uppercase; }
    
    .nav-link-custom { display: flex; align-items: center; padding: 12px 15px; color: white; text-decoration: none; font-weight: 600; margin-bottom: 10px; transition: 0.3s; border-radius: 5px; }
    .nav-link-custom:hover { background-color: rgba(255, 255, 255, 0.2); color: white; }
    .nav-link-custom.active { background-color: #FFC107; color: #440101; }
    .nav-link-custom i { margin-right: 15px; font-size: 1.2rem; }

    .logout-btn { margin-top: auto; background-color: #FFC107; color: black; font-weight: bold; border: none; width: 100%; padding: 12px; border-radius: 25px; text-align: center; text-decoration: none; cursor: pointer; }
    .logout-btn:hover { background-color: #e0a800; color: black; }

    .main-content { flex: 1; margin-left: 280px; padding: 30px 40px; transition: all 0.3s ease; }
    .page-header { background: linear-gradient(180deg, #a71b1b 0%, #880f0b 100%); color: white; padding: 15px 30px; border-radius: 8px; font-weight: bold; font-size: 1.5rem; margin-bottom: 20px; text-transform: uppercase; }
    .stats-card { border: none; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); height: 100%; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; }
    .stats-card:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(255, 0, 0, 0.4); }
    .stats-header { background: linear-gradient(180deg, #880f0b 0%, #ce3c3c 100%); color: white; padding: 10px; text-align: center; font-weight: bold; font-size: 1.1rem; }
    .stats-body { background-color: #e5e5e5; padding: 20px; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 150px; shadow: #880f0b;}
    .stats-label { font-size: 0.9rem; font-weight: bold; color: #333; margin-bottom: 5px; }
    .stats-count { font-size: 3.5rem; font-weight: bold; color: #880f0b; line-height: 1; }
</style>

<div class="dashboard-wrapper">
    
    <?php include("components/sidebar.php"); ?>

    <main class="main-content">
        <div class="page-header">DASHBOARD</div>
        
        <div class="d-flex justify-content-end mb-4 gap-2 action-buttons">
            <button class="btn btn-main text-light" style="background-color: #880f0b;" id="btnDownload"><i class="bi bi-box-arrow-down"></i> Download</button>
            <button class="btn btn-main text-light" style="background-color: #880f0b;" id="btnDownloadStudentData"><i class="bi bi-box-arrow-down"></i> Download Student Data</button>
        </div>

        <?php if ($user['role'] == "teacher") { ?>
            <div class="row g-4">
                <?php $markahans = [['id'=>'unang','title'=>'Unang Markahan'],['id'=>'pangalawang','title'=>'Pangalawang Markahan'],['id'=>'pangatlong','title'=>'Pangatlong Markahan'],['id'=>'ika-apat-na','title'=>'Ika-apat na Markahan']]; foreach ($markahans as $m) { ?>
                <div class="col-md-6 col-lg-3">
                    <div class="stats-card">
                        <div class="stats-header"><?= $m['title'] ?></div>
                        <div class="stats-body" style="height: auto; padding-bottom: 10px;">
                            <div class="row w-100">
                                <div class="col-6"><div style="font-size: 12px;">Passed</div><h4 class="text-main fw-bold" id="<?= $m['id'] ?>-markahan-no-of-passed-student">0</h4></div>
                                <div class="col-6"><div style="font-size: 12px;">Failed</div><h4 class="text-main fw-bold" id="<?= $m['id'] ?>-markahan-no-of-failed-student">0</h4></div>
                            </div>
                            <hr class="w-100 my-2">
                            <div class="stats-label">Completed All Videos</div>
                            <h3 class="stats-count" style="font-size: 2rem;" id="<?= $m['id'] ?>-markahan-student-video-completion-count">0</h3>
                            <a id="link-<?= $m['id'] ?>-markahan" class="btn btn-sm btn-main text-light mt-2 w-100" style="background-color: #880f0b;">View Details</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="row g-4 mt-2">
                <div class="col-md-6"><div class="stats-card"><div class="stats-header">Total Sections</div><div class="stats-body"><div class="stats-label">Number of Assigned Sections</div><div class="stats-count" id="dashboard-my-section-count-count">0</div></div></div></div>
                <div class="col-md-6"><div class="stats-card"><div class="stats-header">Total Students</div><div class="stats-body"><div class="stats-label">Number Assigned Students</div><div class="stats-count" id="dashboard-my-student-count">0</div></div></div></div>
            </div>
            <div class="card mt-4 p-3 shadow-sm border-0"><canvas id="passed-failed-student-chart" style="max-height: 400px;"></canvas></div>
        <?php } ?>

        <?php if ($user['role'] == "super_admin") { ?>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3"><div class="stats-card"><div class="stats-header">Unang Markahan</div><div class="stats-body"><div class="stats-label">Videos Uploaded</div><div class="stats-count" id="unang-markahan-videos-uploaded-count">0</div></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="stats-card"><div class="stats-header">Ikalawang Markahan</div><div class="stats-body"><div class="stats-label">Videos Uploaded</div><div class="stats-count" id="pangalawang-markahan-videos-uploaded-count">0</div></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="stats-card"><div class="stats-header">Ikatlong Markahan</div><div class="stats-body"><div class="stats-label">Videos Uploaded</div><div class="stats-count" id="pangatlong-markahan-videos-uploaded-count">0</div></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="stats-card"><div class="stats-header">Ika-apat na Markahan</div><div class="stats-body"><div class="stats-label">Videos Uploaded</div><div class="stats-count" id="ika-apat-na-markahan-videos-uploaded-count">0</div></div></div></div>
            </div>
            <div class="row g-4 mt-2">
                <div class="col-md-6"><div class="stats-card"><div class="stats-header">Total App Users</div><div class="stats-body"><div class="stats-label">Number of Registered Users</div><div class="stats-count" id="dashboard-total-users-count">0</div></div></div></div>
                <div class="col-md-6"><div class="stats-card"><div class="stats-header">Total Web Users</div><div class="stats-body"><div class="stats-label">Number of Registered Users</div><div class="stats-count" id="dashboard-total-web-users-count">0</div></div></div></div>
            </div>
            <div class="card mt-4 p-3 shadow-sm border-0"><h5 class="mb-3 text-main fw-bold" style="color: #880f0b;">Uploaded Videos Analytics</h5><canvas id="videos-uploaded-chart" style="max-height: 400px;"></canvas></div>
        <?php } ?>
    </main>
</div>

<?php include("components/footer-scripts.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        console.log("Dashboard Loaded");

        // --- NUCLEAR TOGGLE LOGIC ---
        // 1. Unbind any previous clicks to prevent double-firing
        $(document).off('click', '.sidebar-toggle');
        
        // 2. Bind the new click event
        $(document).on('click', '.sidebar-toggle', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Stop bubbling
            console.log("Toggle Clicked"); // Debugging
            
            // Toggle the class on the wrapper
            $(".dashboard-wrapper").toggleClass("toggled");
        });

        // --- LOAD DATA LOGIC (UNCHANGED) ---
        const hidden_user_id = $("#hidden_user_id").val();
        const is_super_admin = $("#hidden_is_super_admin").val();

        const loadDashBoard = () => {
             $.ajax({
                type: "POST", url: "../backend/api/web/home.php",
                data: { requestType: "LoadDashboard", hidden_user_id, is_super_admin },
                success: function(response) {
                    try {
                        let res = JSON.parse(response);
                        if (is_super_admin == "true") {
                            $("#unang-markahan-videos-uploaded-count").text(res.data.vid_uploaded_count[0]?.video_count || 0);
                            $("#pangalawang-markahan-videos-uploaded-count").text(res.data.vid_uploaded_count[1]?.video_count || 0);
                            $("#pangatlong-markahan-videos-uploaded-count").text(res.data.vid_uploaded_count[2]?.video_count || 0);
                            $("#ika-apat-na-markahan-videos-uploaded-count").text(res.data.vid_uploaded_count[3]?.video_count || 0);
                            $("#dashboard-total-users-count").text(res.data.users_count.count);
                            $("#dashboard-total-web-users-count").text(res.data.web_users_count.count);
                            
                            var vidUploadedCtx = document.getElementById('videos-uploaded-chart').getContext('2d');
                            if (Chart.getChart("videos-uploaded-chart")) { Chart.getChart("videos-uploaded-chart").destroy(); }
                            var gradient = vidUploadedCtx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, '#a71b1b'); gradient.addColorStop(1, '#880f0b');
                            new Chart(vidUploadedCtx, { type: 'bar', data: { labels: ['Unang', 'Pangalawa', 'Pangatlo', 'Ika-apat'], datasets: [{ label: 'Uploaded Videos', data: [res.data.vid_uploaded_count[0]?.video_count||0, res.data.vid_uploaded_count[1]?.video_count||0, res.data.vid_uploaded_count[2]?.video_count||0, res.data.vid_uploaded_count[3]?.video_count||0], backgroundColor: gradient }] } });
                        } else {
                            $("#dashboard-my-section-count-count").text(res.data.section_count);
                            $("#dashboard-my-student-count").text(res.data.total_students);
                            var passFailedCtx = document.getElementById('passed-failed-student-chart').getContext('2d');
                            if (Chart.getChart("passed-failed-student-chart")) { Chart.getChart("passed-failed-student-chart").destroy(); }
                            new Chart(passFailedCtx, { type: 'line', data: { labels: ['Unang', 'Pangalawa', 'Pangatlo', 'Ika-apat'], datasets: [{ label: 'Passed', data: [res.data.level_stats[0].passed_count, res.data.level_stats[1].passed_count, res.data.level_stats[2].passed_count, res.data.level_stats[3].passed_count], borderColor: 'blue', backgroundColor: 'rgba(0, 0, 255, 0.1)', fill: true }, { label: 'Failed', data: [res.data.level_stats[0].failed_count, res.data.level_stats[1].failed_count, res.data.level_stats[2].failed_count, res.data.level_stats[3].failed_count], borderColor: 'green', backgroundColor: 'rgba(0, 255, 21, 0.1)', fill: true }] } });
                        }
                    } catch(e) { console.error("Parse Error", e); }
                }
            });
        }
        loadDashBoard();
        setInterval(loadDashBoard, 3000);
    });
</script>
<?php include("components/footer.php"); ?>