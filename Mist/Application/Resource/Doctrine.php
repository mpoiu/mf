<?php

use Doctrine\Common\ClassLoader,
	Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Mapping\Driver;
    	
require_once 'Doctrine/Common/ClassLoader.php';

class Mist_Application_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
	const DEFAULT_REGISTRY_KEY = 'Doctrine';
	
	protected static $_doctrine;
	
	
	/* (non-PHPdoc)
	 * @see Zend_Application_Resource_Resource::init()
	 */
	public function init()
	{
		return $this->getDoctrine();
	}
	
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
				
				if(isset($options['config']['mapping'])
					&& isset($options['config']['mapping']['paths']))
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