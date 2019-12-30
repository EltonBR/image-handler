<?php

namespace Ejss;
use Ejss\Exceptions\FileNotFoundException;
use Ejss\Exceptions\UnsuportedFileTypeException;
use Ejss\Exceptions\UnsuportedImageFormatException;
use Ejss\Exceptions\PngCompressionOutRangeException;
use Ejss\Exceptions\JpegQualityOutRangeException;

class ImageHandler {

    /*  imageInfo is return of getimagesize()
     *  index/keys
     *  [0]=> width
     *  [1]=> height
     *  [2]=> type
     *  [3]=> html attributes
     *  ["bits"]=> bits quantity
     *  ["channels"]=> image channels eg: 3 -> RGB
     *  ["mime"]=> image mime type
    */
    protected $imageInfo = false;
    protected $handler = NULL;
    protected $pngCompression = 3;
    protected $jpegQuality = 80;

    function __construct($imagePath)
    {
        if (!file_exists($imagePath)) {
            throw new FileNotFoundException("File Not Found", 1);
        }

        $this->imageInfo = getimagesize($imagePath);

        if ($this->imageInfo === false) {
            throw new UnsuportedFileTypeException("Unsuported FileType", 2);
        }

        $this->handler = $this->getFunctionCreateByMime($this->imageInfo['mime'])($imagePath);

    }

    public function resize($targetWidth, $targetHeight, $bestFit = false)
    {

        $getBestFitSize = function ($newWidth, $newHeight, $oldWidth, $oldHeight)
        {
            $division = $oldWidth/$newWidth;
            $height = (int)($oldHeight/$division);
            return [$newWidth, $height];    
        };

        $srcWidth  = $this->imageInfo[0];
        $srcHeight = $this->imageInfo[1];
        $srcX = 0;
        $srcY = 0;

        if ($bestFit)
        {
            list($targetWidth, $targetHeight) = $getBestFitSize($targetWidth, $targetHeight, $srcWidth, $srcHeight);
        }

        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($targetImage, $this->handler, 0, 0, $srcX, $srcY, $targetWidth, $targetHeight, $srcWidth, $srcHeight);

        imagedestroy($this->handler);

        $this->handler = $targetImage;

        return $this;
    }

    public function save($path, $destinationFormat = false)
    {
        //override original format
        if ($destinationFormat !== false) {
            $this->saveFormat = $destinationFormat;
        }

        $function = $this->getFunctionSaveByFormat($this->saveFormat);

        switch ($function) {
            case 'imagejpeg':
                $function($this->handler, $path, $this->jpegQuality);
            break;

            case 'imagepng':
                $function($this->handler, $path, $this->pngCompression);
            break;

            default:
                $function($this->handler, $path);
            break;
        }

    }

    public function getPngCompression()
    {
        return $this->pngCompression;
    }

    public function setPngCompression($compression)
    {
        $this->pngCompression = $compression;
        return $this;
    }

    public function getJpegQuality()
    {
        return $this->jpegQuality;
    }

    public function setJpegQuality($quality)
    {
        $this->jpegQuality = $quality;
        return $this;
    }

    private function getFunctionCreateByMime($mime)
    {    
        $mapMimeFunctions = [
            "image/bmp" => "imagecreatefrombmp",
            "image/gd2" => "imagecreatefromgd2",
            "image/gd2part" => "imagecreatefromgd2part",
            "image/gd" => "imagecreatefromgd",
            "image/gif" => "imagecreatefromgif",
            "image/jpeg" => "imagecreatefromjpeg",
            "image/png" => "imagecreatefrompng",
            "image/string" => "imagecreatefromstring",
            "image/wbmp" => "imagecreatefromwbmp",
            "image/webp" => "imagecreatefromwebp",
            "image/xbm" => "imagecreatefromxbm",
            "image/xpm" => "imagecreatefromxpm"
        ];

        $mapMimeType = [
            "image/bmp" => "bmp",
            "image/gd2" => "gd2",
            "image/gd2part" => "gd2p",
            "image/gd" => "gd",
            "image/gif" => "gif",
            "image/jpeg" => "jpeg",
            "image/png" => "png",
            "image/string" => "imgstr",
            "image/wbmp" => "wbmp",
            "image/webp" => "webp",
            "image/xbm" => "xbm",
            "image/xpm" => "xpm"
        ];

        //set default save format by mime
        $this->saveFormat = $mapMimeType[$mime];

        if (!array_key_exists($mime, $mapMimeFunctions)) {
            throw new UnsuportedImageFormatException("Not suported source image format", 3);
        }

        return $mapMimeFunctions[$mime];
    }

    private function getFunctionSaveByFormat($format)
    {
        $mapFormatSave = [
            "bmp" => "imagebmp",
            "gif" => "imagegif",
            "jpeg" => "imagejpeg",
            "jpg" => "imagejpeg",
            "png" => "imagepng",
            "wbmp" => "imagewbmp",
            "wepb" => "imagewebp",
            "xbm" => "imagexbm",
            "xpm" => "imagexpm"
        ];

        if (!array_key_exists($format, $mapFormatSave)) {
            throw new UnsuportedImageFormatException("Not suported image destination format", 4);
        }

        return $mapFormatSave[$format];
    }
}

?>