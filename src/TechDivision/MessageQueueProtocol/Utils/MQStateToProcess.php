<?php

/**
 * TechDivision\MessageQueueProtocol\Utils\MQStateToProcess
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
 * @subpackage Utils
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueueProtocol\Utils;

/**
 * This class holds the MQStateKey used
 * for messages to process.
 *
 * @category   Library
 * @package    TechDivision_MessageQueueProtocol
 * @subpackage Utils
 * @author     Tim Wagner <tw@techdivision.com>
 * @author     Markus Stockbauer <ms@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link       http://www.appserver.io
 */
class MQStateToProcess implements MQStateKey
{

    /**
     * Holds the key for messages with an in progress state.
     * @var integer
     */
    const KEY = 3;

    /**
     * The string value for the 'toProcess' MQStateKey.
     * @var string
     */
    protected $state = "toProcess";

    /**
     * Private constructor for marking
     * the class as utility.
     */
    final protected function __construct()
    {
        /* Class is a utility class */
    }

    /**
     * Returns a new instance of the MQStateKey.
     *
     * @return MQStateToProcess The instance
     */
    public static function get()
    {
        return new MQStateToProcess();
    }

    /**
     * Returns the key value of the
     * StateKey instance.
     *
     * @return integer The key value
     */
    public function getState()
    {
        return MQStateToProcess::KEY;
    }

    /**
     * Returns the string value for the high MQStateKey.
     *
     * @return string The string value
     */
    public function __toString()
    {
        return $this->state;
    }
}
