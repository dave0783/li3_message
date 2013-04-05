<?php

namespace li3_message\extensions\storage;

class Message extends \lithium\core\StaticObject {

    /**
     * Class dependencies.
     *
     * @var array
     */
    protected static $_classes = array(
        'session' => 'lithium\storage\Session'
    );

    /**
     * Message types and their configurations.
     *
     * @var array
     */
    protected static $_types = array();

    /**
     * Sets or gets the configuration for a message type. All message types can have their own
     * configurations.
     *
     * @param string $name The name of the message type.
     * @param array [$config] The configuration options to set for a message type.
     * @return array Returns the configuration for a message type if $config is empty.
     */
    public static function type($name = null, array $config = array()) {
        if (!$name) {
            return static::$_types;
        }

        if (empty($config)) {
            return isset(static::$_types[$name]) ? static::$_types[$name] : null;
        }

        static::$_types[$name] = $config;
    }

    /**
     * Generic method for getting or setting different types of messages.
     *
     * Example usage:
     * {{{
     *      Message::error('auth.invalid', 'Incorrect username or pass');
     *      $error = Message::error('auth.invalid');
     *      $errors = Message::error('auth');
     *      $notices = Message::notice();
     * }}}
     *
     * @param string $name The message type
     * @param array $args The method arguments
     * @return array|boolean Array of messages on read or boolean on write
     */
    public static function __callStatic($name, $args) {
        $key = empty($args) ? $name : "{$name}.{$args[0]}";

        if (isset($args[1])) {
            $options = array();

            if ($type = static::type($name)) {
                extract($type);
            }

            $options = isset($args[2]) ? ($args[2] + $options) : $options;
            return static::write($args[1], $options, $key);
        }

        return static::read($key);
    }

    /**
     * Writes a message.
     *
     * @param string $message
     * @param array $options
     * @param string $key
     * @return boolean
     */
    public static function write($message, array $options = array(), $key = 'default') {
        $session = static::$_classes['session'];
        return $session::write("Message.{$key}", compact('message', 'options'), array('name' => 'default'));
    }

    /**
     * Reads a message.
     *
     * @param string $key
     * @return array
     */
    public static function read($key = 'default') {
        $session = static::$_classes['session'];
        return $session::read("Message.{$key}", array('name' => 'default'));
    }

    /**
     * Clears one or all messages from the storage.
     *
     * @param string $key Optional key. Set this to `null` to delete all flash messages.
     * @return void
     */
    public static function clear($key = 'default') {
        $session = static::$_classes['session'];
        $sessionKey = 'Message';
        if (!empty($key)) {
            $sessionKey .= ".{$key}";
        }
        return $session::delete($sessionKey, array('name' => 'default'));
    }

}
