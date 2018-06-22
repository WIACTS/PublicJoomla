<?php
// Licensed under the GPL v2
/*
Functions that are used by all files.
*/
defined('_JEXEC') or die();

function NOPASS_getOS() { // Returns browser type

  $os_platform    =   "Unknown OS Platform";

  $os_array       =   array(
    '/windows nt 10/i'     =>  'Windows 10',
    '/windows nt 6.3/i'     =>  'Windows 8.1',
    '/windows nt 6.2/i'     =>  'Windows 8',
    '/windows nt 6.1/i'     =>  'Windows 7',
    '/windows nt 6.0/i'     =>  'Windows Vista',
    '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
    '/windows nt 5.1/i'     =>  'Windows XP',
    '/windows xp/i'         =>  'Windows XP',
    '/windows nt 5.0/i'     =>  'Windows 2000',
    '/windows me/i'         =>  'Windows ME',
    '/win98/i'              =>  'Windows 98',
    '/win95/i'              =>  'Windows 95',
    '/win16/i'              =>  'Windows 3.11',
    '/macintosh|mac os x/i' =>  'Mac OS X',
    '/mac_powerpc/i'        =>  'Mac OS 9',
    '/linux/i'              =>  'Linux',
    '/ubuntu/i'             =>  'Ubuntu',
    '/iphone/i'             =>  'iPhone',
    '/ipod/i'               =>  'iPod',
    '/ipad/i'               =>  'iPad',
    '/android/i'            =>  'Android',
    '/blackberry/i'         =>  'BlackBerry',
    '/webos/i'              =>  'Mobile'
  );

  foreach ($os_array as $regex => $value) {

    if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
      $os_platform    =   $value;
    }

  }

  return $os_platform;

}

function NOPASS_getBrowser() { // Returns OS type

  $browser        =   "Unknown Browser";

  $browser_array  =   array(
    '/msie/i'       =>  'Internet Explorer',
    '/firefox/i'    =>  'Firefox',
    '/safari/i'     =>  'Safari',
    '/chrome/i'     =>  'Chrome',
    '/edge/i'       =>  'Edge',
    '/opera/i'      =>  'Opera',
    '/netscape/i'   =>  'Netscape',
    '/maxthon/i'    =>  'Maxthon',
    '/konqueror/i'  =>  'Konqueror',
    '/mobile/i'     =>  'Handheld Browser'
  );

  foreach ($browser_array as $regex => $value) {

    if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
      $browser    =   $value;
    }

  }

  return $browser;

}

function NOPASS_getClientIP(){ // Returns the client IP address
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $client_ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $client_ip = $_SERVER['REMOTE_ADDR'];
  }
  // if($client_ip == "127.0.0.1"){
  // 	$client_ip = "194.42.112.164";
  // }

  if( strpos($client_ip, ',') !== false ) {
     $client_ip = explode(",", $client_ip);
     return $client_ip[0];
 }

  return $client_ip;
}

function NOPASS_generateBrowserID($length = 32) { // Returns a random 32 char long browser ID
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-*/#';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function NOPASS_getBrowserID(){ // Returns the unique browser ID. If not exists create a new one
  $browserID = '';

  if($_COOKIE['NPBrowserID']){
    $browserID = $_COOKIE['NPBrowserID'];
  }
  else{
    $browserID = NOPASS_generateBrowserID(32);
    $response["BrowserId"] = $browserID;
  }

  return $browserID;
}

function NOPASS_getUserStatus($email, $APIKey, $endpoint){ // Returns the user status (InvalidUser, UnpairedUser, Success)
  /*
  Get the NP account Status. It can be:
  - UnpairedUser -> Password login, and gets activation E-mail
  - InvalidUser -> Show him the option to create an account
  - Success -> Must use NP to log-in.
  */
  $data_array =  array(
    "APIKey" => $APIKey,
    "Username" => $email,
  );

  $result = NOPASS_callAPI($endpoint.'/Auth/CheckUser', json_encode($data_array));
  $resultArr = json_decode($result, true);
  return $resultArr['Value']['AuthStatus'];

}

function NOPASS_callAPI($url, $data){ //Returns API response in JSON format
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_POST, 1);
  if ($data)
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

  // Headers:
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'content-type: application/json',
    'Content-Length: ' . strlen($data)
  ));

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLINFO_HEADER_OUT, true);

  $result = curl_exec($curl);
  if(!$result){die("Connection Failure");}
  curl_close($curl);
  return $result;

}

function loginUser($userid){
    $db = JFactory::getDbo();
    $q = "SELECT * FROM `#__users` WHERE id = ".$userid;
    $user = $db->setQuery($q)->loadAssoc();
    JPluginHelper::importPlugin( 'user' );
    $dispatcher = JDispatcher::getInstance();

    // Initiate log in
    $options = array('action' => 'core.login.site', 'remember' => false);
    $results = $dispatcher->trigger('onUserLogin', array($user, $options));
    //redirect logged in user
}

function getUserId($userEmail){
    $db = JFactory::getDbo();
    $query = $db->getQuery(true)
        ->select($db->quoteName('id'))
        ->from($db->quoteName('#__users'))
        ->where($db->quoteName('email') . ' = ' . $db->quote($userEmail));
    $db->setQuery($query, 0, 1);

    return $db->loadResult();
}

function checkJoomlaUserStatus($useremail){
  $db = JFactory::getDbo();
  $query = $db->getQuery(true);
  $query = 'SELECT id FROM #__users WHERE email = ' . $db->Quote($userEmail);
  $db->setQuery($query, 0, 1);
  $check = $db->loadResult();

  if($check){
   return true;
  }
  return false;
}
?>
