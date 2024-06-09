<?php
require 'dbcon.php';

if(isset($_POST['save_student'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $profile_picture = $_FILES['profile_picture']['name'];
    
    if(!empty($profile_picture)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
    } else {
        $profile_picture = null;
    }

    $query = "INSERT INTO students (name, email, phone, course, profile_picture) VALUES ('$name', '$email', '$phone', '$course', '$profile_picture')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Student Created Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student Not Created'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['update_student'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $profile_picture = $_FILES['profile_picture']['name'];

    if(!empty($profile_picture)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $query = "UPDATE students SET name='$name', email='$email', phone='$phone', course='$course', profile_picture='$profile_picture' WHERE id='$student_id'";
    } else {
        $query = "UPDATE students SET name='$name', email='$email', phone='$phone', course='$course' WHERE id='$student_id'";
    }

    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Student Updated Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_GET['student_id'])) {
    $student_id = mysqli_real_escape_string($con, $_GET['student_id']);

    $query = "SELECT * FROM students WHERE id='$student_id'";
    $query_run = mysqli_query($con, $query);

    if(mysqli_num_rows($query_run) == 1) {
        $student = mysqli_fetch_array($query_run);
        $student['profile_picture'] = !empty($student['profile_picture']) ? 'uploads/' . $student['profile_picture'] : 'default_profile_picture.jpg'; // Assuming you have a default picture
        $res = [
            'status' => 200,
            'message' => 'Student Fetch Successfully by id',
            'data' => $student
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'Student Id Not Found'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['delete_student'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);

    $query = "DELETE FROM students WHERE id='$student_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Student Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}
?>
