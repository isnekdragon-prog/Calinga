<?php
require_once __DIR__ . '/db.php';

if(isset($_GET['code'])){

    $code = $_GET['code'];

    $query = "UPDATE users SET is_verified = 1, activation_code = NULL WHERE activation_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $code);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        echo "Account successfully activated!";
    } else {
        echo "Invalid or already used activation code.";
    }

    $stmt->close();
} else {
    echo "No activation code provided.";
}
?>
