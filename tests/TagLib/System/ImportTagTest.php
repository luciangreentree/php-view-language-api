<?php
namespace Test\Lucinda\Templating\TagLib\System;

use Lucinda\Templating\TagLib\System\ImportTag;
use Lucinda\Templating\ViewCompilation;
use Lucinda\Templating\TagLib\System\EscapeTag;
use Lucinda\UnitTest\Result;

class ImportTagTest
{
    public function parse()
    {
        $object = new ImportTag(dirname(__DIR__, 2)."/views", "html", new ViewCompilation(dirname(__DIR__, 2)."/compilations", "homepage", "html"));
        return new Result($object->parse("homepage", new EscapeTag())=='<html>
<body>
Welcome to homepage!
</body>
</html>');
    }
}
