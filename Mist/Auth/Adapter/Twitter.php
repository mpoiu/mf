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
 * @see Zend_Oauth_Consumer
 */
require_once 'Zend/Oauth/Consumer.php';

/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';

/**
 * 
 * Adapter for authenticating through Twitter Signin
 * @category Mist
 * @package Mist_Auth
 * @subpackage Adapter
 * @author Michiel Staessen <mf@michielstaessen.be>
 * @copyright Copyright (c) 2010 Michiel Staessen (http://www.michielstaessen.be/mf)
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons, Share alike
 * @link http://dev.twitter.com/pages/sign_in_with_twitter
 *
 */
class Mist_Auth_Adapter_Twitter implements Zend_Auth_Adapter_Interface
{
	/**
	 * Session namespace for token storage
	 * 
	 * @var string
	 */
	const AUTH_NAMESPACE = 'Mist_Auth_Adapter_Twitter';

	/**
	 * Oauth options
	 * 
	 * @var array
	 */
	protected $_options = array();

	/**
	 * The oauth token on callback
	 * 
	 * @var string
	 */
	protected $_oauthToken = null;

	/**
	 * The oauth verifier on callback
	 * 
	 * @var string
	 */
	protected $_oauthVerifier = null;

	/**
	 * Constructor for OAuth Authentication and Authorization
	 * 
	 * @param string $options
	 * @throws Mist_Exception if not all parameters are supplied.
	 */
	public function __construct($options)
	{
		if($options instanceof Zend_Config)
		{
			$options = $options->toArray();
		}
		if(array_key_exists('consumerKey', $options)
			)
		{
			$this->_options = $options;
		}
		else
		{
			require_once 'Mist/Exception.php';
			throw new Mist_Exception('Config parameter missing.');
		}
	}

	/**
	 * Gets the Oauth options
	 * 
	 * @return array
	 */
	public function getOptions()
	{
		return $this->_options;
	}
	
	/**
	 * Gets the Oauth token
	 * 
	 * @return the $_oauthToken
	 */
	public function getOauthToken()
	{
		return $this->_oauthToken;
	}

	/**
	 * Gets the oauth verifier
	 * 
	 * @return the $_oauthVerifier
	 */
	public function getOauthVerifier()
	{
		return $this->_oauthVerifier;
	}

	/**
	 * Sets the oauth token
	 * 
	 * @param string $_oauthToken
	 */
	public function setOauthToken($_oauthToken)
	{
		$this->_oauthToken = $_oauthToken;
	}

	/**
	 * Sets the oauth verifier
	 * 
	 * @param string $_oauthVerifier
	 */
	public function setOauthVerifier($_oauthVerifier)
	{
		$this->_oauthVerifier = $_oauthVerifier;
	}

	/**
	 * Authenticate through 
	 * @see Zend_Auth_Adapter_Interface::authenticate()
	 */
	public function authenticate()
	{
		$session = new Zend_Session_Namespace(self::AUTH_NAMESPACE);
		$consumer = new Zend_Oauth_Consumer($this->getOptions());
		try
		{
			// First part of Oauth Authentication
			if(null === $this->getOauthToken() || null === $this->getOauthVerifier())
			{
				$token = $consumer->getRequestToken();
				$session->requestToken = $token;
				$consumer->redirect();
			}
			// Second part of authentication 
			else
			{
				$params = array(
					'oauth_token'		=>	$this->getOauthToken(),
					'oauth_verifier'	=>	$this->getOauthVerifier()
				);
				$token = $consumer->getAccessToken($params, $session->requestToken);
				
				// Get some information about the user
				$client = $token->getHttpClient($this->getOptions());
				
				return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $token);
			}
		}
		catch(Exception $e)
		{
			return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, null, array(Zend_Auth_Result::FAILURE => $e->getMessage()));
		}
	}
}