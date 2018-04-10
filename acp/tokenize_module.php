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

/**
* @package acp
*/

class tokenize_module
{
	protected $db;
	protected $config;
	protected $template;
	protected $request;
	
	var $u_action;

	function main($id, $mode)
	{
		$this->tpl_name = 'acp_tokenize';
		
		switch ($mode)
		{
			case 'config':
				$this->config();
			break;
		}
	}
	
	private function config()
	{
		global $phpbb_container, $config, $template, $request;
		
		$this->config = $config;
		$this->template = $template;
		$this->request = $request;
		
		// Get variables
		$use_forums = $this->request->variable('tokenize_use_forums', $this->config['tokenize_use_forums']);
		$post_rate = $this->request->variable('tokenize_post_rate', $this->config['tokenize_post_rate']);
		$bump_rate = $this->request->variable('tokenize_bump_rate', $this->config['tokenize_bump_rate']);
		
		// We need a sanity checkdate
		if(!preg_match('/^[0-9 ,]*$/', $use_forums))
		{
			trigger_error('INVALID_INPUT');
		}
		
		if(!is_numeric($post_rate) || !is_numeric($bump_rate))
		{
			trigger_error('INVALID_INPUT');
		}
		
		$this->config['tokenize_use_forums'] = $use_forums;
		$this->config['tokenize_post_rate'] = $post_rate;
		$this->config['tokenize_bump_rate'] = $bump_rate;
		
		$this->page_title = 'ACP_TOKENIZE_CONFIG';
		
		$this->template->assign_vars(array(
			'S_TOKENIZE_USE_FORUM'	=> $this->config['tokenize_use_forums'],
			'S_TOKENIZE_POST_RATE'	=> $this->config['tokenize_post_rate'],
			'S_TOKENIZE_BUMP_RATE'	=> $this->config['tokenize_bump_rate'],
		));
		
		
	}
}
