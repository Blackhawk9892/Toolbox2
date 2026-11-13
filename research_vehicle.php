
<?php
/*
|--------------------------------------------------------------------------
| research_vehicle.php
|--------------------------------------------------------------------------
| PHP 7.3 / 8.2 / 8.3
|
| Research:
| 1. Awards and safety ratings
| 2. Vehicle specifications
| 3. Five competitors and advantages
|
| Save results to MySQL table: specifications
|--------------------------------------------------------------------------
*/

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', '0');
ini_set('log_errors', '1');

require_once __DIR__ . '/includes/constants.php';
require_once __DIR__ . '/includes/connection.php';

function respond($success, $message, $extra = array())
{
    echo json_encode(
        array_merge(
            array(
                'success' => $success,
                'message' => $message
            ),
            $extra
        ),
        JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit;
}

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
| Assumes connection.php creates $con (mysqli).
*/

if (!isset($con) || !($con instanceof mysqli)) {
    respond(false, 'Database connection $con not found.');
}

if ($con->connect_error) {
    respond(false, 'Database connection failed.');
}

$con->set_charset('utf8mb4');

/*
|--------------------------------------------------------------------------
| OPENAI API KEY
|--------------------------------------------------------------------------
*/

if (!defined('OPENAI_API_KEY') ||
    trim(OPENAI_API_KEY) === '') {
    respond(false, 'OPENAI_API_KEY is not defined.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'POST request required.');
}

/*
|--------------------------------------------------------------------------
| VEHICLE INPUT
|--------------------------------------------------------------------------
*/

$year  = trim((string)($_POST['year'] ?? ''));
$make  = trim((string)($_POST['make'] ?? ''));
$model = trim((string)($_POST['model'] ?? ''));
$trim  = trim((string)($_POST['trim'] ?? ''));

if (
    !preg_match('/^(19|20)\d{2}$/', $year) ||
    $make === '' ||
    $model === '' ||
    $trim === ''
) {
    respond(false, 'Valid Year, Make, Model and Trim are required.');
}

$vehicle = "$year $make $model $trim";

/*
|--------------------------------------------------------------------------
| RESEARCH INSTRUCTIONS
|--------------------------------------------------------------------------
*/

$instructions = <<<'PROMPT'
You are an expert automotive research analyst.

Research the exact vehicle provided by the user.

Return accurate, useful automotive information.

Use United States vehicle specifications.

Do not invent awards, safety ratings, specifications,
or competitor advantages.

If a fact cannot be confirmed, identify it as unverified.

If specifications vary by configuration, explain that.

AWARDS AND SAFETY:
Include NHTSA crash ratings, IIHS awards, J.D. Power
awards, and other recognized third-party awards when
they apply to the exact model year.

Clearly distinguish actual awards from general ratings.
Never claim an award that has not been confirmed.

SPECIFICATIONS:
Include:
- Engine and displacement
- Horsepower
- Torque
- Transmission
- Drivetrain
- Fuel economy
- Fuel tank
- Seating
- Dimensions
- Wheelbase
- Cargo capacity
- Towing capacity
- Payload
- Suspension
- Brakes
- Safety features
- Technology
- Warranty
- Other important specifications

COMPETITION:
Select exactly five relevant direct competitors
when five genuine competitors exist.

For each competitor include:
- Year, make, model and comparable trim
- Important specifications
- Advantages of the selected vehicle
- Advantages of the competitor

Be objective and explain meaningful differences.
Do not invent numerical comparisons.

Return only valid JSON using this structure:

{
  "awards": {
    "safety_ratings": [],
    "awards": [],
    "notes": []
  },
  "specifications": {
    "engine": "",
    "horsepower": "",
    "torque": "",
    "transmission": "",
    "drivetrain": "",
    "fuel_economy": "",
    "fuel_tank": "",
    "seating": "",
    "dimensions": "",
    "wheelbase": "",
    "cargo_capacity": "",
    "towing_capacity": "",
    "payload": "",
    "suspension": "",
    "brakes": "",
    "safety_features": [],
    "technology": [],
    "warranty": "",
    "additional": []
  },
  "competition": [
    {
      "vehicle": "",
      "specifications": [],
      "selected_vehicle_advantages": [],
      "competitor_advantages": []
    }
  ]
}
PROMPT;

/*
|--------------------------------------------------------------------------
| OPENAI REQUEST
|--------------------------------------------------------------------------
*/

$payload = array(
    'model' => 'gpt-4.1-mini',
    'temperature' => 0.2,
    'response_format' => array(
        'type' => 'json_object'
    ),
    'messages' => array(
        array(
            'role' => 'system',
            'content' => $instructions
        ),
        array(
            'role' => 'user',
            'content' => 'Research this vehicle: ' . $vehicle
        )
    )
);

$ch = curl_init('https://api.openai.com/v1/chat/completions');

curl_setopt_array($ch, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . OPENAI_API_KEY,
        'Content-Type: application/json'
    ),
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_CONNECTTIMEOUT => 20,
    CURLOPT_TIMEOUT => 180
));

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($response === false) {
    respond(false, 'OpenAI connection error: ' . $curlError);
}

$apiResult = json_decode($response, true);

if ($httpCode !== 200) {
    $apiMessage = $apiResult['error']['message']
        ?? 'Unknown API error';

    respond(false, 'OpenAI API error: ' . $apiMessage);
}

$content = $apiResult['choices'][0]['message']['content'] ?? '';

$research = json_decode($content, true);

if (!is_array($research)) {
    respond(false, 'OpenAI returned invalid research JSON.');
}

/*
|--------------------------------------------------------------------------
| VALIDATE RESEARCH
|--------------------------------------------------------------------------
*/

if (
    !isset($research['awards']) ||
    !isset($research['specifications']) ||
    !isset($research['competition']) ||
    !is_array($research['awards']) ||
    !is_array($research['specifications']) ||
    !is_array($research['competition'])
) {
    respond(false, 'Research response is missing required sections.');
}

if (count($research['competition']) !== 5) {
    respond(false, 'Research did not return exactly five competitors.');
}

foreach ($research['competition'] as $competitor) {
    if (
        !is_array($competitor) ||
        empty($competitor['vehicle']) ||
        !isset($competitor['specifications']) ||
        !isset($competitor['selected_vehicle_advantages']) ||
        !isset($competitor['competitor_advantages']) ||
        !is_array($competitor['specifications']) ||
        !is_array($competitor['selected_vehicle_advantages']) ||
        !is_array($competitor['competitor_advantages'])
    ) {
        respond(false, 'Incomplete competitor information returned.');
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE DATABASE VALUES
|--------------------------------------------------------------------------
*/

$jsonFlags = JSON_UNESCAPED_UNICODE |
             JSON_UNESCAPED_SLASHES |
             JSON_INVALID_UTF8_SUBSTITUTE;

$awards = json_encode($research['awards'], $jsonFlags);

$specifications = json_encode(
    $research['specifications'],
    $jsonFlags
);

$competition = json_encode(
    $research['competition'],
    $jsonFlags
);

if (
    $awards === false ||
    $specifications === false ||
    $competition === false
) {
    respond(false, 'Unable to encode research data.');
}

/*
|--------------------------------------------------------------------------
| SAVE TO MYSQL
|--------------------------------------------------------------------------
*/

$sql = "INSERT INTO specifications
        (
            spec_year,
            spec_make,
            spec_model,
            spec_trim,
            spec_awards,
            spec_specif,
            spec_competition
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)";

try {

    $stmt = $con->prepare($sql);

    if (!$stmt) {
        throw new Exception($con->error);
    }

    $stmt->bind_param(
        'sssssss',
        $year,
        $make,
        $model,
        $trim,
        $awards,
        $specifications,
        $competition
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    $recordId = $con->insert_id;

    $stmt->close();

} catch (Throwable $e) {

    error_log('Vehicle research database error: ' . $e->getMessage());

    respond(false, 'Unable to save vehicle research to the database.');
}

/*
|--------------------------------------------------------------------------
| RETURN RESULTS
|--------------------------------------------------------------------------
*/

respond(
    true,
    'Vehicle research completed and saved.',
    array(
        'vehicle' => $vehicle,
        'record_id' => $recordId,
        'specifications_saved' => 1,
        'awards_saved' => 1,
        'competitors_saved' => count($research['competition']),
        'research' => $research
    )
);
