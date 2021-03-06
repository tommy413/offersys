<?php
namespace App\Action;

use Interop\Container\ContainerInterface;

error_reporting(0);

session_start();

class PermitAction extends BaseAction
{
	protected $account;

    public function __construct(ContainerInterface $container)
    {
    	parent::__construct($container);

    	$account = $_SESSION['account'];
    	$timestamp = $_SESSION['timestamp'];
    	$token = $_SESSION['token'];

    	if (time() - $timestamp > 360000 || password_verify("$account:$timestamp", $token) === false) {
    		header("Location: /offersys/test/public/login");
    	}

    	$this->account = $account;
    }

}
