<?php
// require_once "pdo.php";
require_once "functions/view-pet-functions.php";

session_start();

$pet_details = array();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] === "Owner") {
        if (isset($_GET["pet_id"]) && is_numeric($_GET["pet_id"]) && checkPet($_GET["pet_id"])) {
            //Continue
            $pet_details = getPetDetails($_GET["pet_id"]);
        } else {
            $_SESSION["failure"] = "Access Denied!";
            header("Location: index-owner.php");
            return;
        }
    } elseif (isset($_SESSION["role"]) && $_SESSION["role"] === "Doctor") {
        $_SESSION["failure"] = "Access Denied!";
        header("Location: index-doctor.php");
        return;
    } else {
        session_destroy();
        header("location: login.php");
        return;
    }
} else {
    header("location: login.php");
    return;
}




?>


<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <title>View Pet Page</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System View Pet Page</h1>
        </div>
    </div>

    <div class="container text-center">
        <div class="card" style="width:400px; margin: 0 auto; float: none; margin-bottom: 10px;">
            <img class="card-img-top" src="cat_profile.png" alt="Card image" style="width:100%">
            <div class="card-body">
                <h4 class="card-title"><?= $pet_details["name"] ?></h4>
                <p class="card-text">Species: <?= $pet_details["species_name"] ?></p>
                <p class="card-text">Age: <?= $pet_details["age"] ?></p>
                <p class="card-text">Gender: <?= $pet_details["gender"] ?></p>
                <a href="request-appointment.php?pet_id=<?= $_GET["pet_id"] ?>" class="btn btn-primary">Add Appointment</a>




            </div>
        </div>
        <br>
    </div>

    <div class="container text-center">
        <a class="btn btn-warning col-4 button_class" href="index-owner.php">Go Back</a>
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

    document.getElementById('editbutton').addEventListener('click',
        function() {
            document.querySelector('.bg-modal').style.display = 'flex';
        });
    document.getElementById('close').addEventListener('click',
        function() {
            document.querySelector('.bg-modal').style.display = 'none';
        })
</script>

</html>
<style>
    .bg-modal {
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        position: absolute;
        top: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        display: none;
    }

    .modal-content {
        width: 500px;
        height: 500px;
        background-color: white;
        border-radius: 4px;
        text-align: center;
        padding: 20px;
        position: relative;
    }

    input {
        width: 50%;
        display: block;
        margin: 15px auto;
    }

    .close {
        position: absolute;
        top: 0;
        right: 14px;
        font-size: 32px;
        transform: rotate(45deg);
        cursor: pointer;
    }
</style>