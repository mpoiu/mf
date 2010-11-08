<?php
/**
 * MiST Framework. Extensions for Zend Framework (http://framework.zend.com).
 * 
 * LICENSE
 * 
 * Creative Commons Attribution-ShareAlike 3.0 Unported
 * http://creativecommons.org/licenses/by-sa/3.0/
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. IN NO EVENT
 * SHALL THE COPYRIGHT HOLDERS OR ANYONE DISTRIBUTING THE SOFTWARE BE LIABLE
 * FOR ANY DAMAGES OR OTHER LIABILITY, WHETHER IN CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * 
 * Adapter for authenticating through Facebook Connect
 * @category Mist
 * @package Mist_Auth
 * @subpackage Adapter
 * @author Michiel Staessen <mf@michielstaessen.be>
 * @copyright Copyright (c) 2010 Michiel Staessen (http://www.michielstaessen.be/mf)
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons, Share alike
 * @link http://developers.facebook.com/docs/authentication/#authenticating-users-in-a-web-application
 * 
 */
class Mist_Auth_Adapter_Facebook implements Zend_Auth_Adapter_Interface
{
	/**
	 * The Authentication URL, used to bounce the user to the facebook redirect url.
	 * 
	 * @var string
	 */
	const AUTH_URL = 'https://graph.facebook.com/oauth/authorize';

	/**
	 * The token URL, used to retrieve the OAuth Token.
	 * 
	 * @var string
	 */
	const TOKEN_URL = 'https://graph.facebook.com/oauth/access_token';

	/**
	 * The user URL, used to retrieve information about the user.
	 * 
	 * @var string
	 */
	const USER_URL = 'https://graph.facebook.com/me';
	
	/**
	 * 
	 * Various scope variables
	 * @var string
	 */
	const SCOPE_PUBLISH_STREAM = 'publish_stream';
	const SCOPE_CREATE_EVENT = 'create_event';
	const SCOPE_RSVP_EVENT = 'rsvp_event';
	const SCOPE_SMS = 'sms';
	const SCOPE_OFFLINE_ACCESS = 'offline_access';
	const SCOPE_PUBLISH_CHECKINS = 'publish_checkins';
	const SCOPE_USER_ABOUT_ME = 'user_about_me';
	const SCOPE_USER_ACTIVITIES = 'user_activities';
	const SCOPE_USER_BIRTHDAY = 'user_birthday';
	const SCOPE_USER_EDUCATION_HISTORY = 'user_education_history';
	const SCOPE_USER_EVENTS = 'user_events';
	const SCOPE_USER_GROUPS = 'user_groups';
	const SCOPE_USER_HOMETOWN = 'user_hometown';
	const SCOPE_USER_INTERESTS = 'user_interests';
	const SCOPE_USER_LIKES = 'user_links';
	const SCOPE_USER_LOCATION = 'user_location';
	const SCOPE_USER_NOTES = 'user_notes';
	const SCOPE_USER_ONLINE_PRESENCE = 'user_online_presence';
	const SCOPE_USER_PHOTO_VIDEO_TAGS = 'user_photo_video_tags';
	const SCOPE_USER_PHOTOS = 'user_photos';
	const SCOPE_USER_RELATIONSHIPS = 'user_relationships';
	const SCOPE_USER_RELATIONSHIP_DETAILS = 'user_relationship_details';
	const SCOPE_USER_RELIGION_POLITICS = 'user_religion_politics';
	const SCOPE_USER_STATUS = 'user_status';
	const SCOPE_USER_VIDEOS = 'user_videos';
	const SCOPE_USER_WEBSITE = 'user_website';
	const SCOPE_USER_WORK_HISTORY = 'user_work_history';	
	const SCOPE_EMAIL = 'email';
	const SCOPE_READ_FRIENDLISTS = 'read_friendlists';
	const SCOPE_READ_INSIGHTS = 'read_insights';
	const SCOPE_READ_MAILBOX = 'read_mailbox';
	const SCOPE_READ_REQUESTS = 'read_requests';
	const SCOPE_READ_STREAM = 'read_stream';
	const SCOPE_XMPP_LOGIN = 'xmpp_login';
	const SCOPE_ADS_MANAGEMENT = 'ads_management';
	const SCOPE_USER_CHECKINS = 'user_checkins';
	const SCOPE_MANAGE_PAGES = 'manage_pages';
	const SCOPE_FRIENDS_ABOUT_ME = 'friends_about_me';
	const SCOPE_FRIENDS_ACTIVITIES = 'friends_activities';
	const SCOPE_FRIENDS_BIRTHDAY = 'friends_birthday';
	const SCOPE_FRIENDS_EDUCATION_HISTORY = 'friends_education_history';
	const SCOPE_FRIENDS_EVENTS = 'friends_events';
	const SCOPE_FRIENDS_GROUPS = 'friends_groups';
	const SCOPE_FRIENDS_HOMETOWN = 'friends_hometown';
	const SCOPE_FRIENDS_INTERESTS = 'friends_interests';
	const SCOPE_FRIENDS_LIKES = 'friends_links';
	const SCOPE_FRIENDS_LOCATION = 'friends_location';
	const SCOPE_FRIENDS_NOTES = 'friends_notes';
	const SCOPE_FRIENDS_ONLINE_PRESENCE = 'friends_online_presence';
	const SCOPE_FRIENDS_PHOTO_VIDEO_TAGS = 'friends_photo_video_tags';
	const SCOPE_FRIENDS_PHOTOS = 'friends_photos';
	const SCOPE_FRIENDS_RELATIONSHIPS = 'friends_relationships';
	const SCOPE_FRIENDS_RELATIONSHIP_DETAILS = 'friends_relationship_details';
	const SCOPE_FRIENDS_RELIGION_POLITICS = 'friends_religion_politics';
	const SCOPE_FRIENDS_STATUS = 'friends_status';
	const SCOPE_FRIENDS_VIDEOS = 'friends_videos';
	const SCOPE_FRIENDS_WEBSITE = 'friends_website';
	const SCOPE_FRIENDS_WORK_HISTORY = 'friends_work_history';
	const SCOPE_FRIENDS_CHECKINS = 'friends_checkins';
	
	/**
	 * The application ID
	 *
	 * @var string
	 */
	private $_appId = null;

	/**
	 * The application secret
	 *
	 * @var string
	 */
	private $_appSecret = null;

	/**
	 * The authentication scope (advanced options) requested
	 *
	 * @var array
	 */
	private $_scope = array();

	/**
	 * The redirect uri
	 *
	 * @var string
	 */
	private $_redirectUri = null;
	
	/**
	 * The code on redirect
	 * 
	 * @var string
	 */
	private $_code = null;

	/**
	 * Constructor
	 *
	 * @param array|Zend_Config $options The Facebook configuration
	 */
	public function __construct($options)
	{
		if($options instanceof Zend_Config)
		{
			$options = $options->toArray();
		}
		$this->setAppId($options['appId']);
		$this->setAppSecret($options['appSecret']);
	}

	/**
	 * Sets the value to be used as the application ID
	 *
	 * @param  string $appId The application ID
	 */
	public function setAppId($appId)
	{
		$this->_appId = $appId;
	}
	
	/**
	 * Gets the application ID
	 * 
	 * @return $appId The Application ID
	 */
	public function getAppId()
	{
		return $this->_appId;	
	}

	/**
	 * Sets the value to be used as the application secret
	 *
	 * @param  string $secret The application secret
	 */
	public function setAppSecret($secret)
	{
		$this->_appSecret = $secret;
	}
	
	/**
	 * Gets the application secret
	 * 
	 * @return appSecret The application secret
	 */
	private function getAppSecret()
	{
		return $this->_appSecret;
	}

	/**
	 * Sets the value to be used as the application scope (array())
	 *
	 * @param string $scope The application scope
	 */
	public function setScope($scope)
	{
		if(is_array($scope))
		{
			$this->_scope = $scope;
		}
	}
	
	/**
	 * Gets the scope
	 * 
	 * @return scope The scope
	 */
	public function getScope()
	{
		return $this->_scope;
	}
	
	/**
	 * Gets the scope as a comma-separated string
	 * 
	 * @return scope The scope as a comma separated string
	 */
	public function getScopeAsString()
	{
		$result = '';
		$i = 0;
		foreach($this->getScope() as $scope)
		{
			$result .= $scope;
			if($i != (count($this->getScope()) - 1))
			{
				$result .= ',';
			}
			$i++;
		}
		return $result;
	}
	
	/**
	 * Adds a scope to the scope if it is a valid one and if it is not already in the scope
	 * 
	 * @param string $scope
	 */
	public function addScope($scope)
	{
		if(self::isValidScope($scope) && !in_array($scope, $this->getScope()))
		{
			$this->_scope[] = $scope;
		}
	}
	
	/**
	 * Checks whether a given scope is a valid one
	 * 
	 * @param string $scope
	 * @return valid whether the scope is valid
	 */
	public static function isValidScope($scope)
	{
		return in_array($scope, self::getValidScopes());
	}

	/**
	 * Sets the redirect url after successful authentication
	 *
	 * @param  string $redirectUrl The redirect URL
	 */
	public function setRedirectUrl($redirectUrl)
	{
		$this->_redirectUri = $redirectUrl;
	}
	
	/**
	 * Gets the redirect URL
	 * 
	 * @return url The redirect URL
	 */
	public function getRedirectUrl()
	{
		return $this->_redirectUri;
	}
	
	/**
	 * Gets the code on callback
	 * 
	 * @return code The callback code
	 */
	public function getCode()
	{
		return $this->_code;
	}
	
	/**
	 * Sets the code on callback
	 * 
	 * @param string $code
	 */
	public function setCode($code)
	{
		$this->_code = $code;
	}

	/**
	 * Authenticates the user against facebook
	 * Defined by Zend_Auth_Adapter_Interface.
	 *
	 * @throws Zend_Auth_Adapter_Exception If answering the authentication query is impossible
	 * @return Zend_Auth_Result
	 */
	public function authenticate()
	{
		// Processing the first part of the authentication process.
		if(empty($this->_code))
		{
			// Create the uri
			$loginUrl = self::AUTH_URL . '?' . http_build_query(array(
				'client_id' 	=> $this->getAppId(),
				'redirect_uri' 	=> $this->getRedirectUrl(),
				'scope'			=> $this->getScopeAsString()
			), '', '&');
			
			header('Location: ' . $loginUrl);
		}
		// Processing the second part of the authentication
		else
		{
			$client = new Zend_Http_Client(self::TOKEN_URL);
			$client->setParameterGet('client_id', $this->getAppId());
			$client->setParameterGet('client_secret', $this->getAppSecret());
			$client->setParameterGet('code', $this->getCode());
			$client->setParameterGet('redirect_uri', $this->getRedirectUrl());
			$result = $client->request('GET');
			$params = array();
			parse_str($result->getBody(), $params);
			
			// Get some info about the user
			$client = new Zend_Http_Client(self::USER_URL);
			$client->setParameterGet('client_id', $this->getAppId());
			$client->setParameterGet('access_token', $params['access_token']);
			$result = $client->request('GET');
			$user = json_decode($result->getBody());
			
			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $user, array(Zend_Auth_Result::SUCCESS => 'Successfully logged in!'));
		}
		
		return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, array(Zend_Auth_Result::FAILURE => 'Error during the Oauth process.'));
	}
	
	/**
	 * Returns all valid scopes.
	 * 
	 * @return array $validScopes
	 */
	public static function getValidScopes()
	{
		return array(
			self::SCOPE_PUBLISH_STREAM,
			self::SCOPE_CREATE_EVENT,
			self::SCOPE_RSVP_EVENT,
			self::SCOPE_SMS,
			self::SCOPE_OFFLINE_ACCESS,
			self::SCOPE_PUBLISH_CHECKINS,
			self::SCOPE_USER_ABOUT_ME,
			self::SCOPE_USER_ACTIVITIES,
			self::SCOPE_USER_BIRTHDAY,
			self::SCOPE_USER_EDUCATION_HISTORY,
			self::SCOPE_USER_EVENTS,
			self::SCOPE_USER_GROUPS,
			self::SCOPE_USER_HOMETOWN,
			self::SCOPE_USER_INTERESTS,
			self::SCOPE_USER_LIKES,
			self::SCOPE_USER_LOCATION,
			self::SCOPE_USER_NOTES,
			self::SCOPE_USER_ONLINE_PRESENCE,
			self::SCOPE_USER_PHOTO_VIDEO_TAGS,
			self::SCOPE_USER_PHOTOS,
			self::SCOPE_USER_RELATIONSHIPS,
			self::SCOPE_USER_RELATIONSHIP_DETAILS,
			self::SCOPE_USER_RELIGION_POLITICS,
			self::SCOPE_USER_STATUS,
			self::SCOPE_USER_VIDEOS,
			self::SCOPE_USER_WEBSITE,
			self::SCOPE_USER_WORK_HISTORY,	
			self::SCOPE_EMAIL,
			self::SCOPE_READ_FRIENDLISTS,
			self::SCOPE_READ_INSIGHTS,
			self::SCOPE_READ_MAILBOX,
			self::SCOPE_READ_REQUESTS,
			self::SCOPE_READ_STREAM,
			self::SCOPE_XMPP_LOGIN,
			self::SCOPE_ADS_MANAGEMENT,
			self::SCOPE_USER_CHECKINS,
			self::SCOPE_MANAGE_PAGES,
			self::SCOPE_FRIENDS_ABOUT_ME,
			self::SCOPE_FRIENDS_ACTIVITIES,
			self::SCOPE_FRIENDS_BIRTHDAY,
			self::SCOPE_FRIENDS_EDUCATION_HISTORY,
			self::SCOPE_FRIENDS_EVENTS,
			self::SCOPE_FRIENDS_GROUPS,
			self::SCOPE_FRIENDS_HOMETOWN,
			self::SCOPE_FRIENDS_INTERESTS,
			self::SCOPE_FRIENDS_LIKES,
			self::SCOPE_FRIENDS_LOCATION,
			self::SCOPE_FRIENDS_NOTES,
			self::SCOPE_FRIENDS_ONLINE_PRESENCE,
			self::SCOPE_FRIENDS_PHOTO_VIDEO_TAGS,
			self::SCOPE_FRIENDS_PHOTOS,
			self::SCOPE_FRIENDS_RELATIONSHIPS,
			self::SCOPE_FRIENDS_RELATIONSHIP_DETAILS,
			self::SCOPE_FRIENDS_RELIGION_POLITICS,
			self::SCOPE_FRIENDS_STATUS,
			self::SCOPE_FRIENDS_VIDEOS,
			self::SCOPE_FRIENDS_WEBSITE,
			self::SCOPE_FRIENDS_WORK_HISTORY,
			self::SCOPE_FRIENDS_CHECKINS
		);
	}
}