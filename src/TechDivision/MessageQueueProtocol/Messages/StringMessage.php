<?php

/**
 * TechDivision\MessageQueueProtocol\Messages\StringMessage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Library
 * @package    TechDivision_MessageQueueProtocol
 * @subpackage Messages
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueueProtocol\Messages;

/**
 * The implementation for sending a message containing
 * data encapsulated in a String.
 *
 * @category   Library
 * @package    TechDivision_MessageQueueProtocol
 * @subpackage Messages
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link       http://www.appserver.io
 */
class StringMessage extends AbstractMessage
{

    /**
     * The message id as hash value.
     *
     * @var string
     */
    protected $messageId = null;

    /**
     * The message itself.
     *
     * @var String
     */
    protected $message = null;

    /**
     * Initializes the message with the String
     * to send to the queue.
     *
     * @param string $message The string with the data to send
     *
     * @throws \Exception
     */
    public function __construct($message)
    {
        if (is_string($message)) {

            // initialize the String sent with the message
            $this->message = $message;

            // initialize the message id
            $this->messageId = md5(uniqid(rand(), true));

            return;
        }

        throw new \Exception("Message '$message' is not a valid string");
    }

    /**
     * Returns the message id.
     *
     * @return string The message id as hash value
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * The message itself.
     *
     * @return String The message itself
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Returns the message as string.
     *
     * @return string The message as string
     */
    public function __toString()
    {
        return $this->message;
    }
}
