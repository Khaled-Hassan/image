<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form id="uploadPhoto" action="image_example2.php" method="post" enctype="multipart/form-data">
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
            /* creat new instance and using multi function and delete old version from image */
            $img = new image('photo', $storePath, 100);// 'photo' is name of input file make upload
            $sourceIamge = $storePath . $img->newImageName[0];
            
            $image1 = $img->resizeSameRatio(800, 600, TRUE)[0];
            $image2 = $img->effectBrightness(50, TRUE)[0];
            $image3 = $img->effectWhiteBlack(TRUE)[0];
            $image4 = $img->addText('Khaled', FALSE, 60, 2, ['R' => 0, 'G' => 0, 'B' => 255], TRUE)[0];
            $image5 = $storePath . $img->watermark_image('logo.png', 9, 0.8, FALSE, TRUE)[0];
            ?>
        <div id="new"><h1>Resize with same ratio, change brightness, make white & black, add text and add watermark<br>Note: delete old image after every step</h1> <br><img src="<?php echo $image5; ?>"></div>
        <?php } ?>

    </body>
</html>
