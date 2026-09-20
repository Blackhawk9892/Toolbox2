<?php

header("Content-Type: application/json");

//require_once "db.php";
require_once "openai.php";
require_once("includes/constants.php");
require("includes/connection.php");

try {

    //------------------------------------------------
    // GET VEHICLE FROM FORM
    //------------------------------------------------

    $year = (int)($_POST["year"] ?? 0);
    $make = trim($_POST["make"] ?? "");
    $model = trim($_POST["model"] ?? "");
    $trim = trim($_POST["trim"] ?? "");

    if (
        !$year ||
        $make === "" ||
        $model === "" ||
        $trim === ""
    ) {

        throw new Exception(
            "Year, make, model and trim are required."
        );
    }


    //------------------------------------------------
    // VEHICLE DESCRIPTION
    //------------------------------------------------

    $vehicle =
        $year . " " .
        $make . " " .
        $model . " " .
        $trim;


    //------------------------------------------------
    // FIRST CHATGPT REQUEST
    // SPECIFICATIONS / AWARDS / SAFETY
    //------------------------------------------------

    $prompt = <<<PROMPT

Research the following vehicle:

$vehicle

Use web research and reliable sources.

I need a detailed vehicle research report.

Research:

1. Complete vehicle specifications
2. Engine specifications
3. Horsepower
4. Torque
5. Transmission
6. Drivetrain
7. Fuel economy
8. Fuel tank
9. Dimensions
10. Wheelbase
11. Ground clearance
12. Curb weight
13. GVWR when applicable
14. Payload when applicable
15. Towing capacity when applicable
16. Passenger capacity
17. Cargo capacity
18. Wheels and tires
19. Brakes
20. Suspension
21. Steering
22. Exterior equipment
23. Interior equipment
24. Infotainment
25. Connectivity
26. Comfort and convenience
27. Driver assistance features
28. Standard safety equipment
29. Warranty information

Also research ALL identifiable:

- awards
- safety ratings
- third-party recognition

Look for information from organizations such as:

- NHTSA
- IIHS
- J.D. Power
- Kelley Blue Book
- Consumer Reports
- MotorTrend
- Car and Driver
- Edmunds
- recognized automotive publications

IMPORTANT:

Only report awards and ratings that you can
reasonably verify.

Do not invent awards.

Clearly distinguish between a rating for this
exact trim and a rating that applies to the
model or vehicle family.

Return ONLY valid JSON.

Do not include Markdown.

Use this exact structure:

{
    "awards": [
        {
            "organization": "",
            "award": "",
            "details": ""
        }
    ],

    "safety_ratings": [
        {
            "organization": "",
            "rating": "",
            "details": ""
        }
    ],

    "specifications": {
        "engine": "",
        "horsepower": "",
        "torque": "",
        "transmission": "",
        "drivetrain": "",
        "fuel_economy": "",
        "fuel_tank": "",
        "dimensions": "",
        "wheelbase": "",
        "ground_clearance": "",
        "curb_weight": "",
        "gvwr": "",
        "payload": "",
        "towing": "",
        "passenger_capacity": "",
        "cargo_capacity": "",
        "wheels_tires": "",
        "brakes": "",
        "suspension": "",
        "steering": "",
        "exterior": "",
        "interior": "",
        "infotainment": "",
        "connectivity": "",
        "comfort_convenience": "",
        "driver_assistance": "",
        "safety_equipment": "",
        "warranty": "",
        "additional_specifications": ""
    }
}

PROMPT;


    $researchResponse =
        askOpenAI($prompt);


    //------------------------------------------------
    // REMOVE POSSIBLE MARKDOWN CODE BLOCK
    //------------------------------------------------

    $researchResponse =
        str_replace(
            ["```json", "```"],
            "",
            $researchResponse
        );


    //------------------------------------------------
    // CONVERT JSON
    //------------------------------------------------

    $research =
        json_decode(
            trim($researchResponse),
            true
        );

    if (!is_array($research)) {

        throw new Exception(
            "Could not decode vehicle research."
        );
    }


    //------------------------------------------------
    // CREATE AWARDS FIELD
    //------------------------------------------------

    $awardData = [

        "awards" =>
            $research["awards"] ?? [],

        "safety_ratings" =>
            $research["safety_ratings"] ?? []
    ];

    $spec_awards =
        json_encode(
            $awardData,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_SLASHES
        );


    //------------------------------------------------
    // CREATE SPECIFICATION FIELD
    //------------------------------------------------

    $spec_specif =
        json_encode(
            $research["specifications"] ?? [],
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_SLASHES
        );


    //------------------------------------------------
    // INSERT / UPDATE SPECIFICATIONS
    //------------------------------------------------

    $sql = "
        INSERT INTO specifications
        (
            spec_year,
            spec_make,
            spec_model,
            spec_trim,
            spec_awards,
            spec_specif
        )

        VALUES (?, ?, ?, ?, ?, ?)

        ON DUPLICATE KEY UPDATE

            spec_awards =
                VALUES(spec_awards),

            spec_specif =
                VALUES(spec_specif),

            spec_created =
                CURRENT_TIMESTAMP
    ";


    $stmt = $con->prepare($sql);

    $stmt->bind_param(
        "isssss",
        $year,
        $make,
        $model,
        $trim,
        $spec_awards,
        $spec_specif
    );

    $stmt->execute();
    $stmt->close();


    //------------------------------------------------
    // SECOND CHATGPT REQUEST
    // OPTION CODES
    //------------------------------------------------

    $optionPrompt = <<<PROMPT

Research factory option codes for:

$vehicle

I need the factory option codes and descriptions
that were available for this exact model year,
make, model and trim.

Research sources such as:

- manufacturer ordering guides
- manufacturer brochures
- manufacturer price lists
- fleet ordering guides
- dealer ordering information
- other reliable automotive sources

IMPORTANT:

Do NOT invent an option code.

If an option is known but the factory order code
cannot be verified, leave the code blank.

Do not confuse:

- standard equipment
- option packages
- standalone options
- accessories

with each other.

Return ONLY valid JSON.

Do not include Markdown.

Use exactly:

{
    "options": [
        {
            "code": "",
            "description": ""
        }
    ]
}

PROMPT;


    $optionResponse =
        askOpenAI($optionPrompt);


    //------------------------------------------------
    // CLEAN RESPONSE
    //------------------------------------------------

    $optionResponse =
        str_replace(
            ["```json", "```"],
            "",
            $optionResponse
        );


    //------------------------------------------------
    // DECODE OPTIONS
    //------------------------------------------------

    $optionData =
        json_decode(
            trim($optionResponse),
            true
        );

    if (!is_array($optionData)) {

        throw new Exception(
            "Could not decode vehicle options."
        );
    }


    //------------------------------------------------
    // DELETE OLD OPTIONS
    //------------------------------------------------

    $delete =
        $con->prepare(
            "DELETE FROM options
             WHERE opt_year = ?
             AND opt_make = ?
             AND opt_model = ?
             AND opt_trim = ?"
        );

    $delete->bind_param(
        "isss",
        $year,
        $make,
        $model,
        $trim
    );

    $delete->execute();
    $delete->close();


    //------------------------------------------------
    // PREPARE OPTION INSERT
    //------------------------------------------------

    $insert =
        $con->prepare(
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


    //------------------------------------------------
    // INSERT EACH OPTION
    //------------------------------------------------

    $optionCount = 0;

    foreach (
        $optionData["options"] ?? []
        as $option
    ) {

        $code =
            trim(
                $option["code"] ?? ""
            );

        $description =
            trim(
                $option["description"] ?? ""
            );

        if ($description === "") {
            continue;
        }

        $insert->bind_param(
            "isssss",
            $year,
            $make,
            $model,
            $trim,
            $code,
            $description
        );

        $insert->execute();

        $optionCount++;
    }

    $insert->close();


    //------------------------------------------------
    // SUCCESS
    //------------------------------------------------

    echo json_encode([

        "success" => true,

        "message" =>
            "$vehicle researched successfully. " .
            "$optionCount options were loaded.",

        "vehicle" => $vehicle,

        "options_loaded" =>
            $optionCount
    ]);

}
catch (Throwable $e) {

    echo json_encode([

        "success" => false,

        "message" =>
            $e->getMessage()

    ]);
}

?>