<?php

require_once('config.php');
require_once('autoload.php');

session_start();

Router::route();