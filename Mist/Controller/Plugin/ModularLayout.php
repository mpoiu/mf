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
 * Plugin for switching between layouts when loading different modules
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
	 * Called before an action is dispatched by Zend_Controller_Dispatcher. 
	 * The layout file will be swapped before the action is dispatched. 
	 * @see Zend_Controller_Plugin_Abstract::preDispatch()
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayout($request->getModuleName());
	}
}