<?php
// (A) RELYING PARTY - CHANGE TO YOUR OWN!
$rp = [
  "name" => "CURIOUS FREAKS",
  "id" => "localhost"
];

// (B) DUMMY USER
$user = [
  "id" => "12345678",
  "name" => "curiousfreaks@gmail.com",
  "display" => "CuriousFreaks"
];
$saveto = "ADMIN.txt"; 

// (C) START SESSION & LOAD WEBAUTHN LIBRARY
session_start();
require "vendor/autoload.php";
$WebAuthn = new lbuchs\WebAuthn\WebAuthn($rp["name"], $rp["id"]);