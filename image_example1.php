<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form id="uploadPhoto" action="image_example1.php" method="post" enctype="multipart/form-data">
            <label for="photo">Add Head Photo</label><input type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png, .bmp, .gif" onchange="document.getElementById('uploadPhoto').submit();">
        </form>
        <?php
        include_once './source/image.class.php';

        $storePath = 'temp/';
        if (!is_dir($storePath)) {
            mkdir($storePath, 0777, true);
        }
        $allowedtypes = array("image/jpeg", "image/pjpeg", "image/png", "image/gif", "image/wbmp");
        
        if (isset($_FILES['photo']) && in_array($_FILES['photo']['type'], $allowedtypes)){
            $image = new image('photo', $storePath, 100); // 'photo' is name of input file make upload
            $sourceIamge = $storePath . $image->newImageName[0];
            
            $image1 = $storePath . $image->compress()[0];
            $image2 = $image->resizeSameRatio(640, 480)[0]; // don't add store path to make this image the source image after that
            $image3 = $storePath . $image->resizeStretch(640, 480)[0];
            $image4 = $storePath . $image->resizeFixed(640, 480)[0];
            $image5 = $storePath . $image->resizeCutOver(640, 480)[0];
            $image6 = $storePath . $image->resizeTransparentPNGFixed(640, 480)[0];
            $image17 = $storePath . $image->resizeTransparentPNGSameRatio(640, 480)[0];
            
            //making image2 is source to work in small size image if upload big size image
            $image->newImageName = [$image2];
            $image->imageData = [];
            $image->getImageData($storePath . $image2);
            $image2 = $storePath .$image2;
            
            $image7 = $storePath . $image->rotate(90, FALSE)[0];
            $image8 = $storePath . $image->addText('Khaled', 60, 9, ['R' => 255, 'G' => 0, 'B' => 0])[0];
            $image9 = $storePath . $image->watermark_image('logo.png', 1, 0.7)[0];
            
            $image10 = $storePath . $image->effectContrast(20)[0];
            $image11 = $storePath . $image->effectBrightness(50)[0];
            $image12 = $storePath . $image->effectBlur()[0];
            $image13 = $storePath . $image->effectCarton()[0];
            $image14 = $storePath . $image->effectNegative()[0];
            $image15 = $storePath . $image->effectWhiteBlack()[0];
            $image16 = $storePath . $image->effectFreehand()[0];
            ?>
        <div id="source"><h1>Source image</h1> <br><img src="<?php echo $sourceIamge; ?>"></div>
        <div id="new1"><h1>compress Image In same size</h1> <br><img src="<?php echo $image1; ?>"></div>
        <div id="new2"><h1>Resize image with same original image ratio</h1> <br><img src="<?php echo $image2; ?>"></div>
        <div id="new5"><h1>Resize image and cut over to save ratio (making zoom)</h1> <br><img src="<?php echo $image5; ?>"></div>
        <div id="new6"><h1>Resize Transparent PNG or  resize any type to PNG to make over space transparent</h1> <br><img src="<?php echo $image6; ?>"></div>
        <div id="new17"><h1>Resize Transparent PNG or  resize any type to PNG with same original image ratio</h1> <br><img src="<?php echo $image17; ?>"></div>
        <div id="new4"><h1>Resize to fixed size with same ration and make free space with background color and can change this color from property ground</h1> <br><img src="<?php echo $image4; ?>"></div>
        <div id="new3"><h1>Stretch Image to new size</h1> <br><img src="<?php echo $image3; ?>"></div>
        <div id="new7"><h1>Rotate image from left to right controlled from degree</h1> <br><img src="<?php echo $image7; ?>"></div>
        <div id="new8"><h1>Add text to image</h1> <br><img src="<?php echo $image8; ?>"></div>
        <div id="new9"><h1>Add PNG watermark to image (logo.png)</h1> <br><img src="<?php echo $image9; ?>"></div>
        
        <div id="new10"><h1>Add contrast effect degree between 60 & -60</h1> <br><img src="<?php echo $image10; ?>"></div>
        <div id="new11"><h1>Add brightness effect degree between 100 & -100</h1> <br><img src="<?php echo $image11; ?>"></div>
        <div id="new12"><h1>Add blur effect</h1> <br><img src="<?php echo $image12; ?>"></div>
        <div id="new13"><h1>Add carton effect</h1> <br><img src="<?php echo $image13; ?>"></div>
        <div id="new14"><h1>Add negative effect</h1> <br><img src="<?php echo $image14; ?>"></div>
        <div id="new15"><h1>Add white and black effect</h1> <br><img src="<?php echo $image15; ?>"></div>
        <div id="new16"><h1>Add freehand effect</h1> <br><img src="<?php echo $image16; ?>"></div>
        <?php }?>

    </body>
</html>
