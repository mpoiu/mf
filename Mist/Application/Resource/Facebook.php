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
 * Resource for initializing the Facebook configuration
 * @category Mist
 * @package Mist_Application
 * @subpackage Resource
 * @author Michiel Staessen <mf@michielstaessen.be>
 * @copyright Copyright (c) 2010 Michiel Staessen (http://www.michielstaessen.be/mf)
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons, Share alike
 *
 */
class Mist_Application_Resource_Facebook extends Zend_Application_Resource_ResourceAbstract
{
	const DEFAULT_REGISTRY_KEY = 'Facebook';
	
	   /**
     * @var Zend_Config
     */
    protected $_facebook;

	/**
	 * 
	 * Defined by Zend_Application_Resource_Resource
	 * 
	 * @return Zend_Config
	 */
	public function init()
	{
		$this->getFacebook();
	}
	
	/**
	 * 
	 * Retrieve Facebook configuration
	 * 
	 * @return Zend_Config
	 */
	public function getFacebook()
	{
		if(null === $this->_facebook)
		{
			$options = $this->getOptions();
			if(array_key_exists('appId', $options)
				&& array_key_exists('apiKey', $options)
				&& array_key_exists('appSecret', $options))
			{
				$this->_facebook = new Zend_Config($options);
			}
			else
			{
				require_once 'Mist/Exception.php';
				throw new Mist_Exception("Missing argument in Facebook configuration. (obligatory fields: appId, apiKey, appSecret)");
			}
		}
		Zend_Registry::set(self::DEFAULT_REGISTRY_KEY, $this->_facebook);
		
		return $this->_facebook;
	}
}