<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Student Result</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      text-align: center;
      padding: 50px;
    }
    input[type="text"] {
      padding: 10px;
      width: 250px;
      font-size: 16px;
    }
    input[type="submit"] {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
    }
    table {
      margin: 30px auto;
      border-collapse: collapse;
      width: 80%;
    }
    th, td {
      border: 1px solid #999;
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #4CAF50;
      color: white;
    }
  </style>
</head>
<body>

  <h1>Search Student Result</h1>

  <form method="post">
    <input type="text" name="rid" placeholder="Enter your RID" required>
    <input type="submit" value="Search">
  </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rid = trim($_POST["rid"]);

    // Google Sheet CSV Link
    $googleSheetUrl = "https://docs.google.com/spreadsheets/d/1FGqVtsQAQvke157cj2nHjIO84PhMGWpVunHUrRkz4Og/export?format=csv&gid=0";

    // Fetch the CSV data
    $csv = file_get_contents($googleSheetUrl);
    if ($csv === false) {
        echo "<p style='color:red;'>Failed to fetch Google Sheet data. Check your link or internet connection.</p>";
        exit;
    }

    // Parse CSV into array
    $lines = explode("\n", $csv);
    $data = array_map("str_getcsv", $lines);

    // Extract headers
    $headers = $data[0];

    // Search for the matching row
    $found = false;
    for ($i = 1; $i < count($data); $i++) {
        if (isset($data[$i][0]) && trim($data[$i][0]) === $rid) {
            $found = true;
            echo "<h2>Result Found for RID: <strong>$rid</strong></h2>";
            echo "<table><tr>";
            foreach ($headers as $header) {
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "</tr><tr>";
            foreach ($data[$i] as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr></table>";
            break;
        }
    }

    if (!$found) {
        echo "<p style='color:red;'>No result found for RID: <strong>$rid</strong></p>";
    }
}
?>

</body>
</html>
