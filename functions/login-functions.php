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

function validateRole($role)
{
    if ($role == "Owner" || $role == "Doctor") {
        return true;
    } else {
        $_SESSION["failure"] = "Invalid role.";
        return false;
    }
}

function validateInputs($INPUT_POST_ARRAY)
{
    // email_id
    // password
    // role

    $email_id = "";
    $password = "";
    $role = "";

    $inpSetFlag = 0;
    $inpReqFlag = 0;

    if (
        isset($INPUT_POST_ARRAY["email_id"]) &&
        isset($INPUT_POST_ARRAY["password"]) &&
        isset($INPUT_POST_ARRAY["role"])
    ) {
        $inpSetFlag = 1;

        $email_id = $INPUT_POST_ARRAY["email_id"];
        $password = $INPUT_POST_ARRAY["password"];
        $role = $INPUT_POST_ARRAY["role"];
    } else {
        $inpSetFlag = 0;
    }

    if ($inpSetFlag === 1) {
        if (
            (strlen($email_id) > 0) &&
            (strlen($password) > 0) &&
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
        //Check for valid email
        $emailVal = ValidateEmail($email_id);
        if (!$emailVal) {
            return false;
        }
        //Check if role is valid
        $roleVal = validateRole($role);
        if (!$roleVal) {
            return false;
        }

        if ($emailVal && $roleVal) {
            $_SESSION["login_data"] = "Valid";
            return true;
        } else {
            return false;
        }
    } elseif ($inpSetFlag === 1 && $inpReqFlag === 0) {
        $_SESSION["failure"] = "Fill all the required input feilds.";
        return false;
    }
}

function checkIfOwnerExists($email_id)
{
    // Returns true if account exists
    // Returns flase if account does not exists
    global $pdo;
    $sql = "SELECT * FROM owners where email_id = :email_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":email_id" => $email_id,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function checkIfDoctorExists($email_id)
{
    // Returns true if account exists
    // Returns false if account does not exists
    global $pdo;
    $sql = "SELECT * FROM doctors where email_id = :email_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":email_id" => $email_id,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function checkOwnerPassword($email_id, $password)
{
    // Returns true if password matches
    // Returns false if password does not match
    global $pdo;
    $sql = "SELECT * FROM owners where email_id = :email_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":email_id" => $email_id,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if ($result["password"] === $password) {
            $_SESSION["email"] = $result["email_id"];
            $_SESSION["name"] = $result["name"];
            $_SESSION["id"] = $result["owner_id"];
            $_SESSION["status"] = 1;
            $_SESSION["role"] = "Owner";
            $_SESSION["success"] = "Successfully loged in!";
            return true;
        } else {
            return false;
        }
    }
}

function checkDoctorPassword($email_id, $password)
{
    // Returns true if password matches
    // Returns false if password does not match
    global $pdo;
    $sql = "SELECT * FROM doctors where email_id = :email_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":email_id" => $email_id,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if ($result["password"] === $password) {
            $_SESSION["email"] = $result["email_id"];
            $_SESSION["name"] = $result["name"];
            $_SESSION["id"] = $result["doctor_id"];
            $_SESSION["status"] = 1;
            $_SESSION["role"] = "Doctor";
            $_SESSION["success"] = "Successfully loged in!";
            return true;
            return true;
        } else {
            return false;
        }
    }
}
