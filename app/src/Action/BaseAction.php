<?php
namespace App\Action;

use Interop\Container\ContainerInterface;

//error_reporting(0);

class BaseAction
{
    
    public function __construct(ContainerInterface $container)
    {
        session_start();
        $this->view = $container->get('view');
        $this->logger = $container->get('logger');
        $this->sql = $container->get('sql');
    }

    public function __destruct()
    {
        foreach ($this->sql as $sql) {
            $sql = null;
        }
    }
}
