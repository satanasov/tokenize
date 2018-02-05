<?php
/**
 *
 * Tokenize extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 Lucifer <http://www.anavaro.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace anavaro\tokenize\event;

/**
 * Event listener
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\user  */
	protected $user;

	/** @var \phpbb\config\config  */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface  */
	protected $db;

	/** @var \phpbb\template\template */
	protected $template;

	static public function getSubscribedEvents()
	{
		return array(
			'core.modify_submit_post_data'	=> 'posting_and_replaying',
			'core.modify_posting_parameters'	=> 'bump',
			'core.memberlist_view_profile'	       => 'user_profile_tokens',
		);
	}

	public function __construct(\phpbb\user $user, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db,
								\phpbb\template\template $template)
	{
		$this->user = $user;
		$this->config = $config;
		$this->db = $db;
		$this->template = $template;
	}

	/**
	 * Posting new and replaying to bump own topics
	 * @param $event
	 */
	public function posting_and_replaying($event)
	{
		$forums = explode(',', $this->config['tokenize_use_forums']);
		if (in_array($event['data']['forum_id'], $forums))
		{
			if ($event['mode'] == 'post')
			{
				if ($this->user->data['tokenize_cur'] < $this->config['tokenize_post_rate'])
				{
					trigger_error('not enough tokens');
				}
				$this->pay('post');
			}
			if (($event['mode'] == 'reply' || $event['mode'] == 'quote'))
			{
				$sql = 'SELECT * FROM ' . POSTS_TABLE . ' WHERE post_id = ' . (int) $event['data']['topic_last_post_id'];
				$result = $this->db->sql_query($sql);
				$last_poster = $this->db->sql_fetchfield('poster_id', $result);
				$this->db->sql_freeresult($result);
				if ($last_poster == $this->user->data['user_id'])
				{
					if ($this->user->data['tokenize_cur'] < $this->config['tokenize_bump_rate'])
					{
						trigger_error('not enough tokens');
					}
					$this->pay('bump');
				}
			}
		}
	}

	/**
	 * Bump topics
	 * @param $event
	 */
	public function bump($event)
	{
		$forums = explode(',', $this->config['tokenize_use_forums']);
		if (in_array($event['forum_id'], $forums))
		{
			if ( $event['mode'] == 'bump')
			{
				if ($this->user->data['tokenize_cur'] < $this->config['tokenize_bump_rate'])
				{
					trigger_error('not enough tokens');
				}
				$this->pay('bump');
			}
		}
	}


	public function user_profile_tokens($event)
	{
		$sql = 'SELECT * FROM ' . USERS_TABLE . ' WHERE user_id = ' . (int) $event['member']['user_id'];
		$result = $this->db->sql_query($sql);
		$user_info = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		$this->template->assign_vars(array(
			'U_TOKENS_CUR'	=> $user_info['tokenize_cur']
		));
	}

	private function pay($action)
	{
		$sum = 0;
		switch ($action)
		{
			case 'bump':
				$sum = (int) $this->config['tokenize_bump_rate'];
			break;
			case 'post':
				$sum = (int) $this->config['tokenize_post_rate'];
		}
		$sql = 'UPDATE '. USERS_TABLE . ' SET tokenize_cur = ' . ((int) $this->user->data['tokenize_cur'] - (int) $sum) . ' WHERE user_id = ' . $this->user->data['user_id'];
		$this->db->sql_query($sql);
	}
}