<?php
	$RandomStr = md5(microtime());// md5 to generate the random string
	$ResultStr = substr($RandomStr,0,5);//trim 5 digit
	$NewImage =imagecreatefromjpeg("img.jpg");//image create by existing image and as back ground
	
	//Set the image width and height
    //$width = 100;
    //$height = 20; 
    //Create the image resource 
    //$NewImage = ImageCreate($width, $height);  
	
	
	$LineColor = imagecolorallocate($NewImage,233,220,215);//line color
	$TextColor = imagecolorallocate($NewImage, 255, 255, 255);//text color-white
	
	imageline($NewImage,1,1,40,40,$LineColor);//create line 1 on image
	imageline($NewImage,1,100,60,0,$LineColor);//create line 2 on image
	imageline($NewImage,5, 1, 200, 50, $LineColor);
	
	imagestring($NewImage, 5, 15, 5, $ResultStr, $TextColor);// Draw a random string horizontally
	
	$_SESSION['captcha_key'] = $ResultStr;// carry the data through session
	header("Content-type: image/jpeg");// out out the image
	imagejpeg($NewImage);//Output image to browser
?>