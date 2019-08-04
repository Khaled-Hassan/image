<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form id="uploadPhoto" action="image_example4.php" method="post" enctype="multipart/form-data">
            <label for="photo">Add Head Photo</label><input type="file" id="image" name="photo[]" multiple="" accept=".jpg, .jpeg, .png, .bmp, .gif" onchange="document.getElementById('uploadPhoto').submit();">
        </form>
        <?php
        include_once './source/image.class.php';
        
        $storePath = 'temp/';
        if (!is_dir($storePath)) {
            mkdir($storePath, 0777, true);
        }
        
        if (isset($_FILES['photo'])){
            $img = new image('photo', $storePath, 100);// 'photo' is name of input file make upload
            $sourceIamge = $img->newImageName;
            $image1 = $img->resizeSameRatio(800, 600);
            $image2 = $img->resizeCutOver(80, 80);
            $image3 = $img->watermark_image('logo.png', 1, 0.7);
            
            echo '<h1>Source images</h1><br>';
            for($i = 0; $i < count($sourceIamge); $i++){
                echo '<img src="' . $storePath . $sourceIamge[$i] . '"><br>';
            }
            
            echo '<h1>Resize images with same ratio</h1><br>';
            for($i = 0; $i < count($image1); $i++){
                echo '<img src="' . $storePath . $image1[$i] . '"><br>';
            }
            
            echo '<h1>Resize images and cut over (making zoom)</h1><br>';
            for($i = 0; $i < count($image2); $i++){
                echo '<img src="' . $storePath . $image2[$i] . '"> ';
            }
            
            echo '<h1>Add watermark to images</h1><br>';
            for($i = 0; $i < count($image3); $i++){
                echo '<img src="' . $storePath . $image3[$i] . '"><br>';
            }
        }
        
        ?>
    </body>
</html>
