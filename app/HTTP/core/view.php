<?php
class View
{


	function generate($content_view, $template_view, $data = null)
	{
		include _views.$template_view;
	}
}