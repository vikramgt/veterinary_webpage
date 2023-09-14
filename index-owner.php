<?php
// require_once "pdo.php";
require_once "functions/index-owner-functions.php";
session_start();

$pets_array = array();
$appointments_array = array();
$visits_array = array();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] === "Owner") {
        //Continue
        $pets_array = getPets();
        $appointments_array = getAppointemnts();
        $visits_array = getVisits();
    } elseif (isset($_SESSION["role"]) && $_SESSION["role"] === "Doctor") {
        header("location: index-doctor.php");
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

if (isset($_POST["cancel_appointment"])) {
    cancelAppointment($_POST["appointment_id"]);
    $_SESSION["success"] = "Cancelled Appointment";
    header("location: index-owner.php");
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
    <title>Owner's Page</title>
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
        <div><a class="btn btn-info col-4 button_class" href="add-pet.php">Add Pet</a></div>
        <div><a class="btn btn-warning col-4 button_class" href="logout.php">Log Out</a></div>
    </div>

    <div class="container text-white text-center">
        <!-- Display Pet List -->
        <h3 class="table_heading_class">Pet List</h3>
        <?php if (count($pets_array) != 0) : ?>
            <table class="table table-hover table-striped table-bordered table-light text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Species</th>
                        <th scope="col">Age</th>
                        <th scope="col">Gender</th>
                        <th scope="col">View</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($pets_array as $pet) : ?>
                        <tr>
                            <th scope="row"><?= $i ?></th>
                            <td><?= $pet["name"] ?></td>
                            <td><?= $pet["species_name"] ?></td>
                            <td><?= $pet["age"] ?></td>
                            <td><?= $pet["gender"] ?></td>
                            <td><a class="btn btn-success btn-sm table_buttons" href="view-pet.php?pet_id=<?= $pet["pet_id"] ?>">View</a></td>
                            <td><a class="btn btn-success btn-sm table_buttons" href="edit-pet.php?pet_id=<?= $pet["pet_id"] ?>">Edit</a></td>
                            <td><a class="btn btn-success btn-sm table_buttons" href="delete-pet.php?pet_id=<?= $pet["pet_id"] ?>" onclick='return checkdelete()'>Delete</a></td>
                            <?php $i++; ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <script>
                function checkdelete() {
                    return confirm('Are you sure you want to delete this pet?');
                }
            </script>
        <?php else : ?>
            <p>No pets.</p>
        <?php endif ?>

        <!-- Display Appointment List -->
        <h3 class="table_heading_class">Appointment List</h3>
        <?php if (count($appointments_array) != 0) : ?>
            <table class="table table-hover table-striped table-bordered table-light text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Pet Name</th>
                        <th scope="col">Doctor Name</th>
                        <th scope="col">Reason</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date Alloted</th>
                        <th scope="col">Cancel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($appointments_array as $appointment) : ?>
                        <?php if ($appointment["status"] !== "Visited") : ?>
                            <tr>
                                <th scope="row"><?= $i ?></th>
                                <td><?= $appointment["pet_name"] ?></td>
                                <td><?= $appointment["doctor_name"] ?></td>
                                <td><?= $appointment["reason"] ?></td>
                                <td><?= $appointment["status"] ?></td>
                                <?php if ($appointment["status"] === "Canceled") : ?>
                                    <td>Not Available</td>
                                <?php else : ?>
                                    <td><?= $appointment["date_alloted"] ?></td>
                                <?php endif; ?>
                                <?php if ($appointment["status"] === "Visited" || $appointment["status"] === "Canceled") : ?>
                                    <td>Not Available</td>
                                <?php else : ?>
                                    <form method="post">
                                        <td>
                                            <input type="hidden" name="appointment_id" value="<?= $appointment["id"] ?>">
                                            <input type="submit" class="btn btn-danger btn-sm table_buttons" name="cancel_appointment" value="Cancel">
                                        </td>
                                    </form>
                                <?php endif; ?>
                                <?php $i++; ?>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No appointments.</p>
        <?php endif ?>

        <!-- Display Visit List -->
        <h3 class="table_heading_class">Visit List</h3>
        <?php if (count($appointments_array) != 0) : ?>
            <table class="table table-hover table-striped table-bordered table-light text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Pet Name</th>
                        <th scope="col">Doctor Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Reason</th>
                        <th scope="col">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($visits_array as $visit) : ?>
                        <tr>
                            <th scope="row"><?= $i ?></th>
                            <td><?= $visit["pet_name"] ?></td>
                            <td><?= $visit["doctor_name"] ?></td>
                            <td><?= $visit["visited_date"] ?></td>
                            <td><?= $visit["reason"] ?></td>
                            <td><?= $visit["doctor_notes"] ?></td>
                            <?php $i++; ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No visits.</p>
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