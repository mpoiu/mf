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
	public function authenticate()
	{
		
	}
}