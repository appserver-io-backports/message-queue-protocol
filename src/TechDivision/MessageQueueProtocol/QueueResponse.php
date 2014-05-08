<?php
/**
 * TechDivision\MessageQueueProtocol\QueueResponse
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

use TechDivision\MessageQueueProtocol\MessageQueueProtocol;
/**
 * Class QueueResponse
 *
 * @category  Library
 * @package   TechDivision_MessageQueueProtocol
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link      http://www.appserver.io
 */
class QueueResponse
{

    /**
     * The message status.
     *
     * @var integer
     */
    protected $statusCode = MessageQueueProtocol::STATUS_CODE_UNKNOWN;

    /**
     * The message status message.
     *
     * @var string
     */
    protected $message = '';

    /**
     * Initializes the QueueResponse with the parts splitted from the MessageQueue response.
     *
     * @param integer $statusCode The response status from the queue
     * @param string  $message    The status message sent along with status code
     *
     * @return void
     */
    public function __construct($statusCode, $message)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    /**
     * Returns true if the Message was successfully delivered
     * to the MessageQueue.
     *
     * @return boolean True if the Message was successfully delivered, else false
     */
    public function success()
    {
    	return $this->getStatusCode() === MessageQueueProtocol::STATUS_CODE_OK;
    }

    /**
     * Returns the status from the message queue response.
     *
     * @return integer The status itself
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns the status message from the message queue response.
     *
     * @return string The status message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
