<?php

/**
 * this file uses the enrollment class to
 * enroll users
 * @authors Dahir Muhammad Dahir (dahirmuhammad3@gmail.com)
 * @date    2020-04-18 14:28:39
 */

require_once("../coreComponents/basicRequirements.php");

if (!empty($_POST["data"])) {
    $user_data = json_decode($_POST["data"]);

    $index_finger_string_array = $user_data->index_finger;
    // $middle_finger_string_array = $user_data->middle_finger;
    $client = new FingerprintController();
    $enrolled_index_finger = $client->enroll_fingerprint($index_finger_string_array);
    // $enrolled_middle_finger = enroll_fingerprint($middle_finger_string_array);
    error_log(json_encode(["waduc" => $enrolled_index_finger,]));
    if ($enrolled_index_finger !== "enrollment failed") {
        # todo: return the enrolled fmds instead
        $output = ["enrolled_index_finger" => $enrolled_index_finger];
        echo json_encode($output);
    } else {
        echo json_encode("enrollment failed!");
    }
} else {
    echo json_encode("error! no data provided in post request");
}
