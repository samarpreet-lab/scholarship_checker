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

<footer class="w-100 py-3 mt-5 footer-main">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="footer-brand">Academic Editorial Portal</div>
        <div class="d-flex gap-4 my-2 my-md-0">
            <a href="#" class="text-decoration-none text-muted small text-uppercase fw-bold footer-link">Privacy Policy</a>
            <a href="#" class="text-decoration-none text-muted small text-uppercase fw-bold footer-link">Terms of Service</a>
        </div>
        <div class="small text-muted footer-copyright">© 2026 Academic Editorial Scholarship Fund.</div>
    </div>
</footer>

<!-- Bootstrap JS Code -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
