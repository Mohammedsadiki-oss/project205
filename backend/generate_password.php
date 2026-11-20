<?php

$newPassword = "NfcAdmin2026";

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

echo $hashedPassword;

?>