<?php

        //Output image header information to browser
        header('Content-type:image/jpeg');
        $width = 100;
        $height = 30;
        $string = ''; //Define variable to save font
        $img = imagecreatetruecolor($width, $height);
        $arr = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        //Generate colored pixels  
        $colorBg = imagecolorallocate($img, rand(200, 255), rand(200, 255), rand(200, 255));
        //Fill color
        imagefill($img, 0, 0, $colorBg);
        //The loop, loop draw background interference points
        for ($m = 0; $m <= 100; $m++) {
            $pointcolor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($img, rand(0, $width - 1), rand(0, $height - 1), $pointcolor);
        }
        //Draw interference lines in a loop
        /*for ($i=0;$i<=4;$i++){
          $linecolor=imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
          imageline($img,rand(0,$width),rand(0,$height),rand(0,$width),rand(0,$height),$linecolor);
        }*/
        for ($i = 0; $i < 4; $i++) {
            $string .= $arr[rand(0, count($arr) - 1)];
        }
        $_SESSION['captcha'] = $string;
        $colorString = imagecolorallocate($img, rand(10, 100), rand(10, 100), rand(10, 100));
        imagestring($img, 5, rand(0, $width - 36), rand(0, $height - 15), $string, $colorString);
        //Output picture to browser
        imagejpeg($img);
        //Destroy, release resources
        imagedestroy($img);
        die;

?>