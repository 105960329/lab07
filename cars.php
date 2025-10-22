<?php
require_once("settings.php");


$conn = mysqli_connect($host, $user, $pwd, $sql_db);


if (!$conn) {
    die("<p class='error-msg'>Database connection failure.</p>");
} else {
    echo "<p class='success-msg'> Database connection successful</p>";
}


$query = "SELECT * FROM cars";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Used Cars List</title>
    
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Used Cars Available</h1>

<?php
if (!$result) {
    echo "<p class='error-msg'>Something went wrong with the query.</p>";
} else {
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Make</th><th>Model</th><th>Price ($)</th><th>Year</th></tr>";

        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['car_id'] . "</td>";
            echo "<td>" . $row['make'] . "</td>";
            echo "<td>" . $row['model'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['yom'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p class='info-msg'>There are no cars to display.</p>";
    }

    mysqli_free_result($result);
}

mysqli_close($conn);
?>

</body>
</html>
