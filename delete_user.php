<?php require_once './database/connection.php'; ?>

<?php 
$form_input = file_get_contents("php://input");
$_POST = json_decode($form_input, true);     //This is a method to get data from JS and decode it for php use.
    
    // var_dump($_POST);
    
    // echo json_encode($_POST);
    if (isset ($_POST['submit'])){
        $id = htmlspecialchars($_POST['id']);

        $sql = "DELETE FROM `users` WHERE `id` = '$id'";
        if($conn->query($sql)){
            echo json_encode(["success"=>"User has successfully deleted!"]);
        }else{
            echo json_encode(["error"=>"User has failed to delete!"]);

        }

    }

    // if($conn->query($sql)){
    //     header("location: ./index_.php");
    // }


?>