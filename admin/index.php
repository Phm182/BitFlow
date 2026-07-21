<?php
require_once __DIR__ . '/bootstrap.php';

if (admin_user() !== null) {
    admin_redirect('dashboard.php');
}

admin_redirect(admin_count_users($conn) === 0 ? 'setup.php' : 'login.php');

