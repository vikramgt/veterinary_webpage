<?php
// require_once "pdo.php";
require_once "functions/login-functions.php";

session_start();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] === "Doctor") {
        $_SESSION["success"] = "Already Logged In!";
        header("Location: index-doctor.php");
        return;
    }

    if (isset($_SESSION["role"]) && $_SESSION["role"] === "Owner") {
        $_SESSION["success"] = "Already Logged In!";
        header("Location: index-owner.php");
        return;
    }
}

if (isset($_POST["log-in"])) {
    if (validateInputs($_POST)) {
        if (isset($_SESSION["login_data"])) {
            if ($_SESSION["login_data"] === "Valid") {

                unset($_SESSION["login_data"]);

                if ($_POST["role"] === "Owner") {
                    if (checkIfOwnerExists($_POST["email_id"])) {
                        if (checkOwnerPassword($_POST["email_id"], $_POST["password"])) {
                            header("Location: index-owner.php");
                            return;
                        } else {
                            $_SESSION["failure"] = "Invalid username or password!";
                        }
                    } else {
                        $_SESSION["failure"] = "Account does not exists!";
                        header("Location: login.php");
                        return;
                    }
                } elseif ($_POST["role"] === "Doctor") {
                    if (checkIfDoctorExists($_POST["email_id"])) {
                        if (checkDoctorPassword($_POST["email_id"], $_POST["password"])) {
                            header("Location: index-doctor.php");
                            return;
                        } else {
                            $_SESSION["failure"] = "Invalid username or password!";
                        }
                    } else {
                        $_SESSION["failure"] = "Account does not exists!";
                        header("Location: login.php");
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
    <title>Log In Page</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System Log In Page</h1>
        </div>
    </div>

    <div class="container text-white">
        <form method="post" class="form_class">
            <div class="form-group row form_element_class">
                <label for="email_id" class="col-sm-3 col-form-label">Enter Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="email_id" id="email_id" placeholder="Enter email" value="<?= isset($_POST["email_id"]) ? $_POST["email_id"] : "" ?>">
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="password" class="col-sm-3 col-form-label">Enter Password</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
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
                <div><input class="btn btn-info col-4 button_class" type="submit" name="log-in" value="Log In"></div>
                <div><input class="btn btn-warning col-4 button_class" type="submit" name="cancel" value="Cancel"></div>
                <a class="text-white" href="signup.php">Don't have an account?</a>
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