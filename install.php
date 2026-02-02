<?php

require_once('config.php');
require_once('autoload.php');

DbInstall::install();

echo "Install done";