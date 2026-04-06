</div> <!-- End main container fluid -->

<?php 
// Show a footer mainly for login/register pages
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page === 'index.php' || $current_page === 'register.php'):
?>
<footer class="position-absolute bottom-0 w-100 py-3" style="background: transparent; z-index: 10;">
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
</body>
</html>
