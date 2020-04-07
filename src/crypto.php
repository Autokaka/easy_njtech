<?php

class Crypto
{
    private static $instance;
    
    public static function getInstance() {
    	if ($instance == null) $instance = new Crypto();
    	return $instance;
    }
    
    public function decrypt($data) {
    	return base64_decode($data);
    }
}

?>