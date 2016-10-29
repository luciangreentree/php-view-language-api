<?php
class TestMineTag extends AbstractParsableTag {
	public function parseStartTag($tblParameters=array()){
		return '
				<standard:foreach var="'.$tblParameters["id"].'" value="c">
				${c}
				</standard:foreach>
				';
	}
	
	public function parseEndTag() {
		return "";
	}
}