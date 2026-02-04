<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Startup</title>
    <link rel="stylesheet" href="public/css.css">
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Index</a></li>
<?php if (!User::check()) { ?>
            <li><a href="index.php?page=register&action=form">Register</a></li>
            <li><a href="index.php?page=login&action=form">Login</a></li>
<?php } else { ?>
            <li><a href="index.php?page=profile&action=form">Profile</a></li>
            <li><a href="index.php?page=logout&action=logout">Logout</a></li>
<?php } ?>
        </ul>
    </nav>
</header>

<main>
    <?= $content ?>
</main>

<footer>
    Micro MVC PHP Framework - by NeirdAdrieN
</footer>

</body>
</html>
