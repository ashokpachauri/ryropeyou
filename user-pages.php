<?php
	$module="";
	$username="";
	if(isset($_REQUEST['__module']))
	{
		$module=$_REQUEST['__module'];
	}
	if(isset($_REQUEST['__username']))
	{
		$username=$_REQUEST['__username'];
	}
	if($username!="")
	{
		if($module=="connections" )
		{
			include_once 'view_users_connections.php';
		}
		else if($module=="profile-views" )
		{
			include_once 'profile-views.php';
		}
		else if($module=="mutual-connections" )
		{
			include_once 'view-user-mutual-connections.php';
		}
		else if($module=="gallery")
		{
			include_once 'view_users_gallery.php';
		}
		else if($module=="" || $module=="profile")
		{
			include_once 'user-profile.php';
		}
        else
        {
            include_once '404.php';
        }
	}
	else
	{
		include_once '404.php';
	}
?>