<?php require_once './database/connection.php'; ?>

<?php
$form_input = file_get_contents("php://input");
$_POST = json_decode($form_input, true);     //This is a method to get data from JS and decode it for php use.

// var_dump($_POST);

// echo json_encode($_POST);
if (isset($_POST['submit'])) {
    $name = htmlspecialchars(($_POST['name']));
    $email = htmlspecialchars(($_POST['email']));
    $id = htmlspecialchars($_POST['id']);



    if (empty($name)) {
        echo json_encode(["emptyName" => "Enter your name Please!"]);
    } elseif (empty($email)) {
        echo json_encode(["emptyEmail" => "Enter your E-mail Please!"]);
    } else {

        $sql = "SELECT * FROM `users` WHERE `email` = '$email' AND `id` != '$id'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            $sql = "UPDATE `users` SET `name`='$name',`email`='$email' WHERE `id` = '$id'";
            if ($conn->query($sql)) {
                echo json_encode(["success" => "User successfully updated!"]);
            } else {
                echo json_encode(["error" => "User has failed to updated!"]);
            }
        } else {
            echo json_encode(["error" => "E-mail already Exist!"]);
        }
    }
}
