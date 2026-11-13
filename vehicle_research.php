<?php

/*
|--------------------------------------------------------------------------
| VEHICLE RESEARCH
|--------------------------------------------------------------------------
|
| Accepts information from:
|
| 1. GET
| 2. POST
| 3. Manual entry
|
*/

$year  = '';
$make  = '';
$model = '';
$trim  = '';

if (isset($_REQUEST['year'])) {
    $year = trim($_REQUEST['year']);
}

if (isset($_REQUEST['make'])) {
    $make = trim($_REQUEST['make']);
}

if (isset($_REQUEST['model'])) {
    $model = trim($_REQUEST['model']);
}

if (isset($_REQUEST['trim'])) {
    $trim = trim($_REQUEST['trim']);
}

function h($value)
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Vehicle Research</title>

<style>

body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 30px;
}

.container {
    max-width: 900px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
}

h1 {
    margin-top: 0;
}

.form-row {
    margin-bottom: 18px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 6px;
}

input {
    width: 100%;
    box-sizing: border-box;
    padding: 12px;
    font-size: 16px;
}

button {
    padding: 14px 25px;
    font-size: 17px;
    cursor: pointer;
}

#status {
    margin-top: 25px;
    padding: 15px;
    display: none;
    background: #eeeeee;
    white-space: pre-wrap;
}

</style>

</head>

<body>

<div class="container">

<h1>Vehicle Research</h1>

<p>
Enter the vehicle you want to research.
</p>

<form id="vehicleForm">

<div class="form-row">

<label for="year">
Year
</label>

<input
    type="number"
    id="year"
    name="year"
    min="1900"
    max="2100"
    required
    value="<?php echo h($year); ?>"
>

</div>


<div class="form-row">

<label for="make">
Make
</label>

<input
    type="text"
    id="make"
    name="make"
    required
    value="<?php echo h($make); ?>"
>

</div>


<div class="form-row">

<label for="model">
Model
</label>

<input
    type="text"
    id="model"
    name="model"
    required
    value="<?php echo h($model); ?>"
>

</div>


<div class="form-row">

<label for="trim">
Trim
</label>

<input
    type="text"
    id="trim"
    name="trim"
    required
    value="<?php echo h($trim); ?>"
>

</div>


<button type="submit">
Research Vehicle
</button>

</form>


<div id="status"></div>

</div>

<script>

document
.getElementById("vehicleForm")
.addEventListener("submit", function(event) {

    event.preventDefault();

    var status = document.getElementById("status");

    status.style.display = "block";

    status.innerHTML =
        "<strong>Researching vehicle...</strong><br><br>" +
        "This may take a little while.";

    var formData = new FormData(this);

    fetch("research_vehicle.php", {

        method: "POST",
        body: formData

    })
    .then(function(response) {

        /*
         * IMPORTANT:
         * Read the response as TEXT first.
         *
         * This allows us to see PHP warnings, fatal errors,
         * HTML error pages, etc.
         */
        return response.text();

    })
    .then(function(rawResponse) {

        /*
         * Display the exact response in the browser console.
         */
        console.log("RAW RESPONSE FROM research_vehicle.php:");
        console.log(rawResponse);

        /*
         * Now try to convert the response to JSON.
         */
        try {

            var data = JSON.parse(rawResponse);

            if (data.success) {

                status.innerHTML =
                    "<strong>Research Complete</strong><br><br>" +

                    "Vehicle: " +
                    data.vehicle +
                    "<br><br>" +

                    "Specifications record saved: " +
                    data.specifications_saved +
                    "<br>" +

                    "Options saved: " +
                    data.options_saved +
                    "<br>" +

                    "Competitors saved: " +
                    data.competitors_saved;

            } else {

                status.innerHTML =
                    "<strong>Error:</strong><br><br>" +
                    data.message;
            }

        } catch (error) {

            /*
             * The PHP program did NOT return valid JSON.
             *
             * Show exactly what PHP returned.
             */
            status.innerHTML =
                "<strong>research_vehicle.php did not return valid JSON.</strong>" +
                "<br><br>" +

                "<strong>Server Response:</strong>" +
                "<br><br>" +

                "<pre style='white-space:pre-wrap; text-align:left;'>" +
                escapeHtml(rawResponse) +
                "</pre>" +

                "<br><strong>JSON Error:</strong><br>" +
                escapeHtml(error.toString());
        }

    })
    .catch(function(error) {

        status.innerHTML =
            "<strong>Program Error:</strong><br><br>" +
            escapeHtml(error.toString());

    });

});


/*
 * Prevent HTML returned by PHP from being interpreted
 * as actual HTML when we display the error.
 */
function escapeHtml(text) {

    var div = document.createElement("div");

    div.textContent = text;

    return div.innerHTML;
}

</script>

</body>
</html>