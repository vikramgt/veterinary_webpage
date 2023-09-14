<?php
// require_once "pdo.php";
require_once "functions/index-doctor-functions.php";
session_start();

$appointments_array = array();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] === "Doctor") {
        //Continue
        $appointments_array = getAppointemnts();
    } elseif (isset($_SESSION["role"]) && $_SESSION["role"] === "Owner") {
        header("location: index-owner.php");
        return;
    } else {
        session_destroy();
        header("location: index.php");
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
    <title>Doctor's Page</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System</h1>
        </div>
    </div>

    <div class="container">
        <div class="my-5 text-center text-white">
            <h2>Welcome <?= $_SESSION["name"] ?></h2>
        </div>
    </div>

    <div class="container text-center">
        <div><a class="btn btn-warning col-4 button_class" href="logout.php">Log Out</a></div>
    </div>

    <div class="container text-white text-center">
        <!-- Display Appointment List -->
        <h3 class="table_heading_class">Appointment List</h3>
        <?php if (count($appointments_array) != 0) : ?>
            <table class="table table-hover table-striped table-bordered table-light text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Owner Name</th>
                        <th scope="col">Pet Name</th>
                        <th scope="col">Reason</th>
                        <th scope="col">Date Applied</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date Alloted</th>
                        <th scope="col">View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($appointments_array as $appointment) : ?>
                        <tr>
                            <th scope="row"><?= $i ?></th>
                            <td><?= $appointment["owner_name"] ?></td>
                            <td><?= $appointment["pet_name"] ?></td>
                            <td><?= $appointment["reason"] ?></td>
                            <td><?= $appointment["date_applied"] ?></td>
                            <td><?= $appointment["status"] ?></td>
                            <td><?= $appointment["date_alloted"] ?></td>
                            <td><a class="btn btn-success btn-sm table_buttons" href="view-appointment.php?appointment_id=<?= $appointment["id"] ?>">View</a></td>
                            <?php $i++; ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No appointments.</p>
        <?php endif ?>
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