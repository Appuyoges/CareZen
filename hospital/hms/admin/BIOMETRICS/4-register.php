<?php
// (A) INIT & CHECK
require "3-init.php";
if (file_exists($saveto)) { exit("User already registered"); }

switch ($_POST["phase"]) {
  // (B) REGISTRATION PART 1 - GET ARGUMENTS
  case "a":
    $args = $WebAuthn->getCreateArgs(
      \hex2bin($user["id"]), $user["name"], $user["display"],
      30, false, true
    );
    $_SESSION["challenge"] = ($WebAuthn->getChallenge())->getBinaryString();
    echo json_encode($args);
    break;

  // (C) REGISTRATION PART 2 - SAVE USER CREDENTIAL
  // should be saved in database, but we save into a file instead
  case "b":
    // (C1) VALIDATE & PROCESS
    try {
      $data = $WebAuthn->processCreate(
        base64_decode($_POST["client"]),
        base64_decode($_POST["attest"]),
        $_SESSION["challenge"],
        true, true, false
      );
    } catch (Exception $ex) { print_r($ex); exit; }

    // (C2) SAVE
    file_put_contents($saveto, serialize($data));
    echo "BIOMETRICS REGISTERED";
    break;
}