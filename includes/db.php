<!-- This is where we set up our database. We will use this file to connect to our database. 
 
    We will also use this file to all other files here in this project as a global database connection.

    -->

<?php

$conn = mysqli_connect("localhost", "root", "", "dynabio");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>