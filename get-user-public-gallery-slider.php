<?php
	include_once 'connection.php';
	$user_id=$_POST['user_id'];
	$image=$_POST['image'];
	$query="SELECT * FROM gallery WHERE user_id='$user_id' AND is_professional=1 AND is_draft=0 AND is_private=0 ORDER BY id DESC LIMIT 10";
	$result=mysqli_query($conn,$query);
	$image_counts=mysqli_num_rows($result)+1;
	$users_data=getUsersData($user_id);
?>
<style>
.mySlides {display: none}
.slide-img {vertical-align: middle;}

/* Slideshow container */
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}

/* Next & previous buttons */
.previous, .itsnext {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -22px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

/* Position the "next button" to the right */
.itsnext {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.previous:hover, .itsnext:hover {
  background-color: rgba(0,0,0,0.8);
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.itsactive, .dot:hover {
  background-color: #717171;
}

/* Fading animation */
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
}

@-webkit-keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: .4} 
  to {opacity: 1}
}

/* On smaller screens, decrease text size */
@media only screen and (max-width: 300px) {
  .previous, .itsnext,.text {font-size: 11px}
}
</style>
<?php
	if($users_data)
	{
		?>
		<a href="<?php echo base_url.'u/'.$users_data['username'].'/gallery'; ?>" style="margin-bottom:20px;" class="btn btn-success">Go to gallery</a>
		<?php
	}
?>
<div class="slideshow-container" style="color:#000;width:100%;">
	<div class="mySlides fade show" style="text-align:center;width:100%;">
	  <div class="numbertext" style="width:100%;text-align:center;">1 / <?php echo $image_counts; ?></div>
	  <img src="<?php echo str_replace(base_url,image_kit,$image).'?tr=w-400,h-700'; ?>" class="slide-img" style="max-height:400px;">
	  <div class="text">Current Profile Picture</div>
	</div>
<?php
	if($image_counts>1)
	{
		$i=2;
		while($row=mysqli_fetch_array($result))
		{
			$image=image_kit.$row['file'];
			?>
			<div class="mySlides fade show" style="text-align:center;width:100%;">
			  <div class="numbertext" style="width:100%;text-align:center;"><?php echo $i++; ?> / <?php echo $image_counts; ?></div>
			  <img src="<?php echo $image.'?tr=w-400,h-700'; ?>" class="slide-img" style="max-height:400px;">
			  <div class="text"></div>
			</div>
			<?php
		}
	}
?>
<a class="previous" onclick="plusSlides(-1)" style="left:-15px;">&#10094;</a>
<a class="itsnext" onclick="plusSlides(1)" style="right:-15px;">&#10095;</a>

</div>
<br>

<div style="text-align:center">
	<?php
		for($i=1;$i<=$image_counts;$i++)
		{
			?>
			<span class="dot <?php if($i==1){ echo ' itsactive'; } ?>" onclick="currentSlide(<?php echo $i; ?>)"></span> 
			<?php
		}
	?>
</div>

<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" itsactive", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " itsactive";
}
</script>