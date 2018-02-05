<?php
/**
 *
 * Tokenize extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 Lucifer <http://www.anavaro.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace anavaro\tokenize\acp;

class tokenize_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container;

		var_dump($id);
		var_dump($mode);
	}
}
