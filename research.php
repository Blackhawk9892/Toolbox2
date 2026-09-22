<?php
session_start();
require("toolbar_sales.php");
?>

  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Research System</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #aaa;
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 14px;
            font-size: 18px;
            background: #0066cc;
            color: white;
            border: 0;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #004d99;
        }

        #status {
            margin-top: 20px;
            padding: 15px;
            display: none;
            background: #eef5ff;
        }
    </style>
</head>

<body>
    <?php

$year  = $_GET['year']  ?? '';
$make  = $_GET['make']  ?? '';
$model = $_GET['model'] ?? '';
$trim  = $_GET['trim']  ?? '';

?>

<div class="container">

    <h1>Vehicle Research</h1>

    <form id="vehicleForm">

      <label>Year</label>
<input
    type="number"
    name="year"
    value="<?php echo htmlspecialchars($year); ?>"
    required
>

<label>Make</label>
<input
    type="text"
    name="make"
    value="<?php echo htmlspecialchars($make); ?>"
    required
>

<label>Model</label>
<input
    type="text"
    name="model"
    value="<?php echo htmlspecialchars($model); ?>"
    required
>

<label>Trim</label>
<input
    type="text"
    name="trim"
    value="<?php echo htmlspecialchars($trim); ?>"
    required
>

        <button type="submit">
            Research Vehicle
        </button>

    </form>

    <div id="status"></div>

</div>

<script>

document.getElementById("vehicleForm")
.addEventListener("submit", async function(e) {

    e.preventDefault();

    const status = document.getElementById("status");

    status.style.display = "block";
    status.innerHTML =
        "Researching vehicle. This may take a little while...";

    const formData = new FormData(this);

    try {

        const response = await fetch(
            "research_vehicle.php",
            {
                method: "POST",
                body: formData
            }
        );

        const result = await response.json();

        if (result.success) {

            status.innerHTML =
                "<strong>Research Complete</strong><br><br>" +
                result.message;

        } else {

            status.innerHTML =
                "<strong>Error:</strong> " +
                result.message;
        }

    } catch (error) {

        status.innerHTML =
            "<strong>Program Error:</strong> " +
            error;
    }

});

</script>

</body>
</html>