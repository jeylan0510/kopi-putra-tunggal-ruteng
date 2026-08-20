<?php
/**
 * Helper Utility for Standardized REST API JSON Responses
 */

/**
 * Send JSON Response
 * 
 * @param bool $status Success boolean status
 * @param int $code HTTP response status code (e.g. 200, 201, 400, 404, 500)
 * @param string $message Descriptive message
 * @param mixed $data Payload data (array, object, or null)
 */
function sendResponse($status, $code, $message, $data = null) {
    http_response_code($code);
    header('Content-Type: application/json; charset=UTF-8');
    
    $response = [
        'status'  => $status,
        'code'    => $code,
        'message' => $message
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Parse Request Input (JSON body or form data)
 * 
 * @return array
 */
function getRequestData() {
    $rawInput = file_get_contents('php://input');
    $jsonData = json_decode($rawInput, true);

    if (is_array($jsonData)) {
        return array_merge($_POST, $jsonData);
    }

    return $_POST;
}
