<?php
namespace Test\Lucinda\Templating;

use Lucinda\Templating\TagExpressionParser;
use Lucinda\UnitTest\Result;

class TagExpressionParserTest
{
    public function parse()
    {
        $object = new TagExpressionParser();
        return new Result($object->parse('asd <:if test="${qwe.rty}"/>fgh</if> jkl') == 'asd <:if test="$qwe["rty"]"/>fgh</if> jkl');
    }
}
