<?php
include("header_inner.php");
error_reporting(0);

if (empty($_COOKIE[$Cook_Name])) {
    header("Location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Redirecting...</title>
    <script>
        localStorage.setItem('dashboardView', 'list');
        window.location.replace('dashboard.php');
    </script>
</head>
<body>
    <p>Redirecting to dashboard list view...</p>
</body>
</html>
