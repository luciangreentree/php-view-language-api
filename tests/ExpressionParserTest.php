<?php
namespace Test\Lucinda\Templating;

use Lucinda\Templating\ExpressionParser;
use Lucinda\UnitTest\Result;

class ExpressionParserTest
{
    public function parse()
    {
        $parser = new ExpressionParser();
        return new Result($parser->parse('<p>${asd.fgh}</p>')=='<p><?php echo $asd["fgh"]; ?></p>');
    }
}
