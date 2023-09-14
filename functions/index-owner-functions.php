<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=VET', 'vet', 'vet');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getPets()
{
    global $pdo;

    $sql = "SELECT * FROM pets where owner_id = :owner_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":owner_id" => $_SESSION["id"],
    ));
    $pets_array = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($pets_array, $row);
    }
    return $pets_array;
}

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

function getDoctorName($doctorId)
{
    global $pdo;

    $sql = "SELECT * FROM doctors where doctor_id = :doctor_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":doctor_id" => $doctorId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["name"];
}

function getAppointemnts()
{
    global $pdo;

    $sql = "SELECT * FROM appointments where owner_id = :owner_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":owner_id" => $_SESSION["id"],
    ));
    $appointments_array = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $appointment = array();
        $appointment["id"] = $row["appointment_id"];
        $appointment["pet_name"] = getPetName($row["pet_id"]);
        $appointment["doctor_name"] = getDoctorName($row["doctor_id"]);
        $appointment["reason"] = $row["reason"];
        $appointment["status"] = $row["status"];
        $appointment["date_alloted"] = $row["date_alloted"] === NULL ? "Yet to be allocated" : $row["date_alloted"];
        array_push($appointments_array, $appointment);
    }
    return $appointments_array;
}

function cancelAppointment($appointmentId)
{
    global $pdo;

    $sql = "UPDATE appointments SET status = :status WHERE appointment_id = :appointment_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":status" => "Canceled",
        ":appointment_id" => $appointmentId,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function getPetIds()
{
    global $pdo;

    $sql = "SELECT * FROM pets where owner_id = :owner_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":owner_id" => $_SESSION["id"],
    ));
    $petIds_array = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($petIds_array, $row["pet_id"]);
    }
    return $petIds_array;
}

function getAppointmentDetails($appointmentId)
{
    // appointment_id, pet_id, doctor_id, owner_id, date_applied, reason, date_alloted, status
    global $pdo;

    $sql = "SELECT * FROM appointments WHERE appointment_id = :appointment_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":appointment_id" => $appointmentId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getVisits()
{
    global $pdo;

    $petIds_array = getPetIds();
    $visits_array = array();

    foreach ($petIds_array as $petId) {
        $sql = "SELECT * FROM visits where pet_id = :pet_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":pet_id" => $petId,
        ));
        
        $visit = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $visits["pet_name"] =  getPetName($row["pet_id"]);
            $visits["doctor_name"] =  getDoctorName(getAppointmentDetails($row["appointment_id"])["doctor_id"]);
            $visits["visited_date"] =  $row["date_visited"];
            $visits["reason"] =  getAppointmentDetails($row["appointment_id"])["reason"];
            $visits["doctor_notes"] =  $row["notes"];
            
            array_push($visits_array, $visits);
        }
    }
    return $visits_array;
}
