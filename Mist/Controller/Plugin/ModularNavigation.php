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
 * @see Zend_Controller_Plugin_Abstract
 */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * 
 * Plugin for switching between navigation when loading different modules
 * @category Mist
 * @package Mist_Controller
 * @subpackage Plugin
 * @author Michiel Staessen <mf@michielstaessen.be>
 * @copyright Copyright (c) 2010 Michiel Staessen (http://www.michielstaessen.be/mf)
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons, Share alike
 * 
 */
class Mist_Controller_Plugin_ModularLayout extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Sets the right navigation file before the action is dispatched.
	 * @see Zend_Controller_Plugin_Abstract::preDispatch()
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$navigationPath = APPLICATION_PATH . '/configs/navigation/' . $request->getModuleName() . '.xml';
		if(file_exists($navigationPath))
		{
			$view = Zend_Layout::getMvcInstance()->getView();
			$config = new Zend_Config_Xml($navigationPath, 'nav');
			$navigation = new Zend_Navigation($config);
			$view->navigation($navigation);
			
			$activeNav = $navigation->findByUri($request->getPathInfo());
			$activeNav->active = true;
		}
	}
}