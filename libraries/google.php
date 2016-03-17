<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Login with Google for Codeigniter
 *
 * Library for Codeigniter to authenticate users through Google OAuth 2.0 and get user profile info
 *
 * @author      Harsha G
 * @license     MIT
 * @link        https://github.com/darkwhispering/facebook-sdk-v4-codeigniter
 */

class Google {
	public function __construct()
	{
		$this->ci =& get_instance();

		include (APPPATH.'libraries/googleSDK/vendor/autoload.php');

		$this->ci->load->config('google');

		$this->ci->load->library('session');

		$this->client = new Google_Client();

		$this->client->setClientId($this->ci->config->item('clientId'));

		$this->client->setClientSecret($this->ci->config->item('clientSecret'));

		$this->client->setRedirectUri($this->ci->config->item('redirectUri'));

		$this->client->addScope('https://www.googleapis.com/auth/userinfo.email');

		$this->client->addScope('https://www.googleapis.com/auth/userinfo.profile');

		$this->client->setAccessType('offline');

		if($this->ci->session->userdata('refreshToken')!=null)
		{
			$this->loggedIn = true;

			if($this->client->isAccessTokenExpired())
			{
				$this->client->refreshToken($this->ci->session->userdata('refreshToken'));
        		
        		$accessToken = $this->client->getAccessToken();

        		$this->client->setAccessToken($accessToken);
			}
		}
		else
		{
			$accessToken = $this->client->getAccessToken();

			if($accessToken!=null)
			{
				$this->client->revokeToken($accessToken);
			}

			$this->loggedIn = false;
		}
	}

	public function isLoggedIn()
	{
		return $this->loggedIn;
	}

	public function getLoginUrl()
	{
		return $this->client->createAuthUrl();
	}

	public function setAccessToken()
	{
		$this->client->authenticate($_GET['code']);

		$accessToken = $this->client->getAccessToken();

		$this->client->setAccessToken($accessToken);

		if(isset($accessToken['refresh_token']))
		{
			$this->ci->session->set_userdata('refreshToken', $accessToken['refresh_token']);
		}
	}

	public function getUserInfo()
	{
		$service = new Google_Service_Oauth2($this->client);

		return $service->userinfo->get();
	}

	public function logout()
	{
		$this->ci->session->unset_userdata('refreshToken');

		$accessToken = $this->client->getAccessToken();

		if($accessToken!=null)
		{
			$this->client->revokeToken($accessToken);
		}
	}
}

?>