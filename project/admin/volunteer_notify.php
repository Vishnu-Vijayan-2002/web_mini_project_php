<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Notification</title>
    <style>
        .message { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<h2>Volunteer Notification</h2>

<?php if (isset($_SESSION['success'])): ?>
    <div class="message success"><?= $_SESSION['success']; ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="message error"><?= $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<a href="volunteer_list.php">← Back to Volunteer List</a>

</body>
</html>
