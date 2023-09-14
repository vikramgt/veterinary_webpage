<?php
// require_once "pdo.php";
require_once "functions/signup-functions.php";

session_start();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] == 'Doctor') {
        $_SESSION["success"] = "Already Logged In!";
        header("Location: index-doctor.php");
        return;
    }

    if (isset($_SESSION["role"]) && $_SESSION["role"] === 'Owner') {
        $_SESSION["success"] = "Already Logged In!";
        header("Location: index-owner.php");
        return;
    }
}

if (isset($_POST["sign-up"])) {
    if (validateInputs($_POST)) {
        if (isset($_SESSION["signup_data"])) {
            if ($_SESSION["signup_data"] === "Valid") {

                unset($_SESSION["signup_data"]);

                if ($_POST["role"] === "Owner") {
                    if (checkIfOwnerNotExists($_POST["email_id"])) {
                        insertOwner($_POST);
                        header("Location: index-owner.php");
                        return;
                    } else {
                        $_SESSION["failure"] = "Account already exists!";
                        header("Location: signup.php");
                        return;
                    }
                } elseif ($_POST["role"] == "Doctor") {
                    if (checkIfDoctorNotExists($_POST["email_id"])) {
                        insertDoctor($_POST);
                        header("Location: index-doctor.php");
                        return;
                    } else {
                        $_SESSION["failure"] = "Account already exists!";
                        header("Location: signup.php");
                        return;
                    }
                }
            }
        }
    }
}

if (isset($_POST["cancel"])) {
    header("location: index.php");
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
    <title>Sign Up Page</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System Sign Up Page</h1>
        </div>
    </div>

    <div class="container text-white">
        <form method="post" class="form_class">

            <div class="form-group row form_element_class">
                <label for="name" class="col-sm-3 col-form-label">Enter Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter your name" value="<?= isset($_POST["name"]) ? $_POST["name"] : "" ?>">
                </div>
            </div>



            <div class="form-group row form_element_class">
                <label for="email_id" class="col-sm-3 col-form-label">Enter Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="email_id" id="email_id" placeholder="Enter your email" value="<?= isset($_POST["age"]) ? $_POST["age"] : "" ?>">
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="password" class="col-sm-3 col-form-label">Enter Password</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="contact_number" class="col-sm-3 col-form-label">Enter Contact Number</label>
                <div class="col-sm-9">
                    <input type="tel" class="form-control" name="contact_number" id="contact_number" placeholder="Enter your contact number" value="<?= isset($_POST["contact_number"]) ? $_POST["contact_number"] : "" ?>">
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="bdate" class="col-sm-3 col-form-label">Enter Birthdate</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" name="bdate" id="bdate" placeholder="Enter your birthdate" value="<?= isset($_POST["bdate"]) ? $_POST["bdate"] : "" ?>">
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="gender" class="col-sm-3 col-form-label">Enter Gender</label>
                <div class="col-sm-9">
                    <select name="gender" id="gender" class="form-control">
                        <option value="0" <?= (isset($_POST["gender"]) && $_POST["gender"] === "0") ? "selected" : "" ?>>Select Gender</option>
                        <option value="M" <?= (isset($_POST["gender"]) && $_POST["gender"] === "M") ? "selected" : "" ?>>Male</option>
                        <option value="F" <?= (isset($_POST["gender"]) && $_POST["gender"] === "F") ? "selected" : "" ?>>Female</option>
                    </select>
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="role" class="col-sm-3 col-form-label">Enter Role</label>
                <div class="col-sm-9">
                    <select name="role" id="role" class="form-control">
                        <option value="Owner" <?= (isset($_POST["role"]) && $_POST["role"] === "Owner") ? "selected" : "" ?>>Owner</option>
                        <option value="Doctor" <?= (isset($_POST["role"]) && $_POST["role"] === "Doctor") ? "selected" : "" ?>>Doctor</option>
                    </select>
                </div>
            </div>

            <div class="text-center">
                <div><input class="btn btn-info col-4 button_class" type="submit" name="sign-up" value="Sign Up"></div>
                <div><input class="btn btn-warning col-4 button_class" type="submit" name="cancel" value="Cancel"></div>
                <a class="text-white" href="login.php">Already have an account?</a>
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