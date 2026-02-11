</body>
<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <span class="text-muted">© 2024 Felamo. All rights reserved.</span>
    </div>
</footer>

<script>
    $(document).ready(function() {
        // Logout Confirmation Logic
        // Note: Sidebar toggle logic is now handled exclusively in the parent page (e.g., home.php)
        $(document).on('click', '#logoutBtn', function(e) {
            e.preventDefault();
            var myModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            myModal.show();
        });
    });
</script>
</body>
</html>