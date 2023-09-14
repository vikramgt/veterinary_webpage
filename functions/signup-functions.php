<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=VET', 'vet', 'vet');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


function validateEmail($email_id)
{
    $email_id = filter_var($email_id, FILTER_SANITIZE_EMAIL);
    if (filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        $_SESSION["failure"] = "Invalid email id.";
        return false;
    }
}

function validatePassword($password)
{
    if (strlen($password) >= 8) {
        return true;
    } else {
        $_SESSION["failure"] = "Password length must be >= 8.";
        return false;
    }
}

function validateContactNumber($contact_number)
{
    if (strlen($contact_number) === 10 && is_numeric($contact_number)) {
        return true;
    } else {
        $_SESSION["failure"] = "Invalid contact number.";
        return false;
    }
}

function validateRole($role)
{
    if ($role == "Owner" || $role == "Doctor") {
        return true;
    } else {
        $_SESSION["failure"] = "Invalid role.";
        return false;
    }
}
function validateBdate($bdate)
{

    $curyear = date('Y');
    $year = date('Y', strtotime($bdate));

    if ($curyear - $year > 100 || $curyear - $year < 18) {
        $_SESSION["failure"] = "Re Check Date.";
        return false;
    } else {
        return true;
    }
}

function validateInputs($INPUT_POST_ARRAY)
{
    // name
    // email_id
    // password
    // contact_number
    // bdate
    // gender

    $name = "";
    $email_id = "";
    $password = "";
    $contact_number = "";
    $bdate = "";
    $gender = "";
    $role = "";

    $inpSetFlag = 0;
    $inpReqFlag = 0;

    if (
        isset($INPUT_POST_ARRAY["name"]) &&
        isset($INPUT_POST_ARRAY["email_id"]) &&
        isset($INPUT_POST_ARRAY["password"]) &&
        isset($INPUT_POST_ARRAY["contact_number"]) &&
        isset($INPUT_POST_ARRAY["bdate"]) &&
        isset($INPUT_POST_ARRAY["gender"]) &&
        isset($INPUT_POST_ARRAY["role"])
    ) {
        $inpSetFlag = 1;

        $name = $INPUT_POST_ARRAY["name"];
        $email_id = $INPUT_POST_ARRAY["email_id"];
        $password = $INPUT_POST_ARRAY["password"];
        $contact_number = $INPUT_POST_ARRAY["contact_number"];
        $bdate = $INPUT_POST_ARRAY["bdate"];
        $gender = $INPUT_POST_ARRAY["gender"];
        $role = $INPUT_POST_ARRAY["role"];
    } else {
        $inpSetFlag = 0;
    }

    if ($inpSetFlag === 1) {
        if (
            (strlen($name) > 0) &&
            (strlen($email_id) > 0) &&
            (strlen($password) > 0) &&
            (strlen($contact_number) > 0) &&
            (strlen($bdate) > 0) &&
            (strlen($gender) > 0) &&
            (strlen($role) > 0)
        ) {
            $inpReqFlag = 1;
        } else {
            $inpReqFlag = 0;
        }
    } elseif ($inpSetFlag === 0) {
        $_SESSION["failure"] = "REQUEST ERROR";
    }

    if ($inpSetFlag === 1 && $inpReqFlag === 1) {
        //Name validation is done
        //Check for valid email
        $emailVal = ValidateEmail($email_id);
        if (!$emailVal) {
            return false;
        }
        //Check if password length is >= 8
        $passwordVal = validatePassword($password);
        if (!$passwordVal) {
            return false;
        }
        //Check if contact number is valid
        $contactVal = validateContactNumber($contact_number);
        if (!$contactVal) {
            return false;
        }
        //Bdate is valid
        //Check if gender is M or F

        //Check if role is valid
        $roleVal = validateRole($role);
        if (!$roleVal) {
            return false;
        }
        $bdateVal = validateBdate($bdate);

        if ($bdateVal && $emailVal && $passwordVal && $contactVal && $roleVal) {
            $_SESSION["signup_data"] = "Valid";
            return true;
        } else {
            return false;
        }
    } elseif ($inpSetFlag === 1 && $inpReqFlag === 0) {
        $_SESSION["failure"] = "Fill all the required input feilds.";
        return false;
    }
}

function checkIfOwnerNotExists($email_id)
{
    // Returns false if account exists
    // Returns true if account does not exists
    global $pdo;
    $sql = "SELECT * FROM owners where email_id = :email_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":email_id" => $email_id,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return false;
    } else {
        return true;
    }
}

function checkIfDoctorNotExists($email_id)
{
    // Returns false if account exists
    // Returns true if account does not exists
    global $pdo;
    $sql = "SELECT * FROM doctors where email_id = :email_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":email_id" => $email_id,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return false;
    } else {
        return true;
    }
}

function insertOwner($INPUT_POST_ARRAY)
{
    global $pdo;

    $name = $INPUT_POST_ARRAY["name"];
    $email_id = $INPUT_POST_ARRAY["email_id"];
    $password = $INPUT_POST_ARRAY["password"];
    $contact_number = $INPUT_POST_ARRAY["contact_number"];
    $bdate = $INPUT_POST_ARRAY["bdate"];
    $gender = $INPUT_POST_ARRAY["gender"];

    $sql = "INSERT INTO owners (name, email_id, password, contact_number, bdate, gender) VALUES (:name, :email_id, :password, :contact_number, :bdate, :gender)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        "name" => $name,
        "email_id" => $email_id,
        "password" => $password,
        "contact_number" => $contact_number,
        "bdate" => $bdate,
        "gender" => $gender
    ));
    $_SESSION["email"] = $email_id;
    $_SESSION["name"] = $name;

    $sql = "SELECT * FROM owners where email_id = :email_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":email_id" => $email_id,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION["id"] = $result["owner_id"];

    $_SESSION["status"] = 1;
    $_SESSION["role"] = "Owner";

    $_SESSION["success"] = "Successfully signed up!";
    return;
}

function insertDoctor($INPUT_POST_ARRAY)
{
    global $pdo;

    $name = $INPUT_POST_ARRAY["name"];
    $email_id = $INPUT_POST_ARRAY["email_id"];
    $password = $INPUT_POST_ARRAY["password"];
    $contact_number = $INPUT_POST_ARRAY["contact_number"];
    $bdate = $INPUT_POST_ARRAY["bdate"];
    $gender = $INPUT_POST_ARRAY["gender"];

    $sql = "INSERT INTO doctors (name, email_id, password, contact_number, bdate, gender) VALUES (:name, :email_id, :password, :contact_number, :bdate, :gender)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        "name" => $name,
        "email_id" => $email_id,
        "password" => $password,
        "contact_number" => $contact_number,
        "bdate" => $bdate,
        "gender" => $gender
    ));
    $_SESSION["email"] = $email_id;
    $_SESSION["name"] = $name;

    $sql = "SELECT * FROM doctors where email_id = :email_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":email_id" => $email_id,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION["id"] = $result["doctor_id"];

    $_SESSION["status"] = 1;
    $_SESSION["role"] = "Doctor";
    $_SESSION["success"] = "Successfully signed up!";
    return;
}
