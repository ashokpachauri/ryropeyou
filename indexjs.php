<?php
	include_once 'connection.php';
?>
<script>
	var base_url="<?php echo base_url; ?>";
		  var googleUser = {};
		  var startApp = function() {
			gapi.load('auth2', function(){
			  // Retrieve the singleton for the GoogleAuth library and set up the client.
			  auth2 = gapi.auth2.init({
				client_id: '940004341323-ubu6e063ut7bafuosk2952k8s84nenvs.apps.googleusercontent.com',
				cookiepolicy: 'single_host_origin',
				// Request scopes in addition to 'profile' and 'email'
				//scope: 'additional_scope'
			  });
			  attachSignin(document.getElementById('customBtn'));
			  attachSignin(document.getElementById('customBtn1'));
			});
		  };

		  function attachSignin(element) {
			//console.log(element.id);
			auth2.attachClickHandler(element, {},
				function(googleUser) {
				  var email=googleUser.getBasicProfile().getEmail();
				  var name=googleUser.getBasicProfile().getFamilyName();
				  var image=googleUser.getBasicProfile().getImageUrl();
				  var id=googleUser.getBasicProfile().getId();
				  var full_name=googleUser.getBasicProfile().getName();
				  var data={email:email,name:name,image:image,id:id,full_name:full_name};
				  //console.log(data);
				  $.ajax({
					  url:'<?php echo base_url; ?>webservices/get-google-signin.php',
					  type:'post',
					  data:data,
					  success:function(result){
						if(result=="SUCCESS")
						{
							window.location.href='<?php echo base_url; ?>onboarding';
						}
						else
						{
							swal({
							  title: "Oh!, Snap",
							  text: "Something went wrong please try again.",
							  icon: "error",
							  buttons: {
								cancel: false,
								confirm: "Close",
							  },
							  dangerMode: false,
							});
						}
					  },
					  error:function(error){
							swal({
							  title: "Oh!, Snap",
							  text: "Something went wrong please try again.",
							  icon: "error",
							  buttons: {
								cancel: false,
								confirm: "Close",
							  },
							  dangerMode: false,
							});
					  }
				  });
				}, function(error) {
				  //alert(JSON.stringify(error, undefined, 2));
				});
			}
		startApp();
	</script>
	<script>
		$('ul.dropdown-menu li').hover(function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
		}, function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
		});
	</script>
<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$(".scroll, .navbar li a, .footer li a").click(function (event) {
				$('html,body').animate({
					scrollTop: $(this.hash).offset().top
				}, 1000);
			});
			<?php
			if(isset($_SESSION['mesg_type']) && $_SESSION['mesg_type']!='')
			{
				if($_SESSION['mesg_type']=="error")
				{
					?>
					swal({
					  title: "Dear, <?php echo $_SESSION['u_name']; ?>",
					  text: "<?php echo $_SESSION['mesg']; ?>",
					  icon: "<?php echo $_SESSION['mesg_type']; ?>",
					  buttons: {
						cancel: false,
						confirm: "Close",
					  },
					  dangerMode: false,
					});
					/*.then((willDelete) => {
					  if (willDelete) {
						swal("Poof! Your imaginary file has been deleted!", {
						  icon: "success",
						});
					  } else {
						swal("Your imaginary file is safe!");
					  }
					});*/
					<?php
					session_destroy();
					session_start();
				}
			}
			?>
		});
	</script>
	<script type="text/javascript">
	var user_login_button_html=$("#user_login_button").text();
	function login_div_show()
	{
		if(user_login_button_html=="Login")
		{	
			user_login_button_html="Signup";
			$("#user_login_button").html(user_login_button_html);
			$("#register_div").hide();
			$("#forgot_div").hide();
			$("#login_div").show();
		}
		else
		{
			user_login_button_html="Login";
			$("#user_login_button").html(user_login_button_html);
			$("#login_div").hide();
			$("#forgot_div").hide();
			$("#register_div").show();
		}
	}
	function register_div_show()
	{
		if(user_login_button_html=="Login")
		{	
			user_login_button_html="Signup";
			$("#user_login_button").html(user_login_button_html);
			$("#register_div").hide();
			$("#forgot_div").hide();
			$("#login_div").show();
		}
		else
		{
			user_login_button_html="Login";
			$("#user_login_button").html(user_login_button_html);
			$("#login_div").hide();
			$("#forgot_div").hide();
			$("#register_div").show();
		}
	}
	$(document).ready(function () {

		$().UItoTop({
			easingType: 'easeOutQuart'
		});

	});
	var logged_out=0;
	function statusChangeCallback(response) {
		if (response.status === 'connected') {
		  if(logged_out!=0)
		  {
			 testAPI();
		  }
		  logged_out=1;
		} else {
			logged_out=1;
		}
	}
	document.getElementById('fb_login_1').addEventListener('click', function() {
		//do the login
		FB.login(statusChangeCallback, {scope: 'email,public_profile', return_scopes: true});
	}, false);
	document.getElementById('fb_login_2').addEventListener('click', function() {
		//do the login
		FB.login(statusChangeCallback, {scope: 'email,public_profile', return_scopes: true});
	}, false);
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '465307587452391',
      cookie     : true,   
      xfbml      : true,  
      version    : 'v5.0' 
    });

    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });

  };
  
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
	  var id=response.id;
	  var name=response.name;
	 $.ajax({
			url:base_url+'webservices/get-facebook-signin.php',
			type:'post',
			data:{id:id,name:name,email:"",full_name:name,image:""},
			dataType:'html',
			success:function(res){
				//var parsed=JSON.parse(res);
				if(res=="SUCCESS")
				{
					redirect=base_url+"onboarding";
					window.location.href=redirect;
				}
				else
				{
					swal({
					  title: "Oh!, Snap",
					  text: "Something went wrong please try again.",
					  icon: "error",
					  buttons: {
						cancel: false,
						confirm: "Close",
					  },
					  dangerMode: false,
					});
				}
			}
		});
	  console.log(response);
    });
  }
  $("#country_code").change(function(){
	  var code = $('select#country_code').find(':selected').data('code');
	  var url="https://www.countryflags.io/"+code+"/shiny/32.png";
	  if(code=="")
	  {
		  $("#country_flag").hide;
	  }
	  else
	  {
		$("#country_flag").attr("src",url);
		$("#country_flag").show;
	  }
  });
		function forgot_div_show()
		{
			$("#user_login_button").html("Login");
			$("#login_div").hide();
			$("#register_div").hide();
			$("#forgot_div").show();
		}
		function sendPasswordResetEmail()
		{
			var hash=$("#password_reset_hash").val();
			if(hash!="")
			{
				$.ajax({
					url:base_url+"send_password_retrieve_email",
					type:"post",
					data:{reset_hash:hash},
					success:function(response)
					{
						var parsedJson=JSON.parse(response);
						if(parsedJson.status=="success")
						{
							$("#retrieve_password").modal("hide");
							alert("A password reset link has been sent to registered email or mobile");
						}
						else
						{
							alert(parsedJson.message);
						}
					}
				});
			}
			else
			{
				alert("something went wrong please try again.");
			}
		}
		function searchUser(search_input)
		{
			var username=$("#"+search_input).val();
			if(username!="")
			{
				$.ajax({
					url:base_url+"search-user-account-index",
					type:"post",
					data:{username:username},
					success:function(response)
					{
						var parsedJson=JSON.parse(response);
						if(parsedJson.status=="success")
						{
							//console.log(parsedJson);
							$("#retrieve_password_html").html(parsedJson.html);
							$("#retrieve_password").modal("show");
						}
						else
						{
							alert(parsedJson.message);
						}
					}
				});
			}
		}
</script>