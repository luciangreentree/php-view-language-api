<?php
class UserInfoTag extends AbstractTag implements StartTag {
	public function parseStartTag($parameters=array()) {
		$this->checkParameters($parameters, array("id"));
		return 'Name: ${names.'.$parameters["id"].'}<br/>';
	}
}