<?php
namespace Test\Lucinda\Templating;

use Lucinda\Templating\AttributesParser;
use Lucinda\UnitTest\Result;

class AttributesParserTest
{
    public function parse()
    {
        $object = new AttributesParser();
        return new Result($object->parse('asd="fgh" qwe="rty"')==["asd"=>"fgh", "qwe"=>"rty"]);
    }
}
