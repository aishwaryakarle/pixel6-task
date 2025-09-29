<?php
include 'connect.php'; // database connection
// Check if id is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM animals WHERE id='$id'";
    $connection->query($sql);
}

// display index page after deleting animal info
header("Location: index.php");
exit;
?>
