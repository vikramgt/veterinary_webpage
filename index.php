<?php
session_start();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if ($_SESSION["role"] === "Owner") {
        header("location: index-owner.php");
        return;
    }
    if ($_SESSION["role"] === "Doctor") {
        header("location: index-doctor.php");
        return;
    }
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <title>Veterinary Management System</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System</h1>
        </div>
    </div>

    <div class="container text-center">
        <div><a class="btn btn-info col-4 button_class" href="signup.php">Sign Up</a></div>
        <div><a class="btn btn-warning col-4 button_class" href="login.php">Log In</a></div>
    </div>
</body>

<script>
    <?php if (isset($_SESSION["success"])) : ?>
        alert("<?= $_SESSION["success"] ?>");
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["failure"])) : ?>
        alert("<?= $_SESSION["failure"] ?>");
        <?php unset($_SESSION["failure"]); ?>
    <?php endif; ?>
</script>

</html>