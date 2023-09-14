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
