<?php
// (A) INIT & CHECK
require "3-init.php";
if (!file_exists($saveto)) { 
  exit("User is not registered");
}
$saved = unserialize(file_get_contents("ADMIN.txt"));

// (B) HANDLE CORS PREFLIGHT REQUESTS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Allow-Headers: Content-Type");
  header("Access-Control-Max-Age: 86400");
  exit();
}

// (C) HANDLE MAIN REQUEST
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

switch ($_POST["phase"]) {
  // (D) VALIDATION PART 1 - GET ARGUMENTS
  case "a":
    $args = $WebAuthn->getGetArgs([$saved->credentialId], 30);
    $_SESSION["challenge"] = ($WebAuthn->getChallenge())->getBinaryString();
    echo json_encode($args);
    break;

  // (E) VALIDATION PART 2 - CHECKS & PROCESS
  case "b":
    $id = base64_decode($_POST["id"]);
    if ($saved->credentialId !== $id) { 
      exit(json_encode(["error" => "Invalid credentials"]));
    }
    try {
      $WebAuthn->processGet(
        base64_decode($_POST["client"]),
        base64_decode($_POST["auth"]),
        base64_decode($_POST["sig"]),
        $saved->credentialPublicKey,
        $_SESSION["challenge"]
      );
      
      // Redirect to YouTube
      header("Location: http://localhost/HMS/hospital/hms/admin/dashboard.php");
      exit;
      
    } catch (Exception $ex) { 
      header("Location: https://localhost/?error=validation_failed");
      exit;
    }
    break;
}
?>
