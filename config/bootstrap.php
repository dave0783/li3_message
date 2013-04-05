<?php

use li3_message\extensions\storage\Message;

Message::type('default', array(
    'template' => '<div{:options}><button class="close" data-dismiss="alert" href="#">×</button>{:message}</div>',
    'options'  => array('class' => 'alert'),
));

Message::type('info', array(
    'options' => array('class' => 'alert alert-info'),
));

Message::type('error', array(
    'options' => array('class' => 'alert alert-error'),
));

Message::type('notice', array(
    'options' => array('class' => 'alert alert-notice'),
));

Message::type('success', array(
    'options' => array('class' => 'alert alert-success'),
));

