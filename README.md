# NoPassword Joomla plugin
Authentication plugin for Joomla CMS using NoPassword API

## Creating production files
To build production files install Node libraries with
```
npm install
```
When complete, run
```
gulp build
```

The finished plugin will be `nopassword.zip`

## Compile SASS files
To compile SASS files into CSS, run
```
gulp css
```

## Installation
1. Go to Extensions -> Templates -> Templates menupoint in your Joomla administrator panel
2. Choose your default Administrator template (usually Isis)
3. Open login.php and paste the following code after the line `defined('_JEXEC') or die;`
```
if(JPluginHelper::getPlugin('authentication', 'nopassword') && $_GET['passwordlogin'] != "true"){
  JPluginHelper::importPlugin( 'authentication', 'nopassword' );
  show_screen();
}
```

 **Make sure that you have 'shared session' enabled in your Joomla settings!**

 You can do it under System -> Global Configuration -> System (tab)
