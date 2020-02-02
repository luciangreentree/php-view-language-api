<?php
namespace Test\Lucinda\Templating;
    
use Lucinda\Templating\Wrapper;
use Lucinda\UnitTest\Result;

class WrapperTest
{

    public function compile()
    {
        $object = new Wrapper(\simplexml_load_string('
        <xml>
            <templating compilations_path="'.__DIR__.'/compilations" tags_path="'.__DIR__.'/tags" templates_path="'.__DIR__.'/views" templates_extension="html"/>
        </xml>'), "test");
        return new Result($object->compile(["users"=>["John Doe", "Jane Doe"]])=='<h1>Hello, Lucian!</h1>
<ul>
        <li>John Doe</li>
        <li>Jane Doe</li>
    </ul>');
    }
        

}
