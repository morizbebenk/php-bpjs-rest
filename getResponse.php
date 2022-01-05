<?php

require_once "vendor/autoload.php";
date_default_timezone_set("UTC");

function callAPI($header, $method, $url, $data) {
    $curl = curl_init();
    switch ($method){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $result = curl_exec($curl);
    if(!$result){
        return 0;
    }

    curl_close($curl);
    return $result;
}

function decompress($string) {
    return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
}

function stringDecrypt($key, $string) {
    $encrypt_method = "AES-256-CBC";
    $key_hash = hex2bin(hash("sha256", $key));
    $iv = substr(hex2bin(hash("sha256", $key)), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
    return json_decode(decompress($output));
}

if(!isset($_GET["jenisAPI"]) || 
    !isset($_GET["consid"]) || 
    !isset($_GET["secret"]) || 
    !isset($_GET["user_key"]) || 
    !isset($_GET["username"]) || 
    !isset($_GET["password"]) || 
    !isset($_GET["method"]) ||
    !isset($_GET["url"]) || 
    !isset($_GET["withParam"]) || 
    !isset($_GET["params"])) {
    echo "Dibuat Oleh <a href='https://github.com/morizbebenk' target='_blank'>Moriz</a>";
    die("");
} else {
    $tStamp = strval(time() - strtotime("1970-01-01 00:00:00"));

    $jenisAPI = $_GET["jenisAPI"];
    $consid = $_GET["consid"];
    $secret = $_GET["secret"];
    $user_key = $_GET["user_key"];
    $is_encrypt = $_GET["is_encrypt"];

    $username = $_GET["username"];
    $password = $_GET["password"];
    $kdAplikasi = "095";

    $method = $_GET["method"];
    $url = $_GET["url"];
    $withParam = $_GET["withParam"];
    $params = $_GET["params"];

    if ($withParam == 0) {
        $params = null;
    }

    $data = $consid . "&" . $tStamp;
    $signature = hash_hmac("sha256", $data, $secret, true);
    $encodedSignature = base64_encode($signature);
    $encodedAuthorization = base64_encode($username . ":" . $password . ":" . $kdAplikasi);
    $encryption_mode = false;

    if ($jenisAPI == "vclaim-v1" || $jenisAPI == "vclaim-dev-v1") {
        $headers = array(
            "X-cons-id:" . $consid,
            "X-timestamp: " . $tStamp,
            "X-signature: " . $encodedSignature,
            "Content-Type:Application/x-www-form-urlencoded"
        );

    } else if ($jenisAPI == "vclaim-v2" || $jenisAPI == "vclaim-dev-v2" || $jenisAPI == "antrean-rs" || $jenisAPI == "antrean-rs-dev") {
        $headers = array(
            "X-cons-id:" . $consid,
            "X-timestamp: " . $tStamp,
            "X-signature: " . $encodedSignature,
            "user_key: " . $user_key,
            "Content-Type:Application/x-www-form-urlencoded"
        );
        
        $encryption_mode = true;

    } else if ($jenisAPI == "pcare" || $jenisAPI == "pcare-dev") {
        $headers = array(
            "X-cons-id:" . $consid,
            "X-timestamp: " . $tStamp,
            "X-signature: " . $encodedSignature,
            "X-authorization: Basic " . $encodedAuthorization,
            "Content-Type:Application/x-www-form-urlencoded"
        );
    }

    $response = callAPI($headers, $method, $url, $params);

    if($encryption_mode == false) {
        echo $response;

    } else {
        if ($is_encrypt == 0) {
            $response_before = $response;
            $response = json_decode($response, true);

            if ($response == null) {
                $res = array(
                    "metaData" => array(
                        "code" => 401,
                        "message" => $response_before,
                    ),
                    "response" => null
                );

                echo json_encode($res);

            } else {
                echo $response_before;
            }

        } else {
            $keys = $consid . $secret . $tStamp;
            $response_before = $response;
            $response = json_decode($response, true);

            if($response == null) {
                $res = array(
                    "metaData" => array(
                        "code" => 401,
                        "message" => $response_before,
                    ),
                    "response" => null
                );

                echo json_encode($res);

            } else {
                $metaData = "metaData";
                if (!array_key_exists($metaData, $response)) {
                    $metaData = "metadata";
                }

                $code = "code";
                if (!array_key_exists($code, $response[$metaData])) {
                    $code = "Code";
                }

                if (!array_key_exists("response", $response)) {
                    $res = array(
                        $metaData . "" => array(
                            "code" => $response[$metaData][$code],
                            "message" => $response[$metaData]["message"],
                        )
                    );

                } else {
                    $res = array(
                        $metaData . "" => array(
                            "code" => $response[$metaData][$code],
                            "message" => $response[$metaData]["message"],
                        ),
                        "response" => stringDecrypt($keys, $response["response"])
                    );
                }

                echo json_encode($res);
            }
        }
    }
}