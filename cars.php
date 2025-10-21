
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h3>Diagnostic: starting cars.php</h3>";

require_once("settings.php");
echo "<p>Loaded settings.php — trying DB connect to '{$sql_db}' as '{$user}'@'{$host}'</p>";


$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    echo "<h4 style='color:red;'>Database connection FAILED</h4>";
    echo "<p>MySQL error: " . htmlspecialchars(mysqli_connect_error()) . "</p>";
    echo "<p>Check: XAMPP MySQL running, settings.php values, and DB name.</p>";
    exit;
} else {
    echo "<p style='color:green;'>Connected to MySQL successfully.</p>";
}


$res = mysqli_query($conn, "SHOW TABLES LIKE 'cars'");
if (!$res) {
    echo "<p style='color:red;'>SHOW TABLES query failed: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
} else {
    $has = mysqli_num_rows($res);
    echo "<p>SHOW TABLES returned count: $has</p>";
    if ($has === 0) {
        echo "<p style='color:red;'>Table 'cars' does not exist in database '{$sql_db}'.</p>";
        echo "<p>Run the CREATE TABLE SQL in phpMyAdmin or see instructions.</p>";
        mysqli_close($conn);
        exit;
    } else {
        echo "<p style='color:green;'>Table 'cars' exists.</p>";
    }
}


$query = "SELECT * FROM cars LIMIT 10";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "<p style='color:red;'>SELECT query failed: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    mysqli_close($conn);
    exit;
}

$num = mysqli_num_rows($result);
echo "<p>SELECT returned $num rows.</p>";

if ($num == 0) {
    echo "<p style='color:orange;'>There are no rows in 'cars'. You may need to insert sample data.</p>";
} else {
    echo "<table border='1' cellpadding='6'><thead><tr>";
    
    $first = mysqli_fetch_assoc($result);
    if ($first) {
        foreach (array_keys($first) as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr></thead><tbody>";
        
        echo "<tr>";
        foreach ($first as $v) echo "<td>" . htmlspecialchars($v) . "</td>";
        echo "</tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $v) echo "<td>" . htmlspecialchars($v) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Unexpected: could not fetch first row.</p>";
    }
}

mysqli_free_result($result);
mysqli_close($conn);

echo "<p>Diagnostic: finished.</p>";
?>
