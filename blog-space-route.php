<?php
	include_once 'connection.php';
	$_page=false;
	$_function=false;
	$_sub_function=false;
	$_extra=false;
	if(isset($_REQUEST['_page']) && $_REQUEST['_page']!=""){ $_page=$_REQUEST['_page']; }
	if(isset($_REQUEST['_function']) && $_REQUEST['_function']!=""){ $_function=$_REQUEST['_function']; }
	if(isset($_REQUEST['_sub_function']) && $_REQUEST['_sub_function']!=""){ $_sub_function=$_REQUEST['_sub_function']; }
	if(isset($_REQUEST['_extra']) && $_REQUEST['_extra']!=""){ $_extra=$_REQUEST['_extra']; }
	if(!$_page || $_page=="" || $_page=="index")
	{
		include_once 'blog-space.php';
		die();
	}
	else if($_page=="create-blog-space")
	{
		include_once "create-blog-space.php";
		die();
	}
	else
	{
		$space_url=$_page;
		if(isValidSpace($space_url))
		{
			if($_function!="")
			{
				if($_function=="write-post")
				{
					include_once 'write-post.php';
				}
				else
				{
					$post_url=$_function;
					if(isValidSpacePost($post_url))
					{
						if($_sub_function!="")
						{
							if($_sub_function=="edit")
							{
								include_once 'edit-blog-post.php';
							}
						}
						else
						{
							include_once "blog-space-post-page.php";
						}
					}
					else
					{
						include_once '404.php';
						//echo $post_url;
					}
				}
			}
			else
			{
				include_once "blog-space-page.php";
			}
		}
		else
		{
			include_once '404.php';
		}
	}
?>