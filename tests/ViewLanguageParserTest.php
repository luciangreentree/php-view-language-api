<?php
namespace Test\Lucinda\Templating;

use Lucinda\Templating\ViewLanguageParser;
use Lucinda\UnitTest\Result;

class ViewLanguageParserTest
{
    public function compile()
    {
        $object = new ViewLanguageParser(__DIR__."/views", "html", __DIR__."/compilations", __DIR__."/tags");
        $compilationPath = $object->compile("test");
        return new Result(file_get_contents($compilationPath)=='<h1>Hello, Lucian!</h1>
<ul>
    <?php foreach ($data["users"] as $user) { ?>
    <li><?php echo $user; ?></li>
    <?php } ?>
</ul>');
    }
}
