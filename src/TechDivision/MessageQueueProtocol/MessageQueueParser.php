<?php

/**
 * TechDivision\MessageQueueProtocol\MessageQueueParser
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

use TechDivision\Server\Sockets\SocketInterface;

/**
 * This is a parser for a native message invocation.
 *
 * A method invokation must have the following format:
 *
 * <METHOD> <CONTENT-LENGTH> <PROTOCOL>/<VERSION>\r\n
 * <CONTENT>
 *
 * for example:
 *
 * MSG 12 MQ/1.0
 * czoxOiIxIjs=
 *
 * @category  Library
 * @package   TechDivision_MessageQueueProtocol
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_MessageQueueProtocol
 * @link      http://www.appserver.io
 */
class MessageQueueParser
{

    /**
     * Parses the header of a message call call.
     *
     * @param string $line The header line to parse
     *
     * @return integer The content length to parse
     * @throws \TechDivision\MessageQueueProtocol\MessageQueueException
     */
    public function parseHeader($line)
    {

        // parse the header line with
        list ($messageType, $contentLength, $protocolVersion) = explode(' ', trim($line));

        // parse protocol and version
        list ($protocol, $version) = explode('/', $protocolVersion);

        // check if protocol and version are valid
        if ($protocol !== MessageQueueProtocol::PROTOCOL && $version !== MessageQueueProtocol::VERSION) {
            throw new MessageQueueException(sprintf('Protocol %s not supported', $protocolVersion));
        }

        // check the message type
        switch ($messageType) {

            case MessageQueueProtocol::MESSAGE_TYPE_MSG:
                return (integer) $contentLength;
                break;

            default:
                throw new MessageQueueException(sprintf('Found invalid message type %s', $messageType));
                break;

        }
    }

    /**
     * Parses the request body and tries to unpack the remote method
     * instance from it.
     *
     * @param \TechDivision\Server\Sockets\SocketInterface $connection    The package remote method instance
     * @param integer                                      $contentLength The content lenght to read
     *
     * @return object The unpacked message object
     */
    public function parseBody(SocketInterface $connection, $contentLength)
    {
        $rawResponse = stream_get_contents($connection->getConnectionResource(), $contentLength);
        return MessageQueueProtocol::unpack($rawResponse);
    }

    /**
     * Parses the messsage queue response and returns a response instance.
     *
     * @param string $line The response string to parse
     *
     * @return \TechDivision\MessageQueueProtocol\QueueResponse The queue response instance
     * @throws \TechDivision\MessageQueueProtocol\MessageQueueException Is thrown if we found an invalid status code
     */
    public function parseResult($line)
    {
        // parse the header line with
        list ($protocolVersion, $statusCode, $message, ) = explode(' ', trim($line));

        // parse protocol and version
        list ($protocol, $version) = explode('/', $protocolVersion);

        // check if protocol and version are valid
        if ($protocol !== MessageQueueProtocol::PROTOCOL && $version !== MessageQueueProtocol::VERSION) {
            throw new MessageQueueException(sprintf('Protocol %s not supported', $protocolVersion));
        }

        // prepare the queue response
        $responseMessages = MessageQueueProtocol::getResponseMessages();
        if (isset($responseMessages[$statusCode])) {
            return new QueueResponse($statusCode, $message);
        }

        // we can't prepare the queue response because of an unknown status code
        throw new MessageQueueException(sprintf('Found unknown status code %d', $statusCode));
    }
}
