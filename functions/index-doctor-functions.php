<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=VET', 'vet', 'vet');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getPetName($petId)
{
    global $pdo;

    $sql = "SELECT * FROM pets where pet_id = :pet_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":pet_id" => $petId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["name"];
}

function getOwnerName($ownerId)
{
    global $pdo;

    $sql = "SELECT * FROM owners where owner_id = :owner_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":owner_id" => $ownerId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["name"];
}

function getAppointemnts()
{
    global $pdo;

    $sql = "SELECT * FROM appointments where doctor_id = :doctor_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":doctor_id" => $_SESSION["id"],
    ));
    $appointments_array = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row["status"] === "Canceled" || $row["status"] === "Visited") {
            continue;
        } else {
            $appointment = array();
            $appointment["id"] = $row["appointment_id"];
            $appointment["pet_name"] = getPetName($row["pet_id"]);
            $appointment["owner_name"] = getOwnerName($row["owner_id"]);
            $appointment["reason"] = $row["reason"];
            $appointment["status"] = $row["status"];
            $appointment["date_applied"] = $row["date_applied"];
            $appointment["date_alloted"] = $row["date_alloted"];


            array_push($appointments_array, $appointment);
        }
    }
    return $appointments_array;
}
