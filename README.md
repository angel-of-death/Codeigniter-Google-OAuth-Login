# Login with Google for Codeigniter
Library for Codeigniter to authenticate users through Google OAuth 2.0 and get user profile info

##Installation
1. Download the files.
2. Install google apiclient with Composer `composer require google/apiclient`  
2. Copy the `google.php` file from the `config` folder and paste it in your application's config folder.
3. Add your `client id`, `client secret`, `apiKey`(optional) and `redirect uri` in the `google.php` file that you just copied. If you don't know how to get the client id and client secret, you can read about it [here] (https://developers.google.com/identity/protocols/OAuth2WebServer#creatingcred). Redirect uri is where you want the user to be redirected to once he logs in with his google email and password.
5. That's it.

##Usage
Usage is pretty simple. The available functions will be enough to log the user in through his google account, get his profile information, logout the user, etc.

###Load the library. 

Before using any of the functions you need to load the library. You can do it in your controller or by adding it to `autoload.php` in your config folder.

```
$this->load->library('google'); //in controller

$autoload['libraries'] = array('google'); //in autoload.php
```

###Check if user is logged in

`$this->google->isLoggedIn()`

Returns `true` if user is logged in, `false` otherwise.

###Get Login URL

`$this->google->getLoginUrl()`

Returns a URL that you can send the user to where he is prompted to login with his google account. After logging in he will be redirected to the link that you set in the `google.php` config file.

###Set Access Token

`$this->google->setAccessToken()`

You MUST call this function when user arrives at the redirect uri after logging in. This step is very important or the login precedure will not be completed.

###Get User Info

`$this->google->getUserInfo()`

Returns an array containing user's data. Some of the available fields are:

1. name
2. email
3. id
4. picture
5. link
6. gender
7. verifiedEmail

###Logout

`$this->google->logout()`

This will log the user out.
