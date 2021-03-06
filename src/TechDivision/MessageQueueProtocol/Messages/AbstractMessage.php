<?php

/**
 * TechDivision\MessageQueueProtocol\Messages\AbstractMessage
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

use TechDivision\MessageQueueProtocol\Queue;
use TechDivision\MessageQueueProtocol\Message;
use TechDivision\MessageQueueProtocol\Monitor;
use TechDivision\MessageQueueProtocol\Utils\PriorityKeys;
use TechDivision\MessageQueueProtocol\Utils\PriorityKey;
use TechDivision\MessageQueueProtocol\Utils\PriorityLow;
use TechDivision\MessageQueueProtocol\Utils\MQStateKey;
use TechDivision\MessageQueueProtocol\Utils\MQStateKeys;
use TechDivision\MessageQueueProtocol\Utils\MQStateActive;

/**
 * The abstract superclass for all messages.
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
abstract class AbstractMessage implements Message, \Serializable
{

    /**
     * The unique session id.
     *
     * @var string
     */
    protected $sessionId = "";

    /**
     * The destination queue to send the message to.
     *
     * @var \TechDivision\MessageQueueProtocol\Queue
     */
    protected $destination = null;

    /**
     * The parent message.
     *
     * @var \TechDivision\MessageQueueProtocol\Message
     */
    protected $parentMessage = null;

    /**
     * The monitor for monitoring the message.
     *
     * @var \TechDivision\MessageQueueProtocol\Monitor
     */
    protected $messageMonitor = null;

    /**
     * The priority of the message, defaults to 'low'.
     *
     * @var integer
     */
    protected $priority = PriorityLow::KEY;

    /**
     * The state of the message, defaults to 'active'.
     *
     * @var integer
     */
    protected $state = MQStateActive::KEY;

    /**
     * The flag if the message should be deleted when finished or not.
     *
     * @var boolean
     */
    protected $locked = false;

    /**
     * Sets the unique session id.
     *
     * @param string $sessionId The uniquid id
     *
     * @return void
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Returns the unique session id.
     *
     * @return string The uniquid id
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Sets the destination queue.
     *
     * @param \TechDivision\MessageQueueProtocol\Queue $destination The destination queue
     *
     * @return void
     */
    public function setDestination(Queue $destination)
    {
        $this->destination = $destination;
    }

    /**
     * Returns the destination queue.
     *
     * @return \TechDivision\MessageQueueProtocol\Queue The destination queue
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Sets the priority of the message.
     *
     * @param \TechDivision\MessageQueueProtocol\Utils\PriorityKey $priority The priority to set the message to
     *
     * @return void
     */
    public function setPriority(PriorityKey $priority)
    {
        $this->priority = $priority->getPriority();
    }

    /**
     * Returns the priority of the message.
     *
     * @return \TechDivision\MessageQueueProtocol\Utils\PriorityKey The priority of the message
     */
    public function getPriority()
    {
        return PriorityKeys::get($this->priority);
    }

    /**
     * Sets the state of the message.
     *
     * @param \TechDivision\MessageQueueProtocol\Utils\MQStateKey $state The new state
     *
     * @return void
     */
    public function setState(MQStateKey $state)
    {
        $this->state = $state->getState();
    }

    /**
     * Returns the state of the message.
     *
     * @return \TechDivision\MessageQueueProtocol\Utils\MQStateKey The message state
     */
    public function getState()
    {
        return MQStateKeys::get($this->state);
    }

    /**
     * Sets the parent message.
     *
     * @param \TechDivision\MessageQueueProtocol\Message $parentMessage The parent message
     *
     * @return void
     */
    public function setParentMessage(Message $parentMessage)
    {
        $this->parentMessage = $parentMessage;
    }

    /**
     * Returns the parent message.
     *
     * @return \TechDivision\MessageQueueProtocol\Message The parent message
     *
     * @see \TechDivision\MessageQueueProtocol\Message::getParentMessage()
     */
    public function getParentMessage()
    {
        return $this->parentMessage;
    }

    /**
     * Sets the monitor for monitoring the message itself.
     *
     * @param \TechDivision\MessageQueueProtocol\Monitor $messageMonitor The monitor
     *
     * @return void
     */
    public function setMessageMonitor(Monitor $messageMonitor)
    {
        $this->messageMonitor = $messageMonitor;
    }

    /**
     * Returns the message monitor.
     *
     * @return \TechDivision\MessageQueueProtocol\Monitor The monitor
     *
     * @see \TechDivision\MessageQueueProtocol::getMessageMonitor()
     */
    public function getMessageMonitor()
    {
        return $this->messageMonitor;
    }

    /**
     * Locks the message.
     *
     * @return void
     */
    public function lock()
    {
        $this->locked = true;
    }

    /**
     * Unlocks the message.
     *
     * @return void
     */
    public function unlock()
    {
        $this->locked = false;
    }

    /**
     * Returns the message lock flag.
     *
     * @return boolean TRUE if the message is locked, else FALSE
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * Serializes the message and returns the serialized representation.
      *
     * @return string the string representation of the object or null
     * @link http://php.net/manual/en/serializable.serialize.php
     */
    public function serialize()
    {
        return serialize(get_object_vars($this));
    }

    /**
     * The serialized representation of the message.
     *
     * @param string $data The string representation of the object
     *
     * @return void
     * @link http://php.net/manual/en/serializable.unserialize.php
     */
    public function unserialize($data)
    {
        foreach (unserialize($data) as $propertyName => $propertyValue) {
            $this->$propertyName = $propertyValue;
        }
    }
}
