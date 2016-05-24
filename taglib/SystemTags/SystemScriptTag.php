<?php
/**
 * Skips parsing tags or expressions in HTML-standard <script ...> tags.
 * ATTENTION: In nearly all cases, hardcoding JAVASCRIPT into template files is bad design.
 *
 * Tag syntax:
 * <script>JAVASCRIPT</script>
 *
 * Tag example:
 * <script>var asd = 'sd ${asd}'</script>
 *
 * PHP output:
 * <script>var asd = 'sd ${asd}'</script>
 */
class SystemScriptTag extends AbstractNonParsableTag  {
	public function __construct() {
		$this->strTagName = "script";
		$this->blnKeepTag = true;
	}
}