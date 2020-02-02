<?php
namespace Test\Lucinda\Templating;

use Lucinda\Templating\UserTagParser;
use Lucinda\Templating\ViewCompilation;
use Lucinda\Templating\TagLib\System\NamespaceTag;
use Lucinda\Templating\TagLib\System\EscapeTag;
use Lucinda\UnitTest\Result;

class UserTagParserTest
{
    public function parse()
    {
        $object = new UserTagParser(new NamespaceTag(__DIR__."/tags"), "html", new ViewCompilation(__DIR__."/compilations", "userTagParser", "html"));
        return new Result($object->parse('<p><Greeting:client user="Lucian"/></p>', new EscapeTag())=="<p>Hello, Lucian!</p>");
    }
}
