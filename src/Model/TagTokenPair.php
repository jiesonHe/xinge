<?php

namespace Gutplushe\ApnsPHP\Model;

class TagTokenPair {

    public function __construct($tag, $token)
    {
        $this->tag = strval($tag);
        $this->token = strval($token);
    }
    public function __destruct(){}

    public $tag;
    public $token;
}