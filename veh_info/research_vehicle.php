<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/database.php';


/*
|--------------------------------------------------------------------------
| ERROR RESPONSE
|--------------------------------------------------------------------------
*/

function errorResponse($message)
{
    echo json_encode(array(
        'success' => false,
        'message' => $message
    ));

    exit;
}


/*
|--------------------------------------------------------------------------
| GET INPUT
|--------------------------------------------------------------------------
*/

$year = isset($_POST['year'])
    ? trim($_POST['year'])
    : '';

$make = isset($_POST['make'])
    ? trim($_POST['make'])
    : '';

$model = isset($_POST['model'])
    ? trim($_POST['model'])
    : '';

$trim = isset($_POST['trim'])
    ? trim($_POST['trim'])
    : '';


if ($year === '' ||
    $make === '' ||
    $model === '' ||
    $trim === '') {

    errorResponse(
        'Year, Make, Model and Trim are required.'
    );
}


if (!preg_match('/^[0-9]{4}$/', $year)) {

    errorResponse(
        'Please enter a valid four-digit year.'
    );
}


/*
|--------------------------------------------------------------------------
| API KEY
|--------------------------------------------------------------------------
*/

if (
    !defined('OPENAI_API_KEY') ||
    OPENAI_API_KEY === '' ||
    OPENAI_API_KEY === 'YOUR_OPENAI_API_KEY'
) {

    errorResponse(
        'The OpenAI API key has not been configured.'
    );
}


/*
|--------------------------------------------------------------------------
| VEHICLE NAME
|--------------------------------------------------------------------------
*/

$vehicleName =
    $year . ' ' .
    $make . ' ' .
    $model . ' ' .
    $trim;


/*
|--------------------------------------------------------------------------
| RESEARCH INSTRUCTIONS
|--------------------------------------------------------------------------
*/

$instructions = <<<TEXT

You are an automotive research analyst.

Research this exact United States market vehicle:

{$vehicleName}

Use web research to find accurate information.

The information will be stored in a vehicle dealership
sales-training database.

Research these three areas:

1. VEHICLE SPECIFICATIONS

Find as many useful specifications as reasonably possible.

Include when applicable:

engine
engine size
engine configuration
horsepower
torque
transmission
drivetrain
fuel type
EPA fuel economy
battery
electric range
charging
towing capacity
payload
curb weight
GVWR
wheelbase
length
width
height
ground clearance
seating capacity
passenger volume
cargo volume
fuel tank
wheels
tires
brakes
suspension
steering
safety equipment
driver assistance
infotainment
audio
connectivity
interior equipment
exterior equipment
warranty

Use specifications for the requested trim whenever possible.

Do not substitute another trim without clearly saying so.


2. AWARDS AND THIRD-PARTY RECOGNITION

Find legitimate awards, safety ratings and recognition.

Examples of acceptable sources include:

NHTSA
IIHS
J.D. Power
Kelley Blue Book
MotorTrend
Car and Driver
Edmunds
U.S. News
manufacturer documentation
other established automotive organizations

Do not invent awards.

If an award cannot be verified, do not include it.


3. FACTORY OPTIONS

Find factory options and option/package codes for the
requested vehicle and trim.

Include:

individual options
packages
equipment groups
option codes
package codes

IMPORTANT:

Never invent an option code.

If the option is verified but the code cannot be verified,
use an empty string for the code.

Do not confuse standard equipment with optional equipment.


4. COMPETITORS

Find vehicles that directly compete with the requested
vehicle.

For each competitor provide:

vehicle name
important specifications
reasons a salesperson could truthfully explain advantages
of the requested vehicle compared with that competitor

Selling points must be factual.

Do not make unsupported claims.

Where vehicles differ by configuration, explain that
configuration affects the comparison.

TEXT;


/*
|--------------------------------------------------------------------------
| JSON SCHEMA
|--------------------------------------------------------------------------
*/

$schema = array(

    'type' => 'object',

    'properties' => array(

        'awards' => array(
            'type' => 'array',
            'items' => array(
                'type' => 'string'
            )
        ),

        'specifications' => array(
            'type' => 'array',
            'items' => array(

                'type' => 'object',

                'properties' => array(

                    'name' => array(
                        'type' => 'string'
                    ),

                    'value' => array(
                        'type' => 'string'
                    )

                ),

                'required' => array(
                    'name',
                    'value'
                ),

                'additionalProperties' => false
            )
        ),

        'options' => array(
            'type' => 'array',

            'items' => array(

                'type' => 'object',

                'properties' => array(

                    'code' => array(
                        'type' => 'string'
                    ),

                    'option' => array(
                        'type' => 'string'
                    )

                ),

                'required' => array(
                    'code',
                    'option'
                ),

                'additionalProperties' => false
            )
        ),

        'competitors' => array(

            'type' => 'array',

            'items' => array(

                'type' => 'object',

                'properties' => array(

                    'vehicle' => array(
                        'type' => 'string'
                    ),

                    'selling_points' => array(
                        'type' => 'array',
                        'items' => array(
                            'type' => 'string'
                        )
                    ),

                    'specifications' => array(
                        'type' => 'array',

                        'items' => array(

                            'type' => 'object',

                            'properties' => array(

                                'name' => array(
                                    'type' => 'string'
                                ),

                                'value' => array(
                                    'type' => 'string'
                                )

                            ),

                            'required' => array(
                                'name',
                                'value'
                            ),

                            'additionalProperties' => false
                        )
                    )

                ),

                'required' => array(
                    'vehicle',
                    'selling_points',
                    'specifications'
                ),

                'additionalProperties' => false
            )
        )
    ),

    'required' => array(
        'awards',
        'specifications',
        'options',
        'competitors'
    ),

    'additionalProperties' => false
);


/*
|--------------------------------------------------------------------------
| CREATE OPENAI REQUEST
|--------------------------------------------------------------------------
*/

$request = array(

    'model' => OPENAI_MODEL,

    'tools' => array(
        array(
            'type' => 'web_search'
        )
    ),

    'input' => $instructions,

    'text' => array(

        'format' => array(

            'type' => 'json_schema',

            'name' => 'vehicle_research',

            'strict' => true,

            'schema' => $schema
        )
    )
);


/*
|--------------------------------------------------------------------------
| SEND REQUEST
|--------------------------------------------------------------------------
*/

$ch = curl_init(
    'https://api.openai.com/v1/responses'
);

curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);

curl_setopt(
    $ch,
    CURLOPT_POST,
    true
);

curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY
    )
);

curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    json_encode($request)
);

curl_setopt(
    $ch,
    CURLOPT_TIMEOUT,
    180
);


$response = curl_exec($ch);


if ($response === false) {

    $curlError = curl_error($ch);

    curl_close($ch);

    errorResponse(
        'OpenAI connection error: ' .
        $curlError
    );
}


$httpCode = curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);

curl_close($ch);


/*
|--------------------------------------------------------------------------
| CHECK API RESPONSE
|--------------------------------------------------------------------------
*/

$apiData = json_decode(
    $response,
    true
);


if ($httpCode < 200 || $httpCode >= 300) {

    $message = 'OpenAI API error.';

    if (
        isset($apiData['error']) &&
        isset($apiData['error']['message'])
    ) {

        $message =
            $apiData['error']['message'];
    }

    errorResponse($message);
}


/*
|--------------------------------------------------------------------------
| FIND OUTPUT TEXT
|--------------------------------------------------------------------------
*/

$outputText = '';

if (isset($apiData['output'])) {

    foreach ($apiData['output'] as $outputItem) {

        if (
            isset($outputItem['type']) &&
            $outputItem['type'] === 'message' &&
            isset($outputItem['content'])
        ) {

            foreach (
                $outputItem['content']
                as $contentItem
            ) {

                if (
                    isset($contentItem['type']) &&
                    $contentItem['type'] === 'output_text' &&
                    isset($contentItem['text'])
                ) {

                    $outputText .=
                        $contentItem['text'];
                }
            }
        }
    }
}


if ($outputText === '') {

    errorResponse(
        'OpenAI returned no vehicle research.'
    );
}


/*
|--------------------------------------------------------------------------
| DECODE RESEARCH JSON
|--------------------------------------------------------------------------
*/

$research = json_decode(
    $outputText,
    true
);


if (!is_array($research)) {

    errorResponse(
        'The vehicle research could not be decoded.'
    );
}


/*
|--------------------------------------------------------------------------
| FORMAT SPECIFICATIONS
|--------------------------------------------------------------------------
*/

$specificationText = '';

if (
    isset($research['specifications']) &&
    is_array($research['specifications'])
) {

    foreach (
        $research['specifications']
        as $spec
    ) {

        if (
            isset($spec['name']) &&
            isset($spec['value'])
        ) {

            $specificationText .=
                $spec['name'] .
                ': ' .
                $spec['value'] .
                "\n";
        }
    }
}


/*
|--------------------------------------------------------------------------
| FORMAT AWARDS
|--------------------------------------------------------------------------
*/

$awardText = '';

if (
    isset($research['awards']) &&
    is_array($research['awards'])
) {

    foreach (
        $research['awards']
        as $award
    ) {

        $awardText .=
            '- ' .
            $award .
            "\n";
    }
}


/*
|--------------------------------------------------------------------------
| START TRANSACTION
|--------------------------------------------------------------------------
*/

$con->begin_transaction();


try {


/*
|--------------------------------------------------------------------------
| REMOVE OLD RESEARCH
|--------------------------------------------------------------------------
|
| This prevents duplicates if the same vehicle is researched again.
|
*/


$stmt = $con->prepare(
    "DELETE FROM specifications
     WHERE spec_year = ?
     AND spec_make = ?
     AND spec_model = ?
     AND spec_trim = ?"
);

$stmt->bind_param(
    'ssss',
    $year,
    $make,
    $model,
    $trim
);

$stmt->execute();
$stmt->close();


$stmt = $con->prepare(
    "DELETE FROM options
     WHERE opt_year = ?
     AND opt_make = ?
     AND opt_model = ?
     AND opt_trim = ?"
);

$stmt->bind_param(
    'ssss',
    $year,
    $make,
    $model,
    $trim
);

$stmt->execute();
$stmt->close();


$stmt = $con->prepare(
    "DELETE FROM competition
     WHERE compet_year = ?
     AND compet_make = ?
     AND compet_model = ?
     AND compet_trim = ?"
);

$stmt->bind_param(
    'ssss',
    $year,
    $make,
    $model,
    $trim
);

$stmt->execute();
$stmt->close();


/*
|--------------------------------------------------------------------------
| SAVE SPECIFICATIONS
|--------------------------------------------------------------------------
*/

$stmt = $con->prepare(

    "INSERT INTO specifications
    (
        spec_year,
        spec_make,
        spec_model,
        spec_trim,
        spec_awards,
        spec_specif
    )
    VALUES (?, ?, ?, ?, ?, ?)"
);


$stmt->bind_param(
    'ssssss',
    $year,
    $make,
    $model,
    $trim,
    $awardText,
    $specificationText
);


$stmt->execute();

$stmt->close();

$specificationsSaved = 1;


/*
|--------------------------------------------------------------------------
| SAVE OPTIONS
|--------------------------------------------------------------------------
*/

$optionsSaved = 0;


if (
    isset($research['options']) &&
    is_array($research['options'])
) {

    $stmt = $con->prepare(

        "INSERT INTO options
        (
            opt_year,
            opt_make,
            opt_model,
            opt_trim,
            opt_code,
            opt_option
        )
        VALUES (?, ?, ?, ?, ?, ?)"
    );


    foreach (
        $research['options']
        as $option
    ) {

        if (
            !isset($option['option']) ||
            trim($option['option']) === ''
        ) {
            continue;
        }


        $optionCode =
            isset($option['code'])
            ? trim($option['code'])
            : '';


        $optionName =
            trim($option['option']);


        $stmt->bind_param(
            'ssssss',
            $year,
            $make,
            $model,
            $trim,
            $optionCode,
            $optionName
        );


        $stmt->execute();

        $optionsSaved++;
    }


    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| SAVE COMPETITION
|--------------------------------------------------------------------------
*/

$competitorsSaved = 0;


if (
    isset($research['competitors']) &&
    is_array($research['competitors'])
) {

    $stmt = $con->prepare(

        "INSERT INTO competition
        (
            compet_year,
            compet_make,
            compet_model,
            compet_trim,
            compet_vehicle,
            compet_selling,
            compet_specif
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );


    foreach (
        $research['competitors']
        as $competitor
    ) {

        if (
            !isset($competitor['vehicle']) ||
            trim($competitor['vehicle']) === ''
        ) {
            continue;
        }


        $competitorVehicle =
            trim($competitor['vehicle']);


        /*
        |--------------------------------------------------------------
        | SELLING POINTS
        |--------------------------------------------------------------
        */

        $sellingText = '';

        if (
            isset($competitor['selling_points']) &&
            is_array(
                $competitor['selling_points']
            )
        ) {

            foreach (
                $competitor['selling_points']
                as $sellingPoint
            ) {

                $sellingText .=
                    '- ' .
                    $sellingPoint .
                    "\n";
            }
        }


        /*
        |--------------------------------------------------------------
        | COMPETITOR SPECIFICATIONS
        |--------------------------------------------------------------
        */

        $competitorSpecText = '';

        if (
            isset($competitor['specifications']) &&
            is_array(
                $competitor['specifications']
            )
        ) {

            foreach (
                $competitor['specifications']
                as $spec
            ) {

                if (
                    isset($spec['name']) &&
                    isset($spec['value'])
                ) {

                    $competitorSpecText .=
                        $spec['name'] .
                        ': ' .
                        $spec['value'] .
                        "\n";
                }
            }
        }


        /*
        |--------------------------------------------------------------
        | INSERT COMPETITOR
        |--------------------------------------------------------------
        */

        $stmt->bind_param(
            'sssssss',
            $year,
            $make,
            $model,
            $trim,
            $competitorVehicle,
            $sellingText,
            $competitorSpecText
        );


        $stmt->execute();

        $competitorsSaved++;
    }


    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| COMMIT EVERYTHING
|--------------------------------------------------------------------------
*/

$con->commit();


/*
|--------------------------------------------------------------------------
| SUCCESS
|--------------------------------------------------------------------------
*/

echo json_encode(array(

    'success' => true,

    'vehicle' => $vehicleName,

    'specifications_saved' =>
        $specificationsSaved,

    'options_saved' =>
        $optionsSaved,

    'competitors_saved' =>
        $competitorsSaved

));


} catch (Exception $e) {


/*
|--------------------------------------------------------------------------
| SOMETHING FAILED
|--------------------------------------------------------------------------
*/

$con->rollback();


errorResponse(
    'Database error: ' .
    $e->getMessage()
);

}