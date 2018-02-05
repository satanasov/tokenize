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
			array('config.add', array('tokenize_use_forums', '190')),
			array('config.add', array('tokenize_post_rate', '5')),
			array('config.add', array('tokenize_bump_rate', '1')),

			// Add permission
			array('permission.add', array('a_tokenize', true)),
			// Set permissions
			array('permission.permission_set', array('ROLE_ADMIN_FULL', 'a_tokenize')),
			array('permission.permission_set', array('ROLE_ADMIN_STANDARD', 'a_tokenize')),

			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_TOKENIZE')),
			array('module.add', array(
				'acp', 'ACP_TOKENIZE', array(
					'module_basename'	=> '\anavaro\tokenize\acp\tokenize_module',
					'modes'				=> array('config'),
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