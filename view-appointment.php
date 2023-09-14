<?php
// require_once "pdo.php";
require_once "functions/view-appointment-functions.php";

session_start();

$appointment_details = array();
$pet_details = array();
$visits_array = array();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] === "Doctor") {
        if (isset($_GET["appointment_id"]) && is_numeric($_GET["appointment_id"]) && checkAppointment($_GET["appointment_id"])) {
            //Continue
            $appointment_details = getAppointmentDetails($_GET["appointment_id"]);
            $pet_id = getPetIdFromApplointmentId($_GET["appointment_id"]);
            $pet_details = getPetDetails($pet_id);
            $visits_array = getVisits($_GET["appointment_id"]);
        } else {
            $_SESSION["failure"] = "Access Denied!";
            header("Location: index-doctor.php");
            return;
        }
    } elseif (isset($_SESSION["role"]) && $_SESSION["role"] === "Owner") {
        $_SESSION["failure"] = "Access Denied!";
        header("Location: index-owner.php");
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

if (isset($_POST["allocate_appointment"])) {
    if (strlen($_POST["allot_date"]) > 0) {
        allocateDateToAppointment($_POST["appointment_id"], $_POST["allot_date"]);
        $_SESSION["success"] = "Appointment date alloted!";
        header("location: index-doctor.php");
        return;
    } else {
        $_SESSION["failure"] = "Set a valid date!";
        header("location: view-appointment.php?appointment_id=" . $_POST["appointment_id"] . "");
        return;
    }
}

if (isset($_POST["mark_visited"])) {
    if (strlen($_POST["notes"]) > 0) {
        createVisited($_POST["appointment_id"], $_POST["notes"]);
        $_SESSION["success"] = "Marked appointment as visited!";
        header("location: index-doctor.php");
        return;
    } else {
        $_SESSION["failure"] = "Give visit notes!";
        header("location: view-appointment.php?appointment_id=" . $_POST["appointment_id"] . "");
        return;
    }
}

if (isset($_POST["cancel_appointment"])) {
    cancelAppointment($_GET["appointment_id"]);
    $_SESSION["success"] = "Cancelled Appointment";
    header("location: index-doctor.php");
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
    <title>View Appointment Page</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System View Appointment Page</h1>
        </div>
    </div>

    <div class="container text-center">
        <div class="card" style="width:400px; margin: 0 auto; float: none; margin-bottom: 10px;">
            <img class="card-img-top" src="dog_profile.png" alt="Card image" style="width:100%">
            <div class="card-body">
                <h4 class="card-title"><?= $pet_details["name"] ?></h4>

                <p class="card-text">Owner Name: <?= $appointment_details["owner_name"] ?></p>
                <p class="card-text">Owner Email: <?= $appointment_details["owner_email"] ?></p>
                <p class="card-text">Owner Contact Number: <?= $appointment_details["owner_number"] ?></p>
                <p class="card-text">Pet Species: <?= $pet_details["species_name"] ?></p>
                <p class="card-text">Pet Age: <?= $pet_details["age"] ?></p>
                <p class="card-text">Pet Gender: <?= $pet_details["gender"] ?></p>
                <p class="card-text">Applied Date: <?= $appointment_details["date_applied"] ?></p>
                <p class="card-text">Appointment Reason: <?= $appointment_details["reason"] ?></p>
                <p class="card-text">Appointment Status: <?= $appointment_details["status"] ?></p>
                <p class="card-text">Preferred Date: <?= $appointment_details["pref_date"] ?></p>
            </div>
        </div>
        <br>
    </div>

    <?php if ($appointment_details["status"] === "Waiting") : ?>
        <div class="container text-white">
            <form method="post" class="form_class">
                <input type="hidden" name="appointment_id" value="<?= $appointment_details["appointment_id"] ?>">

                <div class="form-group row form_element_class">
                    <label for="allot_date" class="col-sm-3 col-form-label">Allocate Appointment Date</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" name="allot_date" id="allot_date" placeholder="Enter appointment date" value="<?= $appointment_details["pref_date"] ?>">
                    </div>
                </div>

                <div class="text-center">
                    <div><input class="btn btn-info col-4 button_class" type="submit" name="allocate_appointment" value="Allocate Appointment"></div>
                    <div><input class="btn btn-danger col-4 button_class" type="submit" name="cancel_appointment" value="Cancel Appointment"></div>
                </div>
            </form>
        </div>
    <?php elseif ($appointment_details["status"] === "Alloted") : ?>
        <div class="container text-white">
            <form method="post" class="form_class">
                <input type="hidden" name="appointment_id" value="<?= $appointment_details["appointment_id"] ?>">

                <div class="form-group row form_element_class">
                    <label for="notes" class="col-sm-3 col-form-label">Enter Visit Notes</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="notes" id="notes" placeholder="Enter visit notes" value="<?= isset($_POST["email_id"]) ? $_POST["email_id"] : "" ?>">
                    </div>
                </div>

                <div class="text-center">
                    <div><input class="btn btn-info col-4 button_class" type="submit" name="mark_visited" value="Mark Visited"></div>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div class="container text-center">
        <div><a class="btn btn-warning col-4 button_class" href="index-doctor.php">Go Back</a></div>
    </div>

    <div class="container text-white text-center">
        <!-- Pet Past Visits -->
        <h3 class="table_heading_class">Previous Visit List</h3>
        <?php if (count($visits_array) != 0) : ?>
            <table class="table table-hover table-striped table-bordered table-light text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Visit Date</th>
                        <th scope="col">Appointment Reason</th>
                        <th scope="col">Doctor's Name</th>
                        <th scope="col">Doctor's Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($visits_array as $visit) : ?>
                        <tr>
                            <th scope="row"><?= $i ?></th>
                            <td><?= $visit["visited_date"] ?></td>
                            <td><?= $visit["appointment_reason"] ?></td>
                            <td><?= $visit["doctor_name"] ?></td>
                            <td><?= $visit["doctor_notes"] ?></td>
                            <?php $i++; ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No previous visits.</p>
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