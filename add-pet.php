<?php
// require_once "pdo.php";
require_once "functions/add-pet-functions.php";

session_start();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] == 'Owner') {
        //Continue
    } elseif (isset($_SESSION["role"]) && $_SESSION["role"] === 'Doctor') {
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

if (isset($_POST["add-pet"])) {
    if (validateInputs($_POST)) {
        if (isset($_SESSION["add-pet_data"])) {
            if ($_SESSION["add-pet_data"] === "Valid") {
                // unset($_SESSION["addpet_data"]);
                insertPet($_POST);
                header("Location: index-owner.php");
                return;
            }
        }
    }
}

if (isset($_POST["cancel"])) {
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
    <title>Add Pet Page</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System Add Pet Page</h1>
        </div>
    </div>

    <div class="container text-white">
        <form method="post" class="form_class">
            <div class="form-group row form_element_class">
                <label for="name" class="col-sm-3 col-form-label">Enter Pet's Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter your's pet name" value="<?= isset($_POST["name"]) ? $_POST["name"] : "" ?>">
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="species_name" class="col-sm-3 col-form-label">Enter Pet's Species</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="species_name" id="species_name" placeholder="Enter your pet's species" value="<?= isset($_POST["species_name"]) ? $_POST["species_name"] : "" ?>">
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="age" class="col-sm-3 col-form-label">Enter Pet's Age</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" name="age" id="age" placeholder="Enter your pet's age" value="<?= isset($_POST["age"]) ? $_POST["age"] : "" ?>">
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="gender" class="col-sm-3 col-form-label">Enter Pet's Gender</label>
                <div class="col-sm-9">
                    <select name="gender" id="gender" class="form-control">
                        <option value="Not Specified" <?= (isset($_POST["gender"]) && $_POST["gender"] === "Not Specified") ? "selected" : "" ?>>Select</option>
                        <option value="M" <?= (isset($_POST["gender"]) && $_POST["gender"] === "M") ? "selected" : "" ?>>Male</option>
                        <option value="F" <?= (isset($_POST["gender"]) && $_POST["gender"] === "F") ? "selected" : "" ?>>Female</option>
                    </select>
                </div>
            </div>

            <div class="text-center">
                <div><input class="btn btn-info col-4 button_class" type="submit" name="add-pet" value="Add Pet"></div>
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