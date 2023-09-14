<?php
require_once "pdo.php";
session_start();

if (isset($_SESSION["status"]) && $_SESSION["status"] === 1) {
    if (isset($_SESSION["role"]) && $_SESSION["role"] == 'Owner') {
        //Continue
    } elseif (isset($_SESSION["role"]) && $_SESSION["role"] === 'Doctor') {
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

$pet_details = array();
if (isset($_GET["pet_id"])) {
    $sql = "SELECT * FROM pets where pet_id = :pet_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":pet_id" => $_GET["pet_id"]));
    $pet_details = $stmt->fetch(PDO::FETCH_ASSOC);
    $sql = "DELETE FROM pets where pet_id = :pet_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":pet_id" => $_GET["pet_id"]));
    header("location: index.php");
    $_SESSION["success"] = "Successfully deleted pet!";
    return;
}
?>
<html>
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