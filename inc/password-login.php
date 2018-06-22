<?php
// Licensed under the GPL v2
/* Login the user using his email and password. */
defined('_JEXEC') or die();
include(dirname(__FILE__).'/functions.php');

if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['password'])){ // Login user using email and password
  $response = [];

  $email = $_POST['email'];
  $password = $_POST['password'];

  $userid = getUserId($email);

  if( $userid ){ // Check if user exists in WP

    $user = JFactory::getUser($userid);
    $passwordMatch = JUserHelper::verifyPassword($password, $user->password, $user->id);

    if( !$passwordMatch ) {
      $response['Status'] = "Fail";
      $response['Message'] = "Wrong password";
    }
    else {
      loginUser($user->id);
      $response['Status'] = "Success";
      $response['Message'] = "Succesfull login";
    }
  }
  else{
    $response['Status'] = 'Fail';
    $response['Message'] = 'User does not exists';
  }
}
else{
  $response['Status'] = 'Fail';
  $response['Message'] = 'Invalid request';
}

echo json_encode($response);
die();
?>
