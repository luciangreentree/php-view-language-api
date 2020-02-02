<?php
namespace Test\Lucinda\Templating\TagLib\System;

use Lucinda\Templating\TagLib\System\EscapeTag;
use Lucinda\UnitTest\Result;

class EscapeTagTest
{
    private $object;
    
    public function __construct()
    {
        $this->object = new EscapeTag();
    }

    public function backup()
    {
        $result = $this->object->backup('asd <escape>${qwe}</escape> fgh');
        return new Result($result=="asd <bkp>0</bkp> fgh");
    }
        

    public function restore()
    {
        return new Result($this->object->restore("asd <bkp>0</bkp> fgh")=='asd ${qwe} fgh');
    }
}
