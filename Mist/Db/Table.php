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
 * @see Zend_Db_Table
 */
require_once 'Zend/Db/Table.php';

/**
 * Class for SQL table interface with table prefixes enabled.
 * @category Mist
 * @package Mist_Db
 * @subpackage Table
 * @author Michiel Staessen <mf@michielstaessen.be>
 * @copyright Copyright (c) 2010 Michiel Staessen (http://www.michielstaessen.be/mf)
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons, Share alike
 */
class Mist_Db_Table extends Zend_Db_Table
{

	/**
	 * __construct() - For concrete implementation of Zend_Db_Table. 
	 * Instantiation through this constructor enables the use of database table prefixes.
	 * @see Zend_Db_Table
	 */
	public function __construct($config = array(), $definition = null)
	{
		parent::__construct($config, $definition);
		$config = $this->getAdapter()->getConfig();
		if(array_key_exists('prefix', $config))
		{
			$this->_name = $config['prefix'] . $this->_name;
		}
	}
}