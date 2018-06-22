<?php
// Licensed under the GPL v2
/*
Authenticate the user using his NoPassword account and log him in to WP.
If the user does not have NP account, then he can create one.
*/

  defined('_JEXEC') or die();
  include(dirname(__FILE__).'/functions.php');

  if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ // Login user just by email
    $response = [];
    // Get the plugin settings
    $plugin = JPluginHelper::getPlugin('authentication', 'nopassword');
    if ($plugin){
        $pluginParams = new JRegistry($plugin->params);
        $APIKey = $pluginParams->get('nopass_api_key');
        $endpoint = $pluginParams->get('nopass_api_endpoint');
        $create_user = $pluginParams->get('nopass_new_user');
        $default_role_value = $pluginParams->get('nopass_user_role');
    }

    $email = addslashes($_POST['email']);
    $userStatus = NOPASS_getUserStatus($email, $APIKey, $endpoint);

    // Check if the user has a Joomla account
    $userid = getUserId($email);
    $userResult = $userid;
    // echo "|".$userResult."|";
    // echo $userStatus;
    // loginUser($userid);
    // echo '{"Status": "Success", "NewUser": false}';
    // die();

    if($userStatus == 'UnpairedUser'){
      // We call the API just to send him an activation email.
      $data_array =  array(
        "APIKey" => $APIKey,
        "Username" => $email,
        "IPAddress" => NOPASS_getClientIP(),
        "DeviceName" => NOPASS_getBrowser() . ", " . NOPASS_getOS(),
        "BrowserId" => $browserID
      );
      $result = NOPASS_callAPI($endpoint.'/auth/login', json_encode($data_array));
      $response['Status'] = 'Fail';
      $response['Message'] = 'User is not paired';

      if ($userResult) { // If the user doesn't exists in database
        $response['Method'] = 'DefaultLogin';
        $response['Value'] = 'CheckEmail';
      }

    }
    elseif($userStatus == 'InvalidUser') {
      if ($userResult) {
        $response['Status'] = 'Fail';
        $response['Method'] = 'DefaultLogin';
        $response['Value'] = 'CreateAccount';
        $response['Message'] = 'The user does not have a NoPassword account';
      }
      else{
        $response['Status'] = 'Fail';
        $response['Message'] = 'Invalid User';
      }
    }
    elseif($userStatus == 'Success'){
      $data_array =  array(
        "APIKey" => $APIKey,
        "Username" => $email,
        "IPAddress" => NOPASS_getClientIP(),
        "DeviceName" => NOPASS_getBrowser() . ", " . NOPASS_getOS(),
        "BrowserId" => $browserID
      );
      $result = NOPASS_callAPI($endpoint.'/auth/login', json_encode($data_array));
      $resultArr = json_decode($result, true);

      if($resultArr['Value']['AuthStatus'] == 'Success'){ // If the user succesfully authenticated himself on app

        if ($userResult) { // If the user has a Joomla account

          loginUser($userid);

          $response['Status'] = 'Success';
          $response['NewUser'] = false;
        }
        elseif($create_user == '1'){ // If user doesn't have a WP account, but we create him a new one
          $udata = array(
            "name"=>$email,
            "username"=>$email,
            "password"=>"",
            "password2"=>"",
            "email"=>$email,
            "block"=>0,
            "groups"=> array(empty($default_role_value) ? 7 : $default_role_value)
          );

          $user = new JUser;

          //Write to database
          if(!$user->bind($udata)) {
            $response['Status'] = "Fail";
            $response['Message'] = $user->getError();
          }
          if (!$user->save()) {
            $response['Status'] = "Fail";
            $response['Message'] = $user->getError();
          }

          $new_user_id = $user->id;
          // Login the newly created user and redirect to Edit user page.
          loginUser($new_user_id);

          $response['Status'] = "Success";
          $response['NewUser'] = true;
        }
        else{ // We can't create a new account for the user
          $response['Status'] = "Fail";
          $response['Message'] = 'User does not exists';
        }
      }
      elseif($resultArr['Value']['AuthStatus'] == 'InvalidUser'){
      if ($userResult){
          $response['Status'] = 'Fail';
          $response['Method'] = 'DefaultLogin';
          $response['Value'] = 'CreateAccount';
          $response['Message'] = 'The user does not have a NoPassword account';
        }
        else{
          $response['Status'] = 'Fail';
          $response['Message'] = 'Invalid User';
        }
      }
      elseif($resultArr['Value']['AuthStatus'] == 'UnpairedUser'){
        $response['Status'] = 'Fail';
        $response['Method'] = 'DefaultLogin';
        $response['Value'] = 'CheckEmail';
        $response['Message'] = 'User is not paired';
      }
      elseif($resultArr['Value']['AuthStatus'] == "LockedUser"){
        $response['Status'] = "Fail";
        $response['Message'] = "Invalid User";
      }
      elseif($resultArr['Value']['AuthStatus'] == "Alert"){
        $response['Status'] = "Fail";
        $response['Message'] = "Access is denied. Please try again!";
      }
      elseif($resultArr['Value']['AuthStatus'] == "Denied"){
        $response['Status'] = "Fail";
        $response['Message'] = "Access is denied. Please try again!";
      }
      elseif($resultArr['Value']['AuthStatus'] == "Denied by Geofencing"){
        $response['Status'] = "Fail";
        $response['Message'] = "Access is denied by Geofencing";
      }
      elseif($resultArr['Value']['AuthStatus'] == "Denied by Policy"){
        $response['Status'] = "Fail";
        $response['Message'] = "Access is denied by Policy";
      }
      elseif($resultArr['Value']['AuthStatus'] == "No Location"){
        $response['Status'] = "Fail";
        $response['Message'] = "Access is denied. Please try again!";
      }
      elseif($resultArr['Value']['AuthStatus'] == "NoResponse"){
        $response['Status'] = "Fail";
        $response['Message'] = "We didn't get your confirmation. Try again!";
      }
      elseif($resultArr['Value']['AuthStatus'] == "LogError"){
        $response['Status'] = "Fail";
        $response['Message'] = "Access is denied. Please try again!";
      }
      elseif($resultArr['Value']['AuthStatus'] == "BadRequestError"){
        $response['Status'] = "Fail";
        $response['Message'] = "Access is denied. Please try again!";
      }
      elseif($resultArr['Message']){
        $response['Status'] = "Fail";
        $response['Message'] = $resultArr['Message'];
      }
      else{
        $response['Status'] = "Fail";
        $response['Error'] = "Unknown error";
      }
    }
    else{
      $response['Status'] = 'Fail';
      $response['Method'] = 'DefaultLogin';
      $response['Message'] = 'Unknown error';
    }
  }
  else {
    $response['Status'] = "Fail";
    $response['Error'] = "Permission denied";
  }

  echo json_encode($response);

  die();

?>
