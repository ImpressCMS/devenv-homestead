<?php
/**
 * View and manage your private messages
 * 
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @subpackage	Privmessage
 * @version		SVN: $Id: viewpmsg.php 11010 2011-02-05 22:43:30Z skenow $
 */
$xoopsOption['pagetype'] = 'pmsg';
include_once 'mainfile.php';
$module_handler = icms::handler('icms_module');

if (!is_object(icms::$user)) {
	$errormessage = _PM_SORRY . '<br />' . _PM_PLZREG . '';
	redirect_header('user.php', 2, $errormessage);
} else {
	$pm_handler = icms::handler('icms_data_privmessage');
	if (isset($_POST['delete_messages']) && isset($_POST['msg_id'])) {
		if (!icms::$security->check()) {
			echo implode('<br />', icms::$security->getErrors());
			exit();
		}
		$size = count($_POST['msg_id']);
		$msg =& $_POST['msg_id'];
		for ($i = 0; $i < $size; $i++) {
			$pm =& $pm_handler->get($msg[$i]);
			if ($pm->getVar('to_userid') == icms::$user->getVar('uid')) {
				$pm_handler->delete($pm);
			}
			unset($pm);
		}
		redirect_header('viewpmsg.php', 1, _PM_DELETED);
	}
	$xoopsOption['template_main'] = 'system_pm_inbox.html';
	include ICMS_ROOT_PATH . '/header.php';
	$criteria = new icms_db_criteria_Item('to_userid', (int) (icms::$user->getVar('uid')));
	$criteria->setOrder('DESC');
	$pm_arr =& $pm_handler->getObjects($criteria);
	
	$pm_data = array(
		'lang' => array(
			'pm' => _PM_PRIVATEMESSAGE
			, 'profile' => _PM_PROFILE
			, 'inbox' => _PM_INBOX
			, 'from' => _PM_FROM
			, 'subject' => _PM_SUBJECT
			, 'date' => _PM_DATE
			, 'none' => _PM_YOUDONTHAVE
			, 'unread' => _PM_NOTREAD
			, 'send' => _PM_SEND
			, 'delete' => _PM_DELETE
		)
		, 'token' => icms::$security->getTokenHTML()
		, 'msgs' => array()
	);

	$total_messages = count($pm_arr);
	$pm_data['show'] = $total_messages < 1 ? 0 : 1;

	// populate $pm_data['msgs'] array
	for ($i = 0; $i < $total_messages; $i++) {
		$postername = icms_member_user_Object::getUnameFromId($pm_arr[$i]->getVar('from_userid'));
		$pm_data['msgs'][] = array(
			'id' => $pm_arr[$i]->getVar('msg_id')
			, 'read' => $pm_arr[$i]->getVar('read_msg')
			, 'img' => $pm_arr[$i]->getVar('msg_image', 'E')
			, 'from' => array(
				'userId' => $postername ? (int) ($pm_arr[$i]->getVar('from_userid')) : 0
				, 'userName' => $postername ? $postername : $icmsConfig['anonymous']
			)
			, 'link' => 'readpmsg.php?start=' . (int) (($total_messages-$i-1)) . '&amp;total_messages=' . (int) $total_messages
			, 'subject' => $pm_arr[$i]->getVar('subject')
			, 'time' => formatTimestamp($pm_arr[$i]->getVar('msg_time'))
		);
	}

	icms_makeSmarty($pm_data);
	include 'footer.php';
}