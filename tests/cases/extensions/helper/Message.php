<?php

namespace li3_message\tests\cases\extensions\helper;

use li3_message\extensions\helper\Message;

use lithium\net\http\Router;
use lithium\action\Request;
use lithium\action\Response;
use lithium\tests\mocks\template\MockRenderer;

class MessageTest extends \lithium\test\Unit {

    public $message;

    protected $_routes = array();

    /**
     * Initialize test by creating a new object instance with a default context.
     */
    public function setUp() {
        $this->_routes = Router::get();
        Router::reset();
        Router::connect('/{:controller}/{:action}/{:id}.{:type}');
        Router::connect('/{:controller}/{:action}.{:type}');

        $this->context = new MockRenderer(array(
            'request' => new Request(array(
                'base' => '', 'env' => array('HTTP_HOST' => 'foo.local')
            )),
            'response' => new Response()
        ));
        $this->message = new Message(array('context' => &$this->context));
    }

    /**
     * Clean up after the test.
     */
    public function tearDown() {
        Router::reset();

        foreach ($this->_routes as $route) {
            Router::connect($route);
        }
        unset($this->message);
    }


}

