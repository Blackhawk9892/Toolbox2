<?php

//require_once "config.php";
require_once("includes/constants.php");
require("includes/connection.php");

function askOpenAI($prompt)
{
    $url = "https://api.openai.com/v1/responses";


    /*
     * COST CONTROL
     *
     * gpt-5.6-luna is being used because this
     * application performs a large amount of
     * routine vehicle research.
     *
     * It supports web search but costs much less
     * than gpt-5.6 Sol.
     */

    $data = [

        "model" => "gpt-5.6-luna",

        /*
         * Low reasoning is sufficient for
         * collecting and organizing vehicle data.
         */

        "reasoning" => [
            "effort" => "low"
        ],

        /*
         * Allow the model to search the Internet.
         */

        "tools" => [
            [
                "type" => "web_search"
            ]
        ],

        /*
         * Keep the answer reasonably compact.
         * JSON does not need lengthy explanations.
         */

        "max_output_tokens" => 12000,

        "input" => $prompt
    ];


    //------------------------------------------------
    // SEND REQUEST
    //------------------------------------------------

    $ch = curl_init($url);

    curl_setopt_array($ch, [

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POST => true,

        CURLOPT_HTTPHEADER => [

            "Content-Type: application/json",

            "Authorization: Bearer " .
                OPENAI_API_KEY
        ],

        CURLOPT_POSTFIELDS =>
            json_encode($data),

        CURLOPT_TIMEOUT => 240
    ]);


    //------------------------------------------------
    // GET RESPONSE
    //------------------------------------------------

    $response = curl_exec($ch);


    if ($response === false) {

        $error = curl_error($ch);

        curl_close($ch);

        throw new Exception(
            "OpenAI connection error: " .
            $error
        );
    }


    $httpCode =
        curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );


    curl_close($ch);


    //------------------------------------------------
    // CHECK API ERROR
    //------------------------------------------------

    if (
        $httpCode < 200 ||
        $httpCode >= 300
    ) {

        throw new Exception(
            "OpenAI API error: " .
            $response
        );
    }


    //------------------------------------------------
    // DECODE RESPONSE
    //------------------------------------------------

    $json =
        json_decode(
            $response,
            true
        );


    if (!$json) {

        throw new Exception(
            "Invalid response from OpenAI."
        );
    }


    //------------------------------------------------
    // FIND OUTPUT TEXT
    //------------------------------------------------

    foreach (
        $json["output"] ?? []
        as $output
    ) {

        foreach (
            $output["content"] ?? []
            as $content
        ) {

            if (
                ($content["type"] ?? "")
                === "output_text"
            ) {

                return $content["text"];
            }
        }
    }


    throw new Exception(
        "No research information was returned."
    );
}

?>