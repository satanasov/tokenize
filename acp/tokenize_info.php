<?php
/**
 *
 * Tokenize extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014 Lucifer <http://www.anavaro.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace anavaro\tokenize\acp;

class tokenize_info
{
	public function module()
	{
		return array(
			'filename'	=> '\anavaro\tokenize\acp\tokenize_module',
			'title'		=> 'ACP_TOKENIZE',
			'modes'		=> array(
				'config'	=> array(
					'title' => 'ACP_TOKENIZE_CONFIG',
					'auth' => 'ext_phpbb/tokenize && acl_a_tokenize',
					'cat' => array('ACP_TOKENIZE')
				),
			),
		);
	}
}