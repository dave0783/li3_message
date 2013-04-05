<?php

namespace li3_message\tests\cases\extensions\storage;

use \li3_message\extensions\storage\Message;
use \lithium\storage\Session;

class MessageTest extends \lithium\test\Unit {

    public function setUp() {
        Session::config(array('default' => array('adapter' => 'Memory')));
    }

    public function tearDown() {
        Session::delete('default');
    }

    public function testWrite() {
        Message::write('Foo');
        $expected = array('message' => 'Foo', 'atts' => array());
        $result = Session::read('Message.default', array('name' => 'default'));
        $this->assertEqual($expected, $result);

        Message::write('Foo 2', array('type' => 'notice'));
        $expected = array('message' => 'Foo 2', 'atts' => array('type' => 'notice'));
        $result = Session::read('Message.default', array('name' => 'default'));
        $this->assertEqual($expected, $result);

        Message::write('Foo 3', array(), 'TestKey');
        $expected = array('message' => 'Foo 3', 'atts' => array());
        $result = Session::read('Message.TestKey', array('name' => 'default'));
        $this->assertEqual($expected, $result);
    }

    public function testWriteCustomType() {
        Message::notice('key', 'value');
        $expected = array('message' => 'value', 'atts' => array());
        $result = Session::read('Message.notice.key');
        $this->assertEqual($expected, $result);

        Message::error('key', 'value', array('class' => 'error'));
        $expected = array('message' => 'value', 'atts' => array('class' => 'error'));
        $result = Session::read('Message.error.key');
        $this->assertEqual($expected, $result);
    }

    public function testRead() {

    }

    public function testClear() {

    }

}
