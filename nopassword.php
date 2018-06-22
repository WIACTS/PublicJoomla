<?php
// Licensed under the GPL v2
  defined('_JEXEC') or die();
  define('PLUGIN_URL', JUri::root().'/plugins/authentication/nopassword/');

  // Import library dependencies
  jimport('joomla.plugin.plugin');


  class plgAuthenticationNopassword extends JPlugin{

    function onUserAuthenticate( $credentials, $options, &$response ){
    // Let the default Joomla authentication plogin work
  }


      function onAjaxNopassword(){
          switch (addslashes($_GET['cmd'])) {
            case 'auth':
              include(dirname(__FILE__).'/inc/nopassword-auth.php');
              break;
            case 'password':
              include(dirname(__FILE__).'/inc/password-login.php');
              break;
            case 'create':
              include(dirname(__FILE__).'/inc/create-user.php');
              break;

            default:
              return 0;
              die();
              break;
          }
      }

  }

  function show_screen(){

    $plugin = JPluginHelper::getPlugin('authentication', 'nopassword');
    if ($plugin){
        $pluginParams = new JRegistry($plugin->params);
        $nopass_design = $pluginParams->get('nopass_design');
    }
    $uri = JUri::getInstance();
    $password_troubleshoot_value = JURI::root().'index.php/component/users/?view=reset';

    $createUserPopup = '';
    $createUserPopup .= '<div class="NPCreateUserWait" id="NPCreateUserWait"></div>';
    $createUserPopup .= '<div class="NPCreateUserError" id="NPCreateUserError">';
    $createUserPopup .= '<svg data-filename="warning-icon1.svg" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 29 26" style="enable-background: new 0 0 29 26;" xml:space="preserve"><g><path d="M14.5,8.7c-0.5,0-0.8,0.4-0.8,0.9v6c0,0.5,0.4,0.9,0.8,0.9c0.5,0,0.8-0.4,0.8-0.9v-6C15.3,9.1,15,8.7,14.5,8.7
  		L14.5,8.7z" style="fill: #F6506E;"></path><g><path d="M15,19.2c-0.4-0.4-0.8-0.4-1.2,0c-0.1,0.1-0.1,0.5-0.1,0.6c0,0.4,0,0.5,0.1,0.6s0.5,0.1,0.7,0.1
  			c0.1,0,0.5,0,0.4-0.1c0.1-0.1,0.4-0.5,0.4-0.6C15.2,19.6,15.2,19.5,15,19.2L15,19.2z" style="fill: #F6506E;"></path><path d="M28.4,19.5L18.2,2.2C17.5,0.9,16.1,0,14.5,0s-2.9,0.9-3.7,2.2L0.6,19.5c-0.8,1.4-0.8,3,0,4.3
  			C1.4,25.1,2.8,26,4.4,26h20.3c1.7,0,3-0.9,3.7-2.2C29.2,22.4,29.2,20.7,28.4,19.5L28.4,19.5z M26.9,23c-0.4,0.9-1.2,1.4-2.2,1.4
  			H4.5c-0.8,0-1.7-0.5-2.2-1.4c-0.6-0.7-0.6-1.7-0.1-2.6L12.3,3.2c0.4-0.9,1.2-1.4,2.2-1.4s1.9,0.5,2.4,1.4L27,20.5
  			C27.5,21.3,27.5,22.3,26.9,23L26.9,23z" style="fill: #F6506E;"></path></g></g></svg>';
    $createUserPopup .= '<h1>Something went wrong!</h1>';
    $createUserPopup .= '<p>We couldn\'t create you a NoPassword account. Please login using your password.</p>';
    $createUserPopup .= '<p class="npButton button-blue" onclick="popup.toggle()" ><a href="#"><span>Continue</span></a></p>';
    $createUserPopup .= '</div>';
    $createUserPopup .= '<div class="NPSuccess" id="NPSuccess">';
    $createUserPopup .= '<svg data-filename="check.svg" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background: new 0 0 40 40;" xml:space="preserve"><g><g><path d="M20,0C9,0,0,9,0,20s9,20,20,20s20-9,20-20S31,0,20,0z M20,37.4c-9.6,0-17.4-7.8-17.4-17.4
  			c0-9.6,7.8-17.4,17.4-17.4S37.4,10.4,37.4,20C37.4,29.6,29.6,37.4,20,37.4z" style="fill: #00ADD0;"></path><path d="M27.2,13.5L16.9,23.8l-4.2-4.2c-0.5-0.5-1.3-0.5-1.9,0c-0.5,0.5-0.5,1.3,0,1.9l5.1,5.1
  			c0.3,0.3,0.6,0.4,0.9,0.4c0.3,0,0.7-0.1,0.9-0.4c0,0,0,0,0,0l11.2-11.2c0.5-0.5,0.5-1.3,0-1.9C28.6,12.9,27.8,12.9,27.2,13.5z" style="fill: #00ADD0;"></path></g></g></svg>';
    $createUserPopup .= '<h1>Your NoPassword Account is ready</h1>';
    $createUserPopup .= '<p>Please check your email inbox and activate your NoPassword account. Next time you can use it to login.</p>';
    $createUserPopup .= '<p class="npButton button-blue" onclick="popup.toggle()" ><a href="#"><span>Continue</span></a></p>';
    $createUserPopup .= '</div>';
    $createUserPopup .= '<div class="NPCheckEmail" id="NPCheckEmail">';
    $createUserPopup .= '<svg data-filename="check.svg" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background: new 0 0 40 40;" xml:space="preserve"><g><g><path d="M20,0C9,0,0,9,0,20s9,20,20,20s20-9,20-20S31,0,20,0z M20,37.4c-9.6,0-17.4-7.8-17.4-17.4
  			c0-9.6,7.8-17.4,17.4-17.4S37.4,10.4,37.4,20C37.4,29.6,29.6,37.4,20,37.4z" style="fill: #00ADD0;"></path><path d="M27.2,13.5L16.9,23.8l-4.2-4.2c-0.5-0.5-1.3-0.5-1.9,0c-0.5,0.5-0.5,1.3,0,1.9l5.1,5.1
  			c0.3,0.3,0.6,0.4,0.9,0.4c0.3,0,0.7-0.1,0.9-0.4c0,0,0,0,0,0l11.2-11.2c0.5-0.5,0.5-1.3,0-1.9C28.6,12.9,27.8,12.9,27.2,13.5z" style="fill: #00ADD0;"></path></g></g></svg>';
    $createUserPopup .= '<h1>Please activate your NoPassword account</h1>';
    $createUserPopup .= '<p>Please check your email inbox and activate your NoPassword account. Next time you can use it to login.</p>';
    $createUserPopup .= '<p class="npButton button-blue" onclick="popup.toggle()" ><a href="#"><span>Continue</span></a></p>';
    $createUserPopup .= '</div>';
    $createUserPopup .= '<div class="npDialog">';
    $createUserPopup .= '<h1>Create a NoPassword account</h1>';
    $createUserPopup .= '<p class="createUserDescription">By creating a NoPassword account, you will be able to login to this site without using a password.</p>';
    $createUserPopup .= '<div class="line"></div>';
    $createUserPopup .= '<span class="inputSpan">Your email</span><br>';
    $createUserPopup .= '<input type="email" id="npNewUserEmail" class="text required" value="" readonly="true">';
    // $createUserPopup .= '<br><span class="inputSpan">First name</span><br>';
    // $createUserPopup .= '<input id="npNewFirstName" type="text"  class="text required" value="">';
    // $createUserPopup .= '<br><span class="inputSpan">Last name</span><br>';
    // $createUserPopup .= '<input id="npNewLastName" type="text"  class="text required" value="">';
    // $createUserPopup .= '<div class="checkboxWrapper"><input type="checkbox" id="dontAskAgain" value="true"><span>Don\'t ask me again</span></div>';
    $createUserPopup .= '<div class="buttons cf">';
    $createUserPopup .= '<p class="npButton button-grey" id="closePopup"><a href="#"/><span>Cancel</span></a></p>';
    $createUserPopup .= '<p class="npButton button-blue" id="npCreateUserButton" ><a onclick="createuser()" href="#"><span>Create account</span></a></p>';
    $createUserPopup .= '</div>';
    $createUserPopup .= '</div>';
    $createUserPopup .= '</div>';

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    	<jdoc:include type="head" />
    </head>
    <body>
    <?php
    $document = JFactory::getDocument();
    $document->addStyleSheet('/plugins/authentication/nopassword/css/login_style.css');
    $document->addScript('/plugins/authentication/nopassword/js/circle-progress.js');
    $document->addScript('/plugins/authentication/nopassword/js/jquery-cookie.js');
    $document->addScript('/plugins/authentication/nopassword/js/popup.js');
    $document->addScript('/plugins/authentication/nopassword/js/script.js');

    if ($nopass_design == '0'){
      $document->addScript('/plugins/authentication/nopassword/js/compact.js');

      $loginScreen = '';
      $loginScreen .= '<div id="createUserPopup">';
      $loginScreen .= $createUserPopup;
      $loginScreen .= '<div class="compact"><div id="npError">a</div>';
      $loginScreen .= '<div class="loginScreenCompactWrapper">';
      $loginScreen .= '<div id="avaiting" class="compactAwaiting">';
      $loginScreen .= '<div class="phoneWrapper"><div class="phone">';
      $loginScreen .= '<img src="'.PLUGIN_URL.'assets/phone.png" class="phoneWrapper"/>';
      $loginScreen .= '<img src="'.PLUGIN_URL.'assets/phone_bg.png" class="phoneBg"/>';
      $loginScreen .= '<span class="progress"></span>';
      $loginScreen .= '<span class="timer"><span class="inner"></span></span>';
      $loginScreen .= '</div></div></div>';
      $loginScreen .= '<h1 id="npTitle">Login with NoPassword</h1>';
      $loginScreen .= '<input id="npMail" type="email" name="login_user_name" placeholder="Enter your email" class="text required" value="'.$_COOKIE['NPuserLastEmail'].'">';
      $loginScreen .= '<p class="npButton button-blue login"><a onclick="loginclick()" id="npLoginButton" href="#login"><span>Login</span></a></p>';
      $loginScreen .= '<p class="hint nphint"><a href="https://nopassword.com/Login/LoginProblem.aspx" target="_blank"><span>Trouble signing in?</span></a></p>';
      $loginScreen .= '<p class="hint passwordhint"><a href="'.$password_troubleshoot_value.'" target="_blank"><span>Trouble signing in?</span></a></p>';
      $loginScreen .= '<p class="hint" id="createUser"><a onclick="popup.toggle()" href="#"><span>Want to login without password?</span></a></p>';
      $loginScreen .= '</div></div>';
    }
    else{
      $document->addScript('/plugins/authentication/nopassword/js/fullscreen.js');

      $loginScreen = '';
      $loginScreen .= '<div id="createUserPopup" class="fullscreen">';
      $loginScreen .= $createUserPopup;
      $loginScreen .= '<div class="loginScreenPopUpWrapper login-screen-popup-opened">';
      $loginScreen .= '<div class="lsFirst"></div>';
      $loginScreen .= '<div class="lsAwaiting">';
      $loginScreen .= '<img src="'.PLUGIN_URL.'assets/check_icon.png" width="52" class="check"/>';
      $loginScreen .= '<p class="npTitle">Confirm the request on NoPassword App</p>';
      $loginScreen .= '<div class="phoneWrapper"><div class="phone">';
      $loginScreen .= '<img src="'.PLUGIN_URL.'assets/phone.png" class="phoneWrapper"/>';
      $loginScreen .= '<img src="'.PLUGIN_URL.'assets/phone_bg.png" class="phoneBg"/>';
      $loginScreen .= '<span class="progress"></span>';
      $loginScreen .= '<span class="timer"><span class="inner"></span></span>';
      $loginScreen .= '</div></div>';
      $loginScreen .= '<p class="npButton button-carnation button-cancel">';
      $loginScreen .= '<a href="#cancel">';
      $loginScreen .= '<span>Cancel</span>';
      $loginScreen .= '</a>';
      $loginScreen .= '</p>';
      $loginScreen .= '</div>';
      $loginScreen .= '<div class="rsWrapper"><div class="rsInner">';
      $loginScreen .= '<div class="lScreenPopupWrapper">';
      $loginScreen .= '<img src="'.PLUGIN_URL.'assets/logo-blue.svg" alt="NoPassword">';
      $loginScreen .= '<div class="lScreenPopup cf">';
      $loginScreen .= '<p class="npTitle" id="npTitle">Login with NoPassword account</p>';
      $loginScreen .= '<div class="inputWrapper">';
      $loginScreen .= '<input id="npMail" type="email" name="login_user_name" class="text required"  value="'.$_COOKIE['NPuserLastEmail'].'">';
      $loginScreen .= '</div>';
      $loginScreen .= '<div class="buttons cf">';
      $loginScreen .= '<p class="npButton button-grey back"><a href=".."/><span>Back</span></a></p>';
      $loginScreen .= '<p class="npButton button-blue login"><a onclick="loginclick()" id="npLoginButton" href="#login"><span>Login</span></a></p>';
      $loginScreen .= '</div>';
      $loginScreen .= '<p class="hint nphint"><a href="https://nopassword.com/Login/LoginProblem.aspx" target="_blank"><span>Have trouble logging in?</span></a></p>';
      $loginScreen .= '<p class="hint passwordhint"><a href="'.$password_troubleshoot_value.'" target="_blank"><span>Have trouble logging in?</span></a></p>';
      $loginScreen .= '<p class="hint" id="createUser"><a onclick="popup.toggle()" href="#"><span>Want to login without password?</span></a></p>';
      $loginScreen .= '<p id="npError" class="error"></p>';
      $loginScreen .= '</div>';
      $loginScreen .= '</div>';
      $loginScreen .= '</div></div>';
      $loginScreen .= '</div>';
    }

    echo $loginScreen;

    ?>
      </body>
      </html>
    <?php
  }
