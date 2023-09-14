<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=VET', 'vet', 'vet');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function checkAppointment($appointmentId)
{
    global $pdo;

    $sql = "SELECT * FROM appointments WHERE appointment_id = :appointment_id AND doctor_id = :doctor_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":appointment_id" => $appointmentId,
        ":doctor_id" => $_SESSION["id"],
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function getOwnerName($ownerId)
{
    global $pdo;

    $sql = "SELECT * FROM owners WHERE owner_id = :owner_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":owner_id" => $ownerId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["name"];
}

function getOwnerNameEmailNumber($ownerId)
{
    global $pdo;

    $sql = "SELECT name, email_id, contact_number FROM owners WHERE owner_id = :owner_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":owner_id" => $ownerId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function getDoctorName($doctorId)
{
    global $pdo;

    $sql = "SELECT * FROM doctors WHERE doctor_id = :doctor_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":doctor_id" => $doctorId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["name"];
}

function getPetName($petId)
{
    global $pdo;

    $sql = "SELECT * FROM pets WHERE pet_id = :pet_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":pet_id" => $petId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["name"];
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
    $ownerDetails = getOwnerNameEmailNumber($result["owner_id"]);
    $result["owner_name"] = $ownerDetails["name"];
    $result["owner_email"] = $ownerDetails["email_id"];
    $result["owner_number"] = $ownerDetails["contact_number"];
    // $result["doctor_name"] = getDoctorName($result["doctor_id"]);
    $result["pet_name"] = getPetName($result["pet_id"]);

    return $result;
}

function getPetIdFromApplointmentId($appointmentId)
{
    global $pdo;

    $sql = "SELECT * FROM appointments WHERE appointment_id = :appointment_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":appointment_id" => $appointmentId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["pet_id"];
}
function getPetDetails($petId)
{
    global $pdo;

    global $pdo;

    $sql = "SELECT * FROM pets WHERE pet_id = :pet_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":pet_id" => $petId
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function allocateDateToAppointment($appointmentId, $allotDate)
{
    global $pdo;

    $sql = "UPDATE appointments SET status = :status, date_alloted = :date_alloted WHERE appointment_id = :appointment_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":status" => "Alloted",
        ":date_alloted" => $allotDate,
        ":appointment_id" => $appointmentId,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

function createVisited($appointmentId, $visitNotes)
{
    global $pdo;

    $sql = "UPDATE appointments SET status = :status WHERE appointment_id = :appointment_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":status" => "Visited",
        ":appointment_id" => $appointmentId,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $appointmentDetails = getAppointmentDetails($appointmentId);
    $petId = $appointmentDetails["pet_id"];

    $sql = "INSERT INTO visits (appointment_id, pet_id, notes) VALUES (:appointment_id, :pet_id, :notes)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":appointment_id" => $appointmentId,
        ":pet_id" => $petId,
        ":notes" => $visitNotes,
    ));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

function getVisits($appointmentId)
{
    global $pdo;

    $appointmentDetails = getAppointmentDetails($appointmentId);
    $petId = $appointmentDetails["pet_id"];

    $sql = "SELECT * FROM visits WHERE pet_id = :pet_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":pet_id" => $petId
    ));
    $visits_array = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visit = array();
        $visit["visited_date"] = $row["date_visited"];
        $visit["appointment_reason"] = getAppointmentDetails($row["appointment_id"])["reason"];
        $visit["doctor_name"] = getDoctorName(getAppointmentDetails($row["appointment_id"])["doctor_id"]);
        $visit["doctor_notes"] = $row["notes"];
        array_push($visits_array, $visit);
    }

    return $visits_array;
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
