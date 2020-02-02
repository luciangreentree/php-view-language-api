<?php
namespace Test\Lucinda\Templating;

use Lucinda\Templating\UserTag;
use Lucinda\UnitTest\Result;

class UserTagTest
{
    public function parseStartTag()
    {
        $object = new UserTag(__DIR__."/tags/Greeting/client.html");
        return new Result($object->parseStartTag(["user"=>"Lucian"])=="Hello, Lucian!");
    }
}
