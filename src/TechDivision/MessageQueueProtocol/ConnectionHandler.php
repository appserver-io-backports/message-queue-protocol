<?php

/**
 * TechDivision\MessageQueueProtocol\ConnectionHandler
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
 * @link      https://github.com/techdivision/TechDivision_PersistenceContainerClient
 * @link      http://www.appserver.io
 */

namespace TechDivision\MessageQueueProtocol;

use TechDivision\WebServer\Interfaces\ConnectionHandlerInterface;
use TechDivision\WebServer\Interfaces\ServerContextInterface;
use TechDivision\WebServer\Interfaces\WorkerInterface;
use TechDivision\WebServer\Sockets\SocketInterface;

/**
 * This is a connection handler to handle native persistence container requests.
 *
 * @category  Library
 * @package   TechDivision_MessageQueueProtocol
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_PersistenceContainerProtocol
 * @link      http://www.appserver.io
 */
class ConnectionHandler implements ConnectionHandlerInterface
{

    /**
     * The server context instance.
     *
     * @var \TechDivision\WebServer\Interfaces\ServerContextInterface
     */
    protected $serverContext;

    /**
     * The connection instance.
     *
     * @var \TechDivision\WebServer\Sockets\SocketInterface
     */
    protected $connection;

    /**
     * The worker instance.
     *
     * @var \TechDivision\WebServer\Interfaces\WorkerInterface
     */
    protected $worker;

    /**
     * Holds an array of modules to use for connection handler.
     *
     * @var array
     */
    protected $modules;

    /**
     * Inits the connection handler by given context and params
     *
     * @param \TechDivision\WebServer\Interfaces\ServerContextInterface $serverContext The servers context
     * @param array                                                     $params        The params for connection handler
     *
     * @return void
     */
    public function init(ServerContextInterface $serverContext, array $params = null)
    {

        // set server context
        $this->serverContext = $serverContext;

        // register shutdown handler
        register_shutdown_function(array(&$this, "shutdown"));
    }

    /**
     * Does shutdown logic for worker if something breaks in process.
     *
     * @return void
     */
    public function shutdown()
    {
        // get refs to local vars
        $connection = $this->getConnection();
        $worker = $this->getWorker();

        // check if connections is still alive
        if ($connection) {

            // close client connection
            $this->getConnection()->close();
        }

        // check if worker is given
        if ($worker) {
            // call shutdown process on worker to respawn
            $this->getWorker()->shutdown();
        }
    }

    /**
     * Handles the connection with the connected client in a proper way the given
     * protocol type and version expects for example.
     *
     * @param \TechDivision\WebServer\Sockets\SocketInterface    $connection The connection to handle
     * @param \TechDivision\WebServer\Interfaces\WorkerInterface $worker     The worker how started this handle
     *
     * @return bool Weather it was responsible to handle the firstLine or not.
     */
    public function handle(SocketInterface $connection, WorkerInterface $worker)
    {

        try {

            // add connection ref to self
            $this->connection = $connection;
            $this->worker = $worker;

            $container = $this->getContainer();

            // receive a line from the connection
            $buffer = '';
            while ($line = $connection->readLine()) {

                // if receive timeout occured
                if (strlen($line) === 0) {
                    break;
                }

                // append line to buffer
                $buffer .= $line;

                // check if data transmission has finished
                if (false === strpos($buffer, "\r\n")) {
                    break;
                }
            }

            // register the class loader
            $this->registerClassLoader();

            // extract the remote method to process
            $message = unserialize(base64_decode($buffer));

            // load class name and session ID from remote method
            $queue = $message->getDestination();
            $sessionId = $message->getSessionId();

            // load the referenced application from the server
            $application = $this->findApplication($queue);

            // lookup the message receiver and process the message
            $receiver = $application->locate($queue);

            // set container to receiver
            $receiver->setContainer($container);
            $receiver->onMessage($message, $sessionId);

        } catch (\Exception $e) {
            $response = $e;
        }

        // send the the result back to the client
        $connection->write(base64_encode(serialize($response)) . "\r\n");

        // finally close connection
        $connection->close();
    }

    /**
     * Returns the server context instance
     *
     * @return \TechDivision\WebServer\Interfaces\ServerContextInterface
     */
    public function getServerContext()
    {
        return $this->serverContext;
    }

    /**
     * Returns the connection used to handle with
     *
     * @return \TechDivision\WebServer\Sockets\SocketInterface
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * Returns the worker instance which starte this worker thread
     *
     * @return \TechDivision\WebServer\Interfaces\WorkerInterface
     */
    protected function getWorker()
    {
        return $this->worker;
    }

    /**
     * Returns the servers configuration
     *
     * @return \TechDivision\WebServer\Interfaces\ServerConfigurationInterface
     */
    public function getServerConfig()
    {
        return $this->getServerContext()->getServerConfig();
    }

    /**
     * Returns the container instance.
     *
     * @return \TechDivision\PersistenceContainer\Container The container instance
     */
    public function getContainer()
    {
        return $this->getServerContext()->getContainer();
    }

    /**
     * Returns the array with the available applications.
     *
     * @return array The available applications
     */
    public function getApplications()
    {
        return $this->getContainer()->getApplications();
    }

    /**
     * Injects all needed modules for connection handler to process
     *
     * @param array $modules An array of Modules
     *
     * @return void
     */
    public function injectModules($modules)
    {
        $this->modules = $modules;
    }

    /**
     * Returns all needed modules as array for connection handler to process
     *
     * @return array An array of Modules
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Returns the inital context instance.
     *
     * @return \TechDivision\ApplicationServer\InitialContext The initial context instance
     */
    protected function getInitialContext()
    {
        return $this->getContainer()->getInitialContext();
    }

    /**
     * Register the class loader again, because in a thread the context
     * lost all class loader information.
     *
     * @return void
     */
    protected function registerClassLoader()
    {
        $this->getInitialContext()->getClassLoader()->register(true, true);
    }

    /**
     * Tries to find and return the application for the passed application name.
     *
     * @param string $appName The name of the application to find and return the application instance
     *
     * @return \TechDivision\PersistenceContainer\Application The application instance
     * @throws \TechDivision\PersistenceContainer\Protocol\RemoteMethodCallException Is thrown if no application can be found for the passed class name
     */
    public function findApplication($appName)
    {

        // iterate over all classes and check if the application name contains the class name
        foreach ($this->getApplications() as $name => $application) {
            if ($application->hasQueue($queue)) {
                return $application;
            }
        }

        // if not throw an exception
        throw new \Exception("Can\'t find application for '" . $queue->getName() . "'");
    }
}
