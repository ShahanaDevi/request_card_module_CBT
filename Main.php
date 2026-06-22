<?php

require_once 'CardRequest.php';

class Main
{
	public function start()
	{
		$cardRequest = new CardRequest();
		$cardRequest->run();
	}
}

$obj = new Main();
$obj->start();
