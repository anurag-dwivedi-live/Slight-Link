<?php 

$conn = mysqli_connect("localhost", "root", "", "slightlink");

function isValidURL($url) {
    $url = trim($url);
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $initial_url = mysqli_real_escape_string($conn, $_POST["url"]);
    if (!empty($initial_url)) {
        if (!(isValidURL($initial_url))) {
           header("location: /");
           die();
        }
        $final_url = uniqid();
        $final_url = substr($final_url, -5);
        $check_sql = "SELECT * FROM `urls` WHERE `final_url` = '$final_url'";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) == 0) {    
            $expire = time() + (86400 * 30);
            $sql = "INSERT INTO `urls`(`id`, `initial_url`, `final_url`, `clicks`, `created_at`) VALUES (NULL, '$initial_url', '$final_url', 0, current_timestamp());";
            $result = mysqli_query($conn, $sql);
            if($result) {
                echo "200";
                $encoded_url = rawurlencode($initial_url);
                setcookie($final_url, $encoded_url, $expire, '/');
            } else {
                echo "Something went wrong";
            }
        } else {
            echo "An unknown error occured... Please try again";
        }
    } else {
        echo "Please enter valid url";
    }
}

?>