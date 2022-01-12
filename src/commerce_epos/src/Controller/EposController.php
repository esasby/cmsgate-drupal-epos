<?php

namespace Drupal\commerce_epos\Controller;

require_once(dirname(dirname(__FILE__)) . '/init.php');

use esas\cmsgate\epos\controllers\ControllerEposCallback;
use esas\cmsgate\utils\Logger;
use Exception;
use Throwable;

class EposController
{
    public function callback()
    {
        try {
            $controller = new ControllerEposCallback();
            $controller->process();
        } catch (Throwable $e) {
            Logger::getLogger("callback")->error("Exception:", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("callback")->error("Exception:", $e);
        }
    }
}