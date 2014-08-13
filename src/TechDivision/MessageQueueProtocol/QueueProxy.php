<?php

/**
 * TechDivision\MessageQueueProtocol\QueueProxy
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

use TechDivision\MessageQueueProtocol\Queue;

/**
 * A proxy implementation for a queue.
 *
 * @category  Library
 * @package   TechDivision_MessageQueueProtocol
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link      http://www.appserver.io
 */
class QueueProxy implements Queue
{

    /**
     * The queue name to use.
     *
     * @var string
     */
    protected $name = null;

    /**
     * Initializes the queue with the name to use.
     *
     * @param string $name Holds the queue name to use
     */
    private function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the queue name.
     *
     * @return string The queue name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Initializes and returns a new proxy instance for the passed queue.
     *
     * @param \TechDivision\MessageQueueProtocol\Queue $queue The queue to create the proxy for
     *
     * @return \TechDivision\MessageQueueProtocol\Queue The proxy instance
     */
    public static function createFromQueue(Queue $queue)
    {
        return new QueueProxy($queue->getName());
    }
}
