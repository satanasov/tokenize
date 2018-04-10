<?php
/**
 *
 * Tokenize extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014 Lucifer <http://www.anavaro.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace anavaro\tokenize\migrations;
/**
 * Primary migration
 */
class release_1_0_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
		array('permission.add', array('a_anavaro_tokenize', true, 'a_board')),
			array('config.add', array('tokenize_use_forums', '190')),
			array('config.add', array('tokenize_post_rate', '5')),
			array('config.add', array('tokenize_bump_rate', '1')),

			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_TOKENIZE_GRP')),
			array('module.add', array(
				'acp', 'ACP_TOKENIZE_GRP', array(
					'module_basename'	=> '\anavaro\tokenize\acp\tokenize_module',
					'module_langname'	=> 'TOKINIZE_CONFIG',
					'module_mode'		=> 'config',
					'module_auth'		=> 'ext_anavaro/tokenize && acl_a_anavaro_tokenize',
				),
			)),
		);
	}

	//Creating additional user field
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				USERS_TABLE	=> array(
					'tokenize_cur'		=> array('UINT:5', 10),
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				USERS_TABLE	=> array(
					'tokenize_cur'
				),
			),
		);
	}
}