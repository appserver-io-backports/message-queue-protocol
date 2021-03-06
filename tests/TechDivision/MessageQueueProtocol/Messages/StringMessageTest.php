<?php

/**
 * TechDivision\MessageQueueProtocol\Messages\StringMessageTest
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
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link       http://www.appserver.io
 */

namespace TechDivision\MessageQueueProtocol\Messages;

/**
 * Test implementation class for the string message implementation.
 *
 * @category   Library
 * @package    TechDivision_MessageQueueProtocol
 * @subpackage Messages
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link       http://www.appserver.io
 */
class StringMessageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * An example string to initialize a string message.
     *
     * @var string
     */
    const EXAMPLE_STRING = 'exampleString';

    /**
     * Another example string to initialize a string message.
     *
     * @var string
     */
    const ANOTHER_EXAMPLE_STRING = 'anotherExampleString';

    /**
     * The string message implementation we want to test.
     *
     * @var \TechDivision\MessageQueueProtocol\Messages\StringMessage
     */
    protected $stringMessage;

    /**
     * Initializes the instance we want to run the testcases for.
     *
     * @return void
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->stringMessage = new StringMessage(StringMessageTest::EXAMPLE_STRING);
    }

    /**
     * Test the serialize/unserialize implementation for a string message.
     *
     * @return void
     */
    public function testSerializeAndUnserialize()
    {

        // serialize the message
        $serialized = $this->stringMessage->serialize();

        // create a new message representation using a different message
        $toCompare = new StringMessage(StringMessageTest::ANOTHER_EXAMPLE_STRING);
        $toCompare->unserialize($serialized);

        // test messages to be equal
        $this->assertEquals($this->stringMessage, $toCompare);
    }
}
