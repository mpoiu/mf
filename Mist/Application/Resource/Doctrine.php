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

use Doctrine\Common\ClassLoader, 
	Doctrine\ORM\Configuration, 
	Doctrine\ORM\EntityManager, 
	Doctrine\ORM\Mapping\Driver;

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * @see Doctrine\Common\ClassLoader
 */
require_once 'Doctrine/Common/ClassLoader.php';

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
class Mist_Application_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * Registry key for Doctrine EntityManager
	 * 
	 * @var string
	 */
	const DEFAULT_REGISTRY_KEY = 'Doctrine';

	/**
	 * Doctrine EntityManager
	 * 
	 * @var Doctrine\ORM\EntityManager
	 */
	protected static $_doctrine;

	/**
	 * 
	 * Defined by Zend_Application_Resource_Resource
	 * 
	 * @return Doctrine\ORM\EntityManager
	 */
	public function init()
	{
		return $this->getDoctrine();
	}

	/**
	 * Sets up the Doctrine EntityManager if it is not set, saves it in the Registry,
	 * and then returns it.
	 * 
	 * @throws Mist_Exception if certain configuration keys are missing
	 */
	public function getDoctrine()
	{
		$options = $this->getOptions();
		
		$classLoader = new ClassLoader('Doctrine\ORM', $options['ormPath']);
		$classLoader->register();
		$classLoader = new ClassLoader('Doctrine\DBAL', $options['dbalPath']);
		$classLoader->register();
		$classLoader = new ClassLoader('Doctrine\Common', $options['commonPath']);
		$classLoader->register();
		
		if(null === self::$_doctrine)
		{
			$config = new \Doctrine\ORM\Configuration();
			// Check for a configuration
			if(!array_key_exists('config', $options))
			{
				require_once 'Mist/Exception.php';
				throw new Mist_Exception('You need to specify the configuration.');
			}
			else
			{
				if(isset($options['config']['autoGenerateProxyClasses']))
				{
					$config->setAutoGenerateProxyClasses($options['config']['autoGenerateProxyClasses']);
				}
				
				if(isset($options['config']['proxyDir']))
				{
					$config->setProxyDir($options['config']['proxyDir']);
				}
				
				if(isset($options['config']['proxyNamespace']))
				{
					$config->setProxyNamespace($options['config']['proxyNamespace']);
				}
				
				if(isset($options['config']['mapping']) && isset($options['config']['mapping']['paths']))
				{
					$driver = null;
					if($options['config']['mapping']['driver'] == 'annotation')
					{
						$driver = $config->newDefaultAnnotationDriver($options['config']['mapping']['paths']);
					}
					else
					{
						$driverClass = 'Doctrine\\ORM\\Mapping\\Driver\\' . ucfirst($options['config']['mapping']['driver']) . 'Driver';
						$driver = new $driverClass($options['config']['mapping']['paths']);
					}
					$config->setMetadataDriverImpl($driver);
				}
				
				if(isset($options['config']['cache']))
				{
					//TODO
				}
				
				if(isset($options['config']['customDateTimeFunctions']))
				{
					$config->setCustomDatetimeFunctions($options['config']['customDateTimeFunctions']);
				}
				
				if(isset($options['config']['customNumericFunctions']))
				{
					$config->setCustomNumericFunctions($options['config']['customNumericFunctions']);
				}
				
				if(isset($options['config']['customStringFunctions']))
				{
					$config->setCustomStringFunctions($options['config']['customStringFunctions']);
				}
				
				if(isset($options['config']['entityNamespaces']))
				{
					$config->setEntityNamespaces($options['config']['entityNamespaces']);
				}
				
				// Check for a connection
				if(!array_key_exists('connection', $options))
				{
					require_once 'Mist/Exception.php';
					throw new Mist_Exception('You need to specify a connection.');
				}
				else
				{
					self::$_doctrine = EntityManager::create($options['connection'], $config);
					Zend_Registry::set(self::DEFAULT_REGISTRY_KEY, self::$_doctrine);
				}
			}
		}
		
		return self::$_doctrine;
	}
}