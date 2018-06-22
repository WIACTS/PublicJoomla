# NoPassword – Free Muti Factor Authentication for Joomla
## How does NoPassword Multi-Factor Authentication solution work?
Through this method, the user is primarily authenticated based on a human factor (biometrics and behavioral factors) on their device (currently smartphone). After accepting the request on their phone the user is redirected to the admin portal.

## Login process
- Users with NoPassword account use their phones to login.
- Users without NoPassword account have the option to create one, but they can still login with their WordPress password.

## How to get access
Sign up at [www2.nopassword.com/free-trial/it/](www2.nopassword.com/free-trial/it/) and Try NoPassword for free. Then get your NoPassword Login API Key from Admin portal and start using the plugin.

## Installation instructions
1. Download the plugin files from GitHub as a ZIP file.
2. Upload the compressed file to your Joomla Dashboard under Extensions -> Manage -> Install menupoint, and activate the plugin.
4. Go to Extensions -> Templates -> Templates menupoint in your Joomla administrator panel
5. Choose your default Administrator template (usually Isis)
6. Open login.php and paste the following code after the line `defined('_JEXEC') or die;`
```
if(JPluginHelper::getPlugin('authentication', 'nopassword') && $_GET['passwordlogin'] != "true"){
  JPluginHelper::importPlugin( 'authentication', 'nopassword' );
  show_screen();
}
```

 **Make sure that you have 'shared session' enabled in your Joomla settings!**

 You can enable it under System -> Global Configuration -> System (tab)

## How to setup your plugin
1. Sign up for free trial at [www2.nopassword.com/free-trial/it/](www2.nopassword.com/free-trial/it/)
2. Get your ‘NoPassword Login’ API key from NoPassword admin portal under ‘Keys’ menupoint
3. Copy ‘NoPassword Login’ key to the plugin settings page
4. Save your settings and test your plugin
