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
if (isset($_POST['submit'])) {
    try {
        $pdo = new PDO('mysql:host=localhost;port=3306;dbname=VET', 'vet', 'vet');
    } catch (PDOException $exc) {
        echo $exc->getMessage();
        exit();
    }
    $id = $_GET['pet_id'];
    $name = $_POST['name'];
    $sname = $_POST['species'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $query = "UPDATE `pets` SET `name`=:name,`species_name`=:sname,`age`=:age,`gender`=:gender WHERE `pet_id` = :id";
    $pdoResult = $pdo->prepare($query);
    $pdoExec = $pdoResult->execute(array(":name" => $name, ":sname" => $sname, ":age" => $age, ":gender" => $gender, ":id" => $id));
    $_SESSION["success"] = "Pet Details Updated";
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
    <title>Edit Pet Page</title>
</head>

<body class="body_class">
    <div class="container">
        <div class="my-5 text-center text-white">
            <h1>Veterinary Management System Edit Pet Page</h1>
        </div>
    </div>

    <div class="container text-white">
        <form method="post" class="form_class">
            <div class="form-group row form_element_class">
                <label for="name" class="col-sm-3 col-form-label">Enter Pet's Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="name" id="name" value=<?= $pet_details["name"] ?>>
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="species" class="col-sm-3 col-form-label">Enter Pet's Species</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="species" id="species" value=<?= $pet_details["species_name"] ?>>
                </div>
            </div>

            <div class="form-group row form_element_class">
                <label for="age" class="col-sm-3 col-form-label">Enter Pet's Age</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" name="age" id="age" value=<?= $pet_details["age"] ?>>
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
                <div><input class="btn btn-info col-4 button_class" type="submit" name="submit" value="Update"></div>

            </div>
        </form>
    </div>




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
</script>

</html>