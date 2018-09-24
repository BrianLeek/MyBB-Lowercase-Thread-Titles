<?php
/**
 * Plugin Name: Lowercase Thread Titles
 * Description: Converts thread titles to all lowercase letters.
 * Author: Brian. ( https://community.mybb.com/user-115119.html )
 * Version: 1.2
 * File: lowercase.php
**/
 
if(!defined("IN_MYBB"))
{
    	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("datahandler_post_insert_thread", "lowercase_newthreads");
$plugins->add_hook("datahandler_post_update_thread", "lowercase_editthreads");

function lowercase_info()
{
	return array(
		"name"			=> "Lowercase Thread Titles",
		"description"	=> "Converts thread titles to all lowercase letters.",
		"website"		=> "https://community.mybb.com/user-115119.html",
		"author"		=> "Brian.",
		"authorsite"	=> "https://community.mybb.com/user-115119.html",
		"version"		=> "1.2",
		"compatibility" => "16*,18*"
	);
}

function lowercase_activate()
{
	global $db;
	$lowercase_settingsgroup = array(
		"gid"    => "0",
		"name"  => "lowercase_settingsgroup",
		"title"      => "Lowercase Titles Settings",
		"description"    => "These options allow you to set the plugin to use all lowercase letters for thread title\'s.",
		"disporder"    => "1",
		"isdefault"  => "0",
	);

	$db->insert_query("settinggroups", $lowercase_settingsgroup);
	$gid = $db->insert_id();
	$lowercase_capitalthreads = array(
		"sid"            => "0",
		"name"        => "lowercase_capitalthreads",
		"title"            => "Use all lowercase letters in thread title\'s",
		"description"    => "If you would like to use all lowercase letters in thread title\'s, select yes below.",
		"optionscode"    => "yesno",
		"value"        => "1",
		"disporder"        => "1",
		"gid"            => intval($gid),
	);
	
	$db->insert_query("settings", $lowercase_capitalthreads);
  	rebuild_settings();
	
}

function lowercase_newthreads($datahandler)
{
	global $mybb, $db;
		if ($mybb->settings['lowercase_capitalthreads'] == 1)
		{
			$datahandler->thread_insert_data['subject'] = strtolower($datahandler->thread_insert_data['subject']);
		}
}

function lowercase_editthreads($datahandler)
{
	global $mybb, $db;
		if ($mybb->settings['lowercase_capitalthreads'] == 1 && $datahandler->thread_update_data['subject'])
		{
			$datahandler->thread_update_data['subject'] = strtolower($datahandler->thread_update_data['subject']);
		}
}

function lowercase_deactivate()
{
	global $db;
		$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('lowercase_capitalposts', 'lowercase_settingsgroup')");
		$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('lowercase_capitalthreads', 'lowercase_settingsgroup')");
		$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='lowercase_settingsgroup'");
		rebuild_settings();
}
?>
