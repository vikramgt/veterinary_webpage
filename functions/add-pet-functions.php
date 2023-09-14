<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=VET', 'vet', 'vet');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function validateAge($age)
{
    if (is_numeric($age)) {
        if ((int)$age >= 0) {
            return true;
        } else {
            $_SESSION["failure"] = "Age should be >= 18.";
            return false;
        }
    } else {
        $_SESSION["failure"] = "Enter a valid age.";
        return false;
    }
}


function validateInputs($INPUT_POST_ARRAY)
{
    // name
    // species_name
    // age
    // gender
    // owner_id

    $name = "";
    $species_name = "";
    $age = "";
    $gender = "";

    $inpSetFlag = 0;
    $inpReqFlag = 0;

    if (
        isset($INPUT_POST_ARRAY["name"]) &&
        isset($INPUT_POST_ARRAY["species_name"]) &&
        isset($INPUT_POST_ARRAY["age"]) &&
        isset($INPUT_POST_ARRAY["gender"])
    ) {
        $inpSetFlag = 1;

        $name = $INPUT_POST_ARRAY["name"];
        $species_name = $INPUT_POST_ARRAY["species_name"];
        $age = $INPUT_POST_ARRAY["age"];
        $gender = $INPUT_POST_ARRAY["gender"];
    } else {
        $inpSetFlag = 0;
    }

    if ($inpSetFlag === 1) {
        if (
            (strlen($name) > 0) &&
            (strlen($species_name) > 0) &&
            (strlen($age) > 0) &&
            (strlen($gender) > 0)
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
        //Check if age is number and is >= 18
        $ageVal = validateAge($age);
        if (!$ageVal) {
            return false;
        }
        //Check for valid email

        if ($ageVal) {
            $_SESSION["add-pet_data"] = "Valid";
            return true;
        } else {
            return false;
        }
    } elseif ($inpSetFlag === 1 && $inpReqFlag === 0) {
        $_SESSION["failure"] = "Fill all the required input feilds.";
        return false;
    }
}

function insertPet($INPUT_POST_ARRAY)
{
    global $pdo;

    $name = $INPUT_POST_ARRAY["name"];
    $species_name = $INPUT_POST_ARRAY["species_name"];
    $age = $INPUT_POST_ARRAY["age"];
    $gender = $INPUT_POST_ARRAY["gender"];
    $owner_id = $_SESSION["id"];

    $sql = "INSERT INTO pets (name, species_name, age, gender, owner_id) VALUES (:name, :species_name, :age, :gender, :owner_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        "name" => $name,
        "species_name" => $species_name,
        "age" => $age,
        "gender" => $gender,
        "owner_id" => $owner_id
    ));
    $_SESSION["success"] = "Successfully added pet!";
    return;
}