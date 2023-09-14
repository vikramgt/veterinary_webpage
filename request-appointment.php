<?php
// require_once "pdo.php";
require_once "functions/request-appointment-functions.php";
session_start();

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

if (isset($_POST["req-appointment"])) {
    if ($_POST["doctor"] === "select") {
        $_SESSION["failure"] = "Choose a doctor!";
        header("location: request-appointment.php?pet_id=" . $_GET["pet_id"] . "");
        return;
    } else {
        $petId = $_GET["pet_id"];
        $doctorId = $_POST["doctor"];
        $reason = $_POST["reason"];
        $date = $_POST["prefdate"];
        addRequestAppointment($petId, $doctorId, $reason, $date);
        $petDetails = getPetDetails($petId);
        $_SESSION["success"] = "Requested appointment for " . $petDetails["name"];
        header("location: index-owner.php");
        return;
    }
}

if (isset($_POST["cancel"])) {
    header("location: view-pet.php?pet_id=" . $_GET["pet_id"] . "");
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
    <title>Add Appointment Page</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System Add Appointment Page</h1>
        </div>
    </div>

    <div class="container">
        <div class="my-5 text-center text-white">
            <h2>Request appointment for <?= $pet_details["name"] ?></h2>
        </div>
    </div>

    <div class="container text-white">
        <form method="post" class="form_class">
            <div class="form-group row form_element_class">
                <label for="doctor" class="col-sm-3 col-form-label">Choose Doctor</label>
                <div class="col-sm-9">
                    <select name="doctor" id="doctor" class="form-control">
                        <option value="select">Select</option>
                        <?php foreach (getDoctors() as $doctor) : ?>
                            <option value="<?= $doctor["doctor_id"] ?>"><?= $doctor["name"] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

            </div>
            <div class="form-group row form_element_class">
                <label for="prefdate" class="col-sm-3 col-form-label">Preferred Date</label>
                <div class="col-sm-9">
                    <input for="prefdate" name="prefdate" id="predate" class="form-control" type="date">
                </div>

            </div>

            <div class="form-group row form_element_class">
                <label for="reason" class="col-sm-3 col-form-label">Enter Reason</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="reason" id="reason" placeholder="Enter the reason for appointment">
                </div>
            </div>

            <div class="text-center">
                <div><input class="btn btn-info col-4 button_class" type="submit" name="req-appointment" value="Request Appointment"></div>
                <div><input class="btn btn-warning col-4 button_class" type="submit" name="cancel" value="Cancel"></div>
            </div>
        </form>
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