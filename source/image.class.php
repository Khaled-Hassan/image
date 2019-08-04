<?php

/**
 * This class contain all image function from upload image to save in new size, add text & watermark and add effects.
 * @author Khaled Hassan
 * @category Image
 * @link khaled.h.developer@gmail.com
 */
class image {

    /**
     * @var The position in center top.
     */
    public const TOP = 1;

    /**
     * @var The position in center down.
     */
    public const DOWN = 2;

    /**
     * @var The position in middle right.
     */
    public const RIGHT = 3;

    /**
     * @var The position in middle left.
     */
    public const LEFT = 4;

    /**
     * @var The position in top right corner.
     */
    public const TOP_RIGHT = 5;

    /**
     * @var The position in top left corner.
     */
    public const TOP_LEFT = 6;

    /**
     * @var The position in down right corner.
     */
    public const DOWN_RIGHT = 7;

    /**
     * @var The position in down left corner.
     */
    public const DOWN_LEFT = 8;

    /**
     * @var The position in center of image.
     */
    public const CENTER = 9;

    /**
     * @var string <br> The new image name after saved from upload.
     */
    public $newImageName = [];

    /**
     * @var string <br> The path to store new images after using functions.
     */
    public $storePath = '';

    /**
     * @var int <br> The image quality saved value from 10 to 100.
     */
    public $quality = 100;

    /**
     * @var string <br> The font path & file name using in write text in image.
     */
    public $fontName = 'source/font/M Unicode Susan.ttf';

    /**
     * @var array <br> The main data get from image uploaded save in array like ['type' => 'jpg', 'width' => 200, 'height' => 150].
     */
    public $imageData = [];

    /**
     * @var array <br> The background color using in free space in image in RGB array like ['R' => 0, 'G' => 0, 'B' => 0] (black).
     */
    public $ground = ['R' => 0, 'G' => 0, 'B' => 0];

    /**
     * construct calass and creat connection
     * @param string $uploadImage [optional] <br> The name of uploaded element <p></p>
     * @param string $storePath [optional] <br> The path to store new omage file after runing functions <p></p>
     * @param int $quality [optional] <br> The new image quality <br> Value between <b>10</b> & <b>100</b> <p></p>
     * @param string $fontName [optional] <br> The font path & file name using in write text in image <p></p>
     */
    function __construct($uploadImage = '', $storePath = '', $quality = 100, $fontName = 'source/font/M Unicode Susan.ttf') {
        if ($uploadImage !== '') {
            
        }

        if ($storePath !== '') {
            $this->storePath = $storePath;
        }

        if ($uploadImage !== '' && $storePath !== '') {
            if (is_array($_FILES[$uploadImage]['tmp_name'])) {
                $this->saveUploadMultiImage($uploadImage, $storePath);
            } else {
                $this->saveUploadSingleImage($uploadImage, $storePath);
            }
        }

        $this->quality = $quality;
        $this->fontName = $fontName;
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Get the image data (type, width, height) for upload phtoto.
     * @param string $uploadImage <br> The name of uploaded element <p></p>
     * @return array <b>The array and put this value in imageData property <br> Like ['type' => 'jpg, 'width' => 200, 'height' => 150].
     */
    function getImageData($uploadImage) {
        $metaData = getimagesize($uploadImage);
        switch ($metaData['mime']) {
            case 'image/jpeg':
                $type = "jpg";
                break;
            case 'image/png':
                $type = "png";
                break;
            case 'image/gif':
                $type = "gif";
                break;
            case 'image/wbmp':
                $type = "bmp";
                break;
            default:
                $type = '';
        }

        $this->imageData[] = ['type' => $type, 'width' => (int) $metaData[0], 'height' => (int) $metaData[1]];
        return $this->imageData;
    }

    /**
     * Generate new name from timestamp and random characters.
     * @param string $type <br> The image type like (jpg, png, bmp, ...) <p></p>
     * @return string <br> The new image name.
     */
    function generateName($type) {
        $char = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $currentDate = date_create();
        $writeDate = date_timestamp_get($currentDate);
        $name = $char[mt_rand(0, 25)] . $char[mt_rand(0, 25)] . substr($writeDate, 0, 6) . $char[mt_rand(0, 25)] . substr($writeDate, 6, 4) . '.' . $type;

        return $name;
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Compress image size according to quality property and save new image in storPath property.
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function compress($deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $newWidth = $this->imageData[$i]['width'];
            $newHeight = $this->imageData[$i]['height'];

            $imageName = $this->storePath . $this->newImageName[$i];
            $newName = $this->generateName($this->imageData[$i]['type']);
            $names[] = $newName;
            $this->saveImage($imageName, $this->imageData[$i]['type'], $this->storePath . $newName, $newWidth, $newHeight, $newWidth, $newHeight, $newWidth, $newHeight, 0, 0, 0, 0);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Resize image with same original image size ratio under new width & height.
     * @param int $maxWidth <br> The maximum width allwaed <p></p>
     * @param int $maxHeight <br> The maximum height allwaed <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function resizeSameRatio($maxWidth, $maxHeight, $deleteSource = FALSE) {
        $newWidth = 0;
        $newHeight = 0;
        $names = [];

        for ($i = 0; $i < count($this->newImageName); $i++) {
            $srcWidth = (int) $this->imageData[$i]['width'];
            $srcHeight = (int) $this->imageData[$i]['height'];
            if ($srcWidth > $srcHeight) {
                $newWidth = $maxWidth;
                $newHeight = (int) (($newWidth / $srcWidth) * $srcHeight);
            } elseif ($srcWidth < $srcHeight) {
                $newHeight = $maxHeight;
                $newWidth = (int) (($newHeight / $srcHeight) * $srcWidth);
            } elseif ($srcWidth === $srcHeight) {
                $newWidth = $maxWidth;
                $newHeight = $maxHeight;
            }

            $imageName = $this->storePath . $this->newImageName[$i];
            $newName = $this->generateName($this->imageData[$i]['type']);
            $names[] = $newName;
            $this->saveImage($imageName, $this->imageData[$i]['type'], $this->storePath . $newName, $newWidth, $newHeight, $srcWidth, $srcHeight, $newWidth, $newHeight, 0, 0, 0, 0);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Resize image and stretch image to new size.
     * @param int $width <br> The new width <p></p>
     * @param int $height <br> The new height <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function resizeStretch($width, $height, $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $x = 0;
            $y = 0;
            $srcWidth = (int) $this->imageData[$i]['width'];
            $srcHeight = (int) $this->imageData[$i]['height'];
            $srcPercent = $srcWidth / $srcHeight;
            $percent = $width / $height;

            if ($percent < $srcPercent) {
                $srcWidth = $srcHeight * $srcPercent;
                if ($srcWidth === $srcWidth) {
                    $x = 0;
                } else {
                    $x = ($srcWidth - $srcWidth) / 2;
                }
            } elseif ($percent > $srcPercent) {
                $srcHeight = (int) ($srcWidth / $srcPercent);
                if ($srcHeight === $srcHeight) {
                    $y = 0;
                } else {
                    $y = ($srcHeight - $srcHeight) / 2;
                }
            }

            $imageName = $this->storePath . $this->newImageName[$i];
            $newName = $this->generateName($this->imageData[$i]['type']);
            $names[] = $newName;
            $this->saveImage($imageName, $this->imageData[$i]['type'], $this->storePath . $newName, $width, $height, $this->imageData[$i]['width'], $this->imageData[$i]['height'], $width, $height, 0, 0, $x, $y);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Resize image and cut over edge space if new ratio(new width & height) not same the original image ration.
     * @param int $width <br> The new width <p></p>
     * @param int $height <br> The new height <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function resizeCutOver($width, $height, $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $x = 0;
            $y = 0;
            $srcWidth = (int) $this->imageData[$i]['width'];
            $srcHeight = (int) $this->imageData[$i]['height'];

            if ($srcWidth > $srcHeight) {
                $x = (int) (($srcWidth - $srcHeight) / 2);
                $y = 0;
                $srcWidth = $srcHeight;
                $srcHeight = $srcHeight;
            } elseif ($srcHeight > $srcWidth) {
                $x = 0;
                $y = (int) (($srcHeight - $srcWidth) / 2);
                $srcWidth = $srcWidth;
                $srcHeight = $srcWidth;
            }

            $imageName = $this->storePath . $this->newImageName[$i];
            $newName = $this->generateName($this->imageData[$i]['type']);
            $names[] = $newName;
            $this->saveImage($imageName, $this->imageData[$i]['type'], $this->storePath . $newName, $width, $height, $srcWidth, $srcHeight, $width, $height, $x, $y, 0, 0);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Resize image with same original ratio and free space will take background color in ground property.
     * @param int $width <br> The new width <p></p>
     * @param int $height <br> The new height <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function resizeFixed($width, $height, $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $newWidth = 0;
            $newHeight = 0;
            $srcWidth = (int) $this->imageData[$i]['width'];
            $srcHeight = (int) $this->imageData[$i]['height'];

            if ($srcWidth > $srcHeight) {
                $percent1 = $width / $height;
                $percent2 = $srcWidth / $srcHeight;
                if ($percent1 > $percent2) {
                    $newHeight = $height;
                    $newWidth = (int) (($newHeight / $srcHeight) * $srcWidth);
                } elseif ($percent1 <= $percent2) {
                    $newWidth = $width;
                    $newHeight = (int) (($newWidth / $srcWidth) * $srcHeight);
                }
            } elseif ($srcWidth < $srcHeight) {
                $percent1 = $height / $width;
                $percent2 = $srcHeight / $srcWidth;
                if ($percent1 > $percent2) {
                    $newWidth = $width;
                    $newHeight = (int) (($newWidth / $srcWidth) * $srcHeight);
                } elseif ($percent1 <= $percent2) {
                    $newHeight = $height;
                    $newWidth = (int) (($newHeight / $srcHeight) * $srcWidth);
                }
            } elseif ($srcWidth === $srcHeight) {
                $percent1 = $width / $height;
                if ($percent1 > 1) {
                    $newHeight = $height;
                    $newWidth = (int) (($newHeight / $srcHeight) * $srcWidth);
                } elseif ($percent1 <= 1) {
                    $newWidth = $width;
                    $newHeight = (int) (($newWidth / $srcWidth) * $srcHeight);
                }
            }

            $x = (int) (($width - $newWidth) / 2);
            $y = (int) (($height - $newHeight) / 2);

            $imageName = $this->storePath . $this->newImageName[$i];
            $newName = $this->generateName($this->imageData[$i]['type']);
            $names[] = $newName;
            $this->saveImage($imageName, $this->imageData[$i]['type'], $this->storePath . $newName, $newWidth, $newHeight, $srcWidth, $srcHeight, $width, $height, 0, 0, $x, $y);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Resize transparent PNG image with same original ratio.
     * @param int $maxWidth <br> The new width <p></p>
     * @param int $maxHeight <br> The new height <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function resizeTransparentPNGSameRatio($maxWidth, $maxHeight, $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $newWidth = 0;
            $newHeight = 0;

            $srcWidth = (int) $this->imageData[$i]['width'];
            $srcHeight = (int) $this->imageData[$i]['height'];
            if ($srcWidth > $srcHeight) {
                $newWidth = $maxWidth;
                $newHeight = (int) (($newWidth / $srcWidth) * $srcHeight);
            } elseif ($srcWidth < $srcHeight) {
                $newHeight = $maxHeight;
                $newWidth = (int) (($newHeight / $srcHeight) * $srcWidth);
            } elseif ($srcWidth === $srcHeight) {
                $newWidth = $maxWidth;
                $newHeight = $maxHeight;
            }

            $imageName = $this->storePath . $this->newImageName[$i];
            $newName = $this->generateName('png');
            $names[] = $newName;
            $this->saveTransparent($imageName, $this->imageData[$i]['type'], $this->storePath . $newName, $newWidth, $newHeight, $srcWidth, $srcHeight, $newWidth, $newHeight, 0, 0, 0, 0);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Resize transparent PNG image with same original ratio and free space will be transparent.
     * @param int $width <br> The new width <p></p>
     * @param int $hight <br> The new height <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function resizeTransparentPNGFixed($width, $hight, $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $newWidth = 0;
            $newHeight = 0;
            $srcWidth = (int) $this->imageData[$i]['width'];
            $srcHeight = (int) $this->imageData[$i]['height'];

            if ($srcWidth > $srcHeight) {
                $percent1 = $width / $hight;
                $percent2 = $srcWidth / $srcHeight;
                if ($percent1 > $percent2) {
                    $newHeight = $hight;
                    $newWidth = (int) (($newHeight / $srcHeight) * $srcWidth);
                } elseif ($percent1 <= $percent2) {
                    $newWidth = $width;
                    $newHeight = (int) (($newWidth / $srcWidth) * $srcHeight);
                }
            } elseif ($srcWidth < $srcHeight) {
                $percent1 = $hight / $width;
                $percent2 = $srcHeight / $srcWidth;
                if ($percent1 > $percent2) {
                    $newWidth = $width;
                    $newHeight = (int) (($newWidth / $srcWidth) * $srcHeight);
                } elseif ($percent1 <= $percent2) {
                    $newHeight = $hight;
                    $newWidth = (int) (($newHeight / $srcHeight) * $srcWidth);
                }
            } elseif ($srcWidth === $srcHeight) {
                $percent1 = $width / $hight;
                if ($percent1 > 1) {
                    $newHeight = $hight;
                    $newWidth = (int) (($newHeight / $srcHeight) * $srcWidth);
                } elseif ($percent1 <= 1) {
                    $newWidth = $width;
                    $newHeight = (int) (($newWidth / $srcWidth) * $srcHeight);
                }
            }

            $x = (int) (($width - $newWidth) / 2);
            $y = (int) (($hight - $newHeight) / 2);

            $imageName = $this->storePath . $this->newImageName[$i];
            $newName = $this->generateName('png');
            $names[] = $newName;
            $this->saveTransparent($imageName, $this->imageData[$i]['type'], $this->storePath . $newName, $newWidth, $newHeight, $srcWidth, $srcHeight, $width, $hight, 0, 0, $x, $y);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Rotate image to left in degree from 1 to 359.
     * @param int $degree <br> The rotation degree from 1 to 359 <p></p>
     * @param bool $Transparent [optional] <br> Save new image in transparent PNG file or not <p></p>
     * @param array $backgroundColor [optional] <br> The array contain background color in RGB if new image not transparent <br> like ['R' => 0, 'G' => 0, 'B' => 0] for black color <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    public function rotate($degree, $Transparent = TRUE, $backgroundColor = [], $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];
            $img = $this->creatImage($imageName, $type);

            if ($Transparent) {
                $newName = $this->generateName('png');
                $names[] = $newName;

                $background = imagecolorallocate($img, 255, 255, 255);
                $rotated_image = imagerotate($img, $degree, $background);
                imagecolortransparent($rotated_image, $background);

                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }
                imagepng($rotated_image, $this->storePath . $newName, $quality);
            } else {
                if (count($backgroundColor) !== 3) {
                    $backgroundColor = $this->ground;
                }
                $newName = $this->generateName('jpg');
                $names[] = $newName;

                $background = imagecolorallocate($img, $backgroundColor['R'], $backgroundColor['G'], $backgroundColor['B']);
                $rotated_image = imagerotate($img, $degree, $background);

                imagejpeg($rotated_image, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($img);
            imagedestroy($rotated_image);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Add a text to current image.
     * @param string $text <br> The text want add to image <p></p>
     * @param int $fontSize [optional] <br> The font size of new text <p></p>
     * @param int $postion [optional] <br> The value form 1 to 9 like postion constant <p></p>
     * @param array $postion [optional] <br> The postion array like [width, height] <p></p>
     * @param array $color [optional] <br> The array contain text color in RGB <br> like ['R' => 255, 'G' => 255, 'B' => 255] for white color <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    public function addText($text, $fontSize = 16, $postion = 9, $color = ['R' => 255, 'G' => 255, 'B' => 255], $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];
            $newName = $this->generateName($type);
            $names[] = $newName;
            $img = $this->creatImage($imageName, $type);

            if (is_array($postion)) {
                $textTop = $postion[0];
                $textLeft = $postion[1];
            } else {
                if ((int) $postion < 1 || (int) $postion > 9) {
                    $postion = 9;
                }

                $postion = $this->getTextPostion($text, $fontSize, $postion, $i);
                $textTop = $postion[0];
                $textLeft = $postion[1];
            }

            $textColor = imagecolorallocate($img, $color['R'], $color['G'], $color['B']);
            imagettftext($img, $fontSize, 0, $textLeft, $textTop, $textColor, $this->fontName, $text);

            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }

                imagepng($img, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($img, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($img);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Add watermark image to current image.
     * @param string $watermarkFile <br> The path and file name of watermaek want add to image <p></p>
     * @param int $postion [optional] <br> The value form 1 to 9 like postion constant <p></p>
     * @param array $postion [optional] <br> The postion array like [width, height] <p></p>
     * @param int $opacity [optional] <br> The opacity degree from <b>0.1</b> to very transparent to <b>1</b> not transparent <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function watermark_image($watermarkFile, $postion = 9, $opacity = 1, $deleteSource = FALSE) {
        $watermark = imagecreatefrompng($watermarkFile);
        imagealphablending($watermark, false);
        imagesavealpha($watermark, true);

        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];
            $newName = $this->generateName($type);
            $names[] = $newName;
            $img = $this->creatImage($imageName, $type);
            $watermarkWidth = imagesx($watermark);
            $watermarkHeight = imagesy($watermark);

            if (is_array($postion)) {
                $watermarkTop = $postion[0];
                $watermarkLeft = $postion[1];
            } else {
                if ((int) $postion < 1 || (int) $postion > 9) {
                    $postion = 9;
                }

                $postion = $this->getWatermarkPostion($watermarkWidth, $watermarkHeight, $postion, $i);
                $watermarkTop = $postion[0];
                $watermarkLeft = $postion[1];
            }

            if ($opacity !== 1) {
                if ($opacity > 1 && $opacity <= 100) {
                    $opacity = $opacity / 100;
                } elseif ($opacity > 100) {
                    $opacity = 1;
                } elseif ($opacity < 0) {
                    $opacity = 0;
                }
                $opacity = 1 - $opacity;
                imagefilter($watermark, IMG_FILTER_COLORIZE, 0, 0, 0, 127 * $opacity);
            }

            imagecopy($img, $watermark, $watermarkLeft, $watermarkTop, 0, 0, $watermarkWidth, $watermarkHeight);

            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }

                imagepng($img, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($img, $this->storePath . $newName, 100);
            }

            imagedestroy($img);
        }

        imagedestroy($watermark);

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Change contrast effect.
     * @param int $degree <br> The contrast degree from <b>-60</b> to <b>60</b> <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function effectContrast($degree, $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];

            $newName = $this->generateName($type);
            $names[] = $newName;

            $image = $this->creatImage($imageName, $type);
            imagefilter($image, IMG_FILTER_CONTRAST, $degree);
            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }
                imagepng($image, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($image, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($image);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Change brightness effect.
     * @param int $degree <br> The contrast degree from <b>-100</b> to <b>100</b> <p></p>
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function effectBrightness($degree, $deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];

            $newName = $this->generateName($type);
            $names[] = $newName;

            $image = $this->creatImage($imageName, $type);
            imagefilter($image, IMG_FILTER_BRIGHTNESS, $degree);
            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }
                imagepng($image, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($image, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($image);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Add blur effect.
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function effectBlur($deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];

            $newName = $this->generateName($type);
            $names[] = $newName;

            $image = $this->creatImage($imageName, $type);
            imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
            imagefilter($image, IMG_FILTER_SELECTIVE_BLUR);
            imagefilter($image, IMG_FILTER_SMOOTH, 1);
            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }
                imagepng($image, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($image, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($image);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Add photo negative effect.
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function effectNegative($deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];

            $newName = $this->generateName($type);
            $names[] = $newName;

            $image = $this->creatImage($imageName, $type);
            imagefilter($image, IMG_FILTER_GRAYSCALE);
            imagefilter($image, IMG_FILTER_NEGATE);
            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }
                imagepng($image, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($image, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($image);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Add carton or comics effect.
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function effectCarton($deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];

            $newName = $this->generateName($type);
            $names[] = $newName;

            $image = $this->creatImage($imageName, $type);
            imagefilter($image, IMG_FILTER_EMBOSS);
            imagefilter($image, IMG_FILTER_MEAN_REMOVAL);
            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }
                imagepng($image, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($image, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($image);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Change image to white & black.
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function effectWhiteBlack($deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];

            $newName = $this->generateName($type);
            $names[] = $newName;

            $image = $this->creatImage($imageName, $type);
            imagefilter($image, IMG_FILTER_GRAYSCALE);
            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }
                imagepng($image, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($image, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($image);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /**
     * Add freehand or draw effect.
     * @param bool $deleteSource [optional] <br> Delete source images and make new images is source or not <br> <b>TRUE</b> delete source image <b>FALSE</b> keep source images <p></p>
     * @return string <br> The new image name.
     */
    function effectFreehand($deleteSource = FALSE) {
        $names = [];
        for ($i = 0; $i < count($this->newImageName); $i++) {
            $imageName = $this->storePath . $this->newImageName[$i];
            $type = $this->imageData[$i]['type'];

            $newName = $this->generateName($type);
            $names[] = $newName;

            $image = $this->creatImage($imageName, $type);
            imagefilter($image, IMG_FILTER_EDGEDETECT);
            if ($type === 'png') {
                $quality = (int) $this->quality / 10;
                if ($quality > 9) {
                    $quality = 9;
                } elseif ($quality < 1) {
                    $quality = 1;
                }
                imagepng($image, $this->storePath . $newName, $quality);
            } else {
                imagejpeg($image, $this->storePath . $newName, $this->quality);
            }

            imagedestroy($image);
        }

        if ($deleteSource) {
            $this->deleteSourceImage($names);
        }
        return $names;
    }

    /* -*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*- */

    /**
     * Save single image uploaded.
     * @param string $uploadImage <br> The name of uploaded element <p></p>
     * @param string $storePath <br> The path to store new omage file after runing functions <p></p>
     * @return null.
     */
    private function saveUploadSingleImage($uploadImage, $storePath) {
        $metaData = getimagesize($_FILES[$uploadImage]['tmp_name']);
        switch ($metaData['mime']) {
            case 'image/jpeg':
                $type = "jpg";
                break;
            case 'image/png':
                $type = "png";
                break;
            case 'image/gif':
                $type = "gif";
                break;
            case 'image/wbmp':
                $type = "bmp";
                break;
            default:
                $type = '';
        }
        $name = $this->generateName($type);
        $this->newImageName[] = $name;

        move_uploaded_file($_FILES[$uploadImage]['tmp_name'], $storePath . $name);
        $this->getImageData($storePath . $name);
    }

    /**
     * Save multi image uploaded.
     * @param string $uploadImage <br> The name of uploaded element <p></p>
     * @param string $storePath <br> The path to store new omage file after runing functions <p></p>
     * @return null.
     */
    private function saveUploadMultiImage($uploadImage, $storePath) {
        for ($i = 0; $i < count($_FILES[$uploadImage]['tmp_name']); $i++) {
            $metaData = getimagesize($_FILES[$uploadImage]['tmp_name'][$i]);
            switch ($metaData['mime']) {
                case 'image/jpeg':
                    $type = "jpg";
                    break;
                case 'image/png':
                    $type = "png";
                    break;
                case 'image/gif':
                    $type = "gif";
                    break;
                case 'image/wbmp':
                    $type = "bmp";
                    break;
                default:
                    $type = '';
            }
            $name = $this->generateName($type);
            $this->newImageName[] = $name;

            move_uploaded_file($_FILES[$uploadImage]['tmp_name'][$i], $storePath . $name);
            $this->getImageData($storePath . $name);
        }
    }

    /**
     * Private Creat image object with same the file format from image file.
     * @param string $imageName <br> The image path and file name <p></p>
     * @param string $type <br> The image type <p></p>
     * @return obj <br> The image object.
     */
    private function creatImage($imageName, $type) {
        switch ($type) {
            case 'jpg':
                $img = imagecreatefromjpeg($imageName);
                break;
            case 'png':
                $img = imagecreatefrompng($imageName);
                break;
            case 'gif':
                $img = imagecreatefromgif($imageName);
                break;
            case 'bmp':
                $img = imagecreatefromwbmp($imageName);
                break;
        }

        return $img;
    }

    /**
     * Private save the image in JPG format.
     * @param string $imageName <br> The sourse image path and file name <p></p>
     * @param string $type <br> The image type <p></p>
     * @param string $newName <br> The destination image path and file name <p></p>
     * @param int $newWidth <br> The new image width <p></p>
     * @param int $newHeight <br> The new image height <p></p>
     * @param int $sourceWidth <br> The source image width <p></p>
     * @param int $sourceHeight <br> The source image height <p></p>
     * @param int $maxWidth <br> The new image maximum width <p></p>
     * @param int $maxHeight <br> The new image maximum height <p></p>
     * @param int $sourceX <br> The source image position X <p></p>
     * @param int $sourceY <br> The source image position Y <p></p>
     * @param int $destinationX <br> The destination image position X <p></p>
     * @param int $destinationY <br> The destination image position Y <p></p>
     * @return null.
     */
    private function saveImage($imageName, $type, $newName, $newWidth, $newHeight, $sourceWidth, $sourceHeight, $maxWidth, $maxHeight, $sourceX, $sourceY, $destinationX, $destinationY) {
        $img = $this->creatImage($imageName, $type);

        $imgThumb = imagecreatetruecolor($maxWidth, $maxHeight);
        $ground = imagecolorallocate($imgThumb, $this->ground['R'], $this->ground['G'], $this->ground['B']);
        imagefilledrectangle($imgThumb, 0, 0, $newWidth, $newHeight, $ground);
        imagecopyresampled($imgThumb, $img, $destinationX, $destinationY, $sourceX, $sourceY, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

        imagejpeg($imgThumb, $newName, $this->quality);
        imagedestroy($imgThumb);
        imagedestroy($img);
    }

    /**
     * Private save the image in PNG format.
     * @param string $imageName <br> The sourse image path and file name <p></p>
     * @param string $type <br> The image type <p></p>
     * @param string $newName <br> The destination image path and file name <p></p>
     * @param int $newWidth <br> The new image width <p></p>
     * @param int $newHeight <br> The new image height <p></p>
     * @param int $sourceWidth <br> The source image width <p></p>
     * @param int $sourceHeight <br> The source image height <p></p>
     * @param int $maxWidth <br> The new image maximum width <p></p>
     * @param int $maxHeight <br> The new image maximum height <p></p>
     * @param int $sourceX <br> The source image position X <p></p>
     * @param int $sourceY <br> The source image position Y <p></p>
     * @param int $destinationX <br> The destination image position X <p></p>
     * @param int $destinationY <br> The destination image position Y <p></p>
     * @return null.
     */
    private function saveTransparent($imageName, $type, $newName, $newWidth, $newHeight, $sourceWidth, $sourceHeight, $maxWidth, $maxHeight, $sourceX, $sourceY, $destinationX, $destinationY) {
        $img = $this->creatImage($imageName, $type);

        $imgThumb = imagecreatetruecolor($maxWidth, $maxHeight);
        imagealphablending($imgThumb, false);
        $background = imagecolorallocatealpha($imgThumb, 255, 255, 255, 127);
        imagefilledrectangle($imgThumb, 0, 0, $maxWidth, $maxHeight, $background);
        imagealphablending($imgThumb, true);
        imagecopyresampled($imgThumb, $img, $destinationX, $destinationY, $sourceX, $sourceY, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
        imagealphablending($imgThumb, true);
        imagesavealpha($imgThumb, true);

        $quality = (int) $this->quality / 10;
        if ($quality > 9) {
            $quality = 9;
        } elseif ($quality < 1) {
            $quality = 1;
        }

        imagepng($imgThumb, $newName, $quality);
        imagedestroy($imgThumb);
        imagedestroy($img);
    }

    /**
     * Private get text postion according to text size and position option.
     * @param string $text <br> The text want define postion <p></p>
     * @param int $fontSize <br> The font size <p></p>
     * @param int $postion <br> The value form 1 to 9 like postion constant <p></p>
     * @param int $id <br> The index of image want add text to it <p></p>
     * @return array <br> The array contain postion like [top, left].
     */
    private function getTextPostion($text, $fontSize, $postion, $id) {
        $size = imagettfbbox($fontSize, 0, $this->fontName, $text);
        $textWidth = (int) (abs($size[0]) + abs($size[2]));
        $textHeight = (int) (abs($size[5]) + abs($size[1]));
        $imageWidth = $this->imageData[$id]['width'];
        $imageHeight = $this->imageData[$id]['height'];

        switch ((int) $postion) {
            case self::TOP:
                $textTop = $textHeight + 10;
                $textLeft = (int) (($imageWidth - $textWidth) / 2);
                break;
            case self::DOWN:
                $textTop = (int) ($imageHeight - 10);
                $textLeft = (int) (($imageWidth - $textWidth) / 2);
                break;
            case self::RIGHT:
                $textTop = (int) ($imageHeight / 2);
                $textLeft = (int) ($imageWidth - $textWidth - 10);
                break;
            case self::LEFT:
                $textTop = (int) ($imageHeight / 2);
                $textLeft = 10;
                break;
            case self::TOP_RIGHT:
                $textTop = $textHeight + 10;
                $textLeft = (int) ($imageWidth - $textWidth - 10);
                break;
            case self::TOP_LEFT:
                $textTop = $textHeight + 10;
                $textLeft = 10;
                break;
            case self::DOWN_RIGHT:
                $textTop = (int) ($imageHeight - 10);
                $textLeft = (int) ($imageWidth - $textWidth - 10);
                break;
            case self::DOWN_LEFT:
                $textTop = (int) ($imageHeight - 10);
                $textLeft = 10;
                break;
            case self::CENTER:
                $textTop = (int) ($imageHeight / 2);
                $textLeft = (int) (($imageWidth - $textWidth) / 2);
                break;
            default :
                $textTop = (int) ($imageHeight / 2);
                $textLeft = (int) (($imageWidth - $textWidth) / 2);
                break;
        }

        return [$textTop, $textLeft];
    }

    /**
     * Private get watermark postion according to watermark image size and position option.
     * @param int $width <br> The watermark width <p></p>
     * @param int $height <br> The watermark height <p></p>
     * @param int $postion [optional] <br> The value form 1 to 9 like postion constant <p></p>
     * @param int $id <br> The index of image want add watetmark to it <p></p>
     * @return array <br> The array contain postion like [top, left].
     */
    private function getWatermarkPostion($width, $height, $postion, $id) {
        $imageWidth = $this->imageData[$id]['width'];
        $imageHeight = $this->imageData[$id]['height'];

        switch ((int) $postion) {
            case self::TOP:
                $top = 10;
                $left = (int) (($imageWidth - $width) / 2);
                break;
            case self::DOWN:
                $top = (int) ($imageHeight - $height - 10);
                $left = (int) (($imageWidth - $width) / 2);
                break;
            case self::RIGHT:
                $top = (int) (($imageHeight - $height) / 2);
                $left = (int) ($imageWidth - $width - 10);
                break;
            case self::LEFT:
                $top = (int) (($imageHeight - $height) / 2);
                $left = 10;
                break;
            case self::TOP_RIGHT:
                $top = 10;
                $left = (int) ($imageWidth - $width - 10);
                break;
            case self::TOP_LEFT:
                $top = 10;
                $left = 10;
                break;
            case self::DOWN_RIGHT:
                $top = (int) ($imageHeight - $height - 10);
                $left = (int) ($imageWidth - $width - 10);
                break;
            case self::LEFT:
                $top = (int) ($imageHeight - $height - 10);
                $left = 10;
                break;
            case self::CENTER:
                $top = (int) (($imageHeight - $height) / 2);
                $left = (int) (($imageWidth - $width) / 2);
                break;
            default :
                $top = (int) (($imageHeight - $height) / 2);
                $left = (int) (($imageWidth - $width) / 2);
                break;
        }

        return [$top, $left];
    }

    /**
     * Private delete the source images and make new images is source.
     * @param array $newImage <br> The array contain new images name <p></p>
     * @return null.
     */
    private function deleteSourceImage($newImage) {
        for ($i = 0; $i < count($this->newImageName); $i++) {
            unlink($this->storePath . $this->newImageName[$i]);
        }

        $this->newImageName = $newImage;
        $this->imageData = [];

        for ($i = 0; $i < count($newImage); $i++) {
            $imageName = $this->storePath . $newImage[$i];
            $this->getImageData($imageName);
        }
    }

}
