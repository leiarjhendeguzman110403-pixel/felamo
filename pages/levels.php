<?php 
include("components/header.php"); 
?>

<style>
    /* Hide the Default Top Navbar */
    nav.navbar {
        display: none !important;
    }

    /* Layout Wrapper */
    #layout-wrapper {
        display: flex;
        width: 100%;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Sidebar - Fixed & Visible */
    .sidebar {
        display: block !important;
        width: 280px !important;
        min-width: 280px !important;
        height: 100vh;
        position: sticky;
        top: 0;
        z-index: 1050;
        overflow-y: auto;
        background: #fff;
        border-right: 1px solid #eee;
    }

    /* Main Content Area */
    .main-content {
        flex-grow: 1;
        width: calc(100% - 280px);
        background-color: #f8f9fa;
        display: flex;
        flex-direction: column;
        padding: 2rem; /* Match home.php padding */
    }

    /* Mobile Responsive */
    @media (max-width: 991.98px) {
        .sidebar { display: none !important; }
        .main-content { width: 100%; padding: 1rem; }
    }
</style>

<input type="hidden" id="hidden_user_id" value="<?= $auth_user_id ?>">

<div id="layout-wrapper">
    
    <?php include("components/sidebar.php"); ?>

    <div class="main-content">
        
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Markahan</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                    <span data-feather="calendar"></span>
                    This week
                </button>
            </div>
        </div>

        <div id="levels-container" class="row g-3">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-main" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include("components/footer-scripts.php"); ?>
<script src="scripts/levels.js?v=8"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>
<?php include("components/footer.php"); ?>