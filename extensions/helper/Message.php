<?php

namespace li3_message\extensions\helper;

use li3_message\extensions\storage\Message as Storage;

class Message extends \lithium\template\Helper {

    protected $_strings = array(
        'default' => '<div{:options}>{:message}</div>'
    );

    protected $_config = array(
        'default' => array(),
    );

    protected function _init() {
        parent::_init();
        $this->config(Storage::type());
    }

    /**
     * Sets up the default strings and configuration options for different message types.
     *
     * @param array $config
     */
    public function config(array $config = array()) {
        foreach ($config as $type => &$options) {
            if (isset($options['template'])) {
                $this->_classes[$type] = $options['template'];
                unset($options['template']);
            }
        }
        unset($options);

        $this->_config = $config + $this->_config;
    }

    /**
     * Retrieves and renders a message or array of messages by custom type.
     *
     * {{{
     *      <?= $this->message->error(); ?>
     *      <?= $this->message->notice('user'); ?>
     *      <?= $this->message->warning('system.warnings'); ?>
     * }}}
     *
     * @param string $name
     * @param array $args
     * @return string
     */
    public function __call($name, $args) {
        $self = $this;
        $template = 'default';

        if ($name == 'read') $name = 'default';
        if (isset($this->_classes[$name])) $template = $name;

        $template = $this->_classes[$template];

        $filter = function($message) use(&$filter, $self, $template, $name) {
            $return = array();

            if (array_key_exists('message', $message)) {
                $return[] = $self->invokeMethod('_render', array($name, $template, $message));
            } else {
                foreach($message as $m) {
                    $return[] = $filter($m);
                }
            }
            return implode("\n", $return);
        };

        $messages = Storage::__callStatic($name, $args);
        Storage::clear($name);
        return $messages ? $filter($messages) : '';
    }
}
