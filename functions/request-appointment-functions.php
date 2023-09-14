<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=VET', 'vet', 'vet');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function checkPet($petId)
{
    global $pdo;

    $sql = "SELECT * FROM pets where pet_id = :pet_id and owner_id = :owner_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":pet_id" => $petId,
        ":owner_id" => $_SESSION["id"],
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function getPetDetails($petId)
{
    global $pdo;

    $sql = "SELECT * FROM pets where pet_id = :pet_id and owner_id = :owner_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":pet_id" => $petId,
        ":owner_id" => $_SESSION["id"],
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function getDoctors()
{
    global $pdo;

    $doctors_array = array();

    $stmt = $pdo->query("SELECT * FROM doctors");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($doctors_array, $row);
    }

    return $doctors_array;
}

function addRequestAppointment($petId, $doctorId, $reason, $date)
{
    global $pdo;

    $sql = "INSERT INTO appointments (pet_id, doctor_id, owner_id, pref_date, reason, status) VALUES (:pet_id, :doctor_id, :owner_id, :pref_date, :reason, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":pet_id" => $petId,
        ":doctor_id" => $doctorId,
        ":owner_id" => $_SESSION["id"],
        ":pref_date" => $date,
        ":reason" => $reason,
        ":status" => "Waiting",
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
