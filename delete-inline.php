<?php

include "db_connect.php";

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE id = $id";
$result = mysqli_query($conn, $sql) or die("Querry Unsuccessful!");

header("Location: http://localhost/voting/index.php?page=users");

mysqli_close($conn);