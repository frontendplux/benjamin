<?php

include __DIR__ . "/api/conn.php";

$query = file_get_contents(__DIR__ . "/query.sql");

if (!$query) {
    die("Failed to read query.sql");
}

if ($conn->multi_query($query)) {

    // Flush all results
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());

    echo "SQL imported successfully";

} else {
    echo "Import failed: " . $conn->error;
}