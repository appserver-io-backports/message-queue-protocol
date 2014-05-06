<?php

/**
 * TechDivision\MessageQueueProtocol\Interfaces\MessageReceiver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category  Library
 * @package   TechDivision_MessageQueueProtocol
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueueProtocol;

/**
 * The interface for all message receivers.
 *
 * @category  Library
 * @package   TechDivision_MessageQueueProtocol
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link      http://www.appserver.io
 */
interface Receiver
{

    /**
     * This function is invoked by the MessageQueue if a message
     * related to the receiver was received.
     *
     * @param \TechDivision\MessageQueueProtocol\Message $message   The message itself
     * @param string                                     $sessionId The session ID
     *
     * @return void
     */
    public function onMessage(Message $message, $sessionId);
}
