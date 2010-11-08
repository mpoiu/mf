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
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * 
 * Resource for initializing the Twitter configuration
 * @category Mist
 * @package Mist_Application
 * @subpackage Resource
 * @author Michiel Staessen <mf@michielstaessen.be>
 * @copyright Copyright (c) 2010 Michiel Staessen (http://www.michielstaessen.be/mf)
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons, Share alike
 *
 */
class Mist_Application_Resource_Twitter extends Zend_Application_Resource_ResourceAbstract
{
	const DEFAULT_REGISTRY_KEY = 'Twitter';
	
	   /**
     * @var Zend_Config
     */
    protected $_twitter;

	/**
	 * 
	 * Defined by Zend_Application_Resource_Resource
	 * 
	 * @return Zend_Config
	 */
	public function init()
	{
		$this->getTwitter();
	}
	
	/**
	 * 
	 * Retrieve Twitter configuration
	 * 
	 * @return Zend_Config
	 */
	public function getTwitter()
	{
		if(null === $this->_twitter)
		{
			$options = $this->getOptions();
			if(array_key_exists('apiKey', $options)
				&& array_key_exists('consumerKey', $options)
				&& array_key_exists('consumerSecret', $options)
				&& array_key_exists('requestTokenUrl', $options)
				&& array_key_exists('accessTokenUrl', $options)
				&& array_key_exists('authorizeUrl', $options))
			{
				$this->_twitter = new Zend_Config($options);
			}
			else
			{
				require_once 'Mist/Exception.php';
				throw new Mist_Exception("Missing argument in Twitter configuration. (obligatory fields: apiKey, consumerKey, consumerSecret, requestTokenUrl, accessTokenUrl, authorizeUrl)");
			}
		}
		Zend_Registry::set(self::DEFAULT_REGISTRY_KEY, $this->_twitter);
		
		return $this->_twitter;
	}
}