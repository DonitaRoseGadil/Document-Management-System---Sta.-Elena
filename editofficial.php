<?php

include 'connect.php'; // your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST data and sanitize as needed
    $id = $_POST['official_id'];
    $position = $_POST['position'];
    $surname = $_POST['surname'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $birthday = $_POST['birthday'];
    $birthplace = $_POST['birthplace'];
    $address = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $education_attainment = $_POST['education_attainment'];
    $education_school = $_POST['education_school'];
    $education_date = $_POST['education_date'];
    $civil_status = $_POST['civil_status'];
    $spouse_name = $_POST['spouse_name'];
    $spouse_birthday = $_POST['spouse_birthday'];
    $spouse_birthplace = $_POST['spouse_birthplace'];
    $dependents = $_POST['dependents'];
    $gsis_number = $_POST['gsis_number'];
    $pagibig_number = $_POST['pagibig_number'];
    $philhealth_number = $_POST['philhealth_number'];

    // Handle photo upload if exists
    $photo_path = null; // default if no new photo uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/photos/"; // your upload folder
        $file_name = basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name; // unique filename

        // Optional: Check file type, size here

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_path = $target_file;
        } else {
            echo "Sorry, there was an error uploading your photo.";
            exit;
        }
    }

    // Prepare the SQL query with or without photo update
    if ($photo_path) {
        $sql = "UPDATE officials SET
            position = ?, surname = ?, firstname = ?, middlename = ?,
            birthday = ?, birthplace = ?, address = ?, mobile_number = ?,
            email = ?, gender = ?, education_attainment = ?, education_school = ?,
            education_date = ?, civil_status = ?, spouse_name = ?, spouse_birthday = ?,
            spouse_birthplace = ?, dependents = ?, gsis_number = ?, pagibig_number = ?,
            philhealth_number = ?, photo_path = ?
            WHERE id = ?";
    } else {
        $sql = "UPDATE officials SET
            position = ?, surname = ?, firstname = ?, middlename = ?,
            birthday = ?, birthplace = ?, address = ?, mobile_number = ?,
            email = ?, gender = ?, education_attainment = ?, education_school = ?,
            education_date = ?, civil_status = ?, spouse_name = ?, spouse_birthday = ?,
            spouse_birthplace = ?, dependents = ?, gsis_number = ?, pagibig_number = ?,
            philhealth_number = ?
            WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);

    if ($photo_path) {
        $stmt->bind_param("ssssssssssssssssssssssi",
            $position, $surname, $firstname, $middlename,
            $birthday, $birthplace, $address, $mobile_number,
            $email, $gender, $education_attainment, $education_school,
            $education_date, $civil_status, $spouse_name, $spouse_birthday,
            $spouse_birthplace, $dependents, $gsis_number, $pagibig_number,
            $philhealth_number, $photo_path, $id
        );
    } else {
        $stmt->bind_param("sssssssssssssssssssssi",
            $position, $surname, $firstname, $middlename,
            $birthday, $birthplace, $address, $mobile_number,
            $email, $gender, $education_attainment, $education_school,
            $education_date, $civil_status, $spouse_name, $spouse_birthday,
            $spouse_birthplace, $dependents, $gsis_number, $pagibig_number,
            $philhealth_number, $id
        );
    }

    if ($stmt->execute()) {
        header("Location: officials_list.php?success=1");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
