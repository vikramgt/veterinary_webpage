if (isset($_POST["submit"])) {
require "functions/edit-pet-functions.php";
edit($_GET["pet_ID"], $_POST["name"], $_POST["species"], $_POST["age"], $_POST["gender"]);
$_SESSION["success"] = "Pet Details Updated";
header("location: index-owner.php");
return;
}


<div class="container text-white">
    <form method="post" class="form_class">
        <div class="form-group row form_element_class">
            <label for="name" class="col-sm-3 col-form-label">Enter Pet's Name</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter your's pet name" value="<?= isset($_POST["name"]) ? $_POST["name"] : "" ?>">
            </div>
        </div>

        <div class="form-group row form_element_class">
            <label for="species_name" class="col-sm-3 col-form-label">Enter Pet's Species</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="species_name" id="species_name" placeholder="Enter your pet's species" value="<?= isset($_POST["species_name"]) ? $_POST["species_name"] : "" ?>">
            </div>
        </div>

        <div class="form-group row form_element_class">
            <label for="age" class="col-sm-3 col-form-label">Enter Pet's Age</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" name="age" id="age" placeholder="Enter your pet's age" value="<?= isset($_POST["age"]) ? $_POST["age"] : "" ?>">
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
            <div><input class="btn btn-info col-4 button_class" type="submit" name="add-pet" value="Add Pet"></div>
            <div><input class="btn btn-warning col-4 button_class" type="submit" name="cancel" value="Cancel"></div>
        </div>
    </form>
</div>





<div class="container text-center">
    <div class="card-body">

        <form method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value=<?= $pet_details["name"] ?>>
            <label for="species">Species</label>
            <input type="text" name="species" id="species" value=<?= $pet_details["species_name"] ?>>
            <label for="age">Age</label>
            <input type="text" name="age" id="age" value=<?= $pet_details["age"] ?>>

            <label for="gender">Gender</label>

            <select name="gender" id="gender" class="form-control">
                <option value="Not Specified" <?= (isset($_POST["gender"]) && $_POST["gender"] === "Not Specified") ? "selected" : "" ?>>Select</option>
                <option value="M" <?= (isset($_POST["gender"]) && $_POST["gender"] === "M") ? "selected" : "" ?>>Male</option>
                <option value="F" <?= (isset($_POST["gender"]) && $_POST["gender"] === "F") ? "selected" : "" ?>>Female</option>
            </select>

            <input class="btn btn-info col-4 button_class" type="submit" name="submit" value="Insert">
        </form>

    </div>
</div>