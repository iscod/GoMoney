<?php
$this->setMethod('GET')->setUri('')->setAction('Welcome/index')->addDefaultRoute();
$this->setMethod('GET')->setUri('Welcome/index')->setAction('Welcome/index')->addRoute();

