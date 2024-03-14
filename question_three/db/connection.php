<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 */

require_once('config.php');


try {
    $conn = new PDO("mysql:host=$SERVERNAME;port=$PORT;dbname=$DBNAME", $USERNAME, $PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit();
}
