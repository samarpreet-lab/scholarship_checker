<?php
// Close the main-content and container divs only if they were opened
$current_page = basename($_SERVER['PHP_SELF']);
$is_auth_page = ($current_page === 'index.php' || $current_page === 'register.php');
$is_authenticated = isset($_SESSION['user_id']);

if ($is_authenticated && !$is_auth_page):
?>
</div> <!-- End main-content wrapper -->
<?php endif; ?>

</div> <!-- End container-fluid p-0 -->

<?php 
// Show footer mainly for login/register pages
if ($current_page === 'index.php' || $current_page === 'register.php'):
?>
<footer class="w-100 py-3 mt-5" style="background: transparent;">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="small fw-bold text-uppercase" style="color: #002855; letter-spacing: 0.1em; font-size: 0.7rem;">Academic Editorial Portal</div>
        <div class="d-flex gap-4 my-2 my-md-0">
            <a href="#" class="text-decoration-none text-muted small text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Privacy Policy</a>
            <a href="#" class="text-decoration-none text-muted small text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.05em;">Terms of Service</a>
        </div>
        <div class="small text-muted" style="font-size: 0.65rem;">© 2024 Academic Editorial Scholarship Fund.</div>
    </div>
</footer>
<?php endif; ?>

<!-- Bootstrap JS Code -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.sidebar') && !e.target.closest('#sidebarToggle')) {
                sidebar.classList.remove('show');
            }
        });
    }
</script>
</body>
</html>
