<?php
namespace Test\Lucinda\Templating;

use Lucinda\Templating\SystemTagParser;
use Lucinda\UnitTest\Result;

class SystemTagParserTest
{
    public function parse()
    {
        $results = [];
        
        $object = new SystemTagParser();
        
        $results[] = new Result($object->parse('asd <:set var="zxc" val="vbn"/> fgh') == "asd <?php \$zxc = 'vbn'; ?> fgh", "start tag test");
        $results[] = new Result($object->parse('asd <:foreach var="${data.users}" val="user">${user}</:foreach> fgh') == 'asd <?php foreach ($data["users"] as $user) { ?>${user}<?php } ?> fgh', "start end tag test");
        
        return $results;
    }
}
