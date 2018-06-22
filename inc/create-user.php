<?php
// Licensed under the GPL v2
/* Create a NoPassword account if the user already has a WordPress Account */

defined('_JEXEC') or die();
include(dirname(__FILE__).'/functions.php');

  if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $response = [];

    $plugin = JPluginHelper::getPlugin('authentication', 'nopassword');
    if ($plugin){
        $pluginParams = new JRegistry($plugin->params);
        $APIKey = $pluginParams->get('nopass_api_key');
        $endpoint = $pluginParams->get('nopass_api_endpoint');
    }
    $email = $_POST['email'];

    $userid = getUserId($email);
    $user = JFactory::getUser($userid);

    $firstname = explode("@", $email)[0];
    $lastname = $firstname;

    $data_array =  array(
      "APIKey" => $APIKey,
      "Username" => $email,
      "IPAddress" => NOPASS_getClientIP(),
      "FirstName" => $firstname,
      "LastName" => $lastname,
      "BrowserId" => NOPASS_getBrowserID(),
      "DeviceName" => NOPASS_getBrowser() . ", " . NOPASS_getOS()
    );

    if($userid){// Check if user exists in WP

      $result = NOPASS_callAPI($endpoint.'/auth/login', json_encode($data_array));
      $resultArr = json_decode($result, true);
      if($resultArr['Succeeded'] && $resultArr['Value']['AuthStatus'] = "UnpairedUser"){
          $response['Status'] = 'Success';
          $response['Message'] = 'Your NoPassword account is ready to use';
      }
      else{
        $response['Status'] = 'Fail';
        $response['Message'] = 'Can\'t create NoPassword account';
      }

    }
    else{
      $response['Status'] = 'Fail';
      $response['Message'] = 'User does not exists in WordPress';
    }
  }
  else{
    $response['Status'] = 'Fail';
    $response['Message'] = 'Invalid request';
  }

  echo json_encode($response);
  die();

?>
