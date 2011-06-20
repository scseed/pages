<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div id="rean_in_another_lang">
<?php foreach($page_contents as $page_content):?>
<?php echo HTML::anchor(
		Request::current().URL::query(array('lang' => $page_content->lang->abbr)),
		__('read in :language', array(':language' => $page_content->lang->locale_name))
	)?>
<?php endforeach?>
</div>

<?php echo $textile->TextileThis($page->content)?>