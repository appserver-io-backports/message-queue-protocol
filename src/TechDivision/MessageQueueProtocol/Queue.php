<?php

/**
 * TechDivision\MessageQueueProtocol\Queue
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
 * Class Queue
 *
 * @category  Library
 * @package   TechDivision_MessageQueueProtocol
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link      http://www.appserver.io
 */
interface Queue
{

    /**
     * Returns the message queue name.
     *
     * @return string The message queue name
     */
    public function getName();
}
