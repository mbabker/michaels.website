<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Authentication;

/**
 * AuthenticationException
 *
 * @since  1.0
 */
class AuthenticationException extends \Exception
{
	/**
	 * Constructor.
	 *
	 * @param   string  $message  The optional message to throw.
	 *
	 * @since   1.0
	 */
	public function __construct($message = '')
	{
		parent::__construct($message, 403);
	}
}
