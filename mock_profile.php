<?php
session_start();
$_SESSION['user_id'] = 1;

$_POST['action'] = 'update_profile';
$_POST['username'] = 'testuser123';
$_FILES = []; // mock no file upload

ob_start();
require_once __DIR__ . '/user/action_profile.php';
$output = ob_get_clean();

file_put_contents('profile_test_output.txt', $output);
echo "Done";
