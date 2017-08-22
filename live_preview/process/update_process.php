<?php
require_once('../session.php');

if(!isset($row['email'])){
    echo 404;
    exit;
}

$id = $row['id'];
$columnName = strip_tags(trim($_POST['columnName']));
$newColumnValue = strip_tags(trim($_POST['newColumnValue']));

$columnName = mysqli_real_escape_string($db_connect, $columnName);
$newColumnValue = mysqli_real_escape_string($db_connect, $newColumnValue);

if(isset($newColumnValue)){
  
    $sql = "UPDATE clients SET ";
    $sql.= "$columnName = '$newColumnValue' WHERE id = $id";
    $change = mysqli_query($db_connect, $sql);

    if($change){
        echo 1001;
        exit;
    }else{
        echo 404;
        exit;
    }  
    
    
}else{
    echo 404;
    exit;
}


//if($columnName == 'first_name' || $columnName == 'last_name'){
//    if(is_string($newColumnValue)){
//        $sql = "UPDATE clients SET $columnName = '$newColumnValue' WHERE id = $id";
//        $change = mysqli_query($db_connect, $sql);
//        if($change){
//            echo 1001;
//            exit;
//        }else{
//            echo 404;
//            exit;
//        }
//
//    }else{
//        echo 404;
//        exit;
//    }
//}
//if($columnName == 'phone_number'){
//    if(is_int($newColumnValue)){
//        if($newColumnValue == 9 || $newColumnValue == 11){
//            $sql = "UPDATE clients SET $columnName = '$newColumnValue' WHERE id = $id";
//            $change = mysqli_query($db_connect, $sql);
//
//            if($change){
//                echo 1001;
//                exit;
//            }else{
//                echo 404;
//                exit;
//            }   
//        }
//
//    }else{
//        echo 404;
//        exit;
//    }
//}


?>