<?php
require "autoload.php";

use Ejss\Exceptions\FileNotFoundException;
use Ejss\Exceptions\UnsuportedFileTypeException;
use Ejss\Exceptions\UnsuportedImageFormatException;
use Ejss\Exceptions\PngCompressionOutRangeException;
use Ejss\Exceptions\JpegQualityOutRangeException;
use Ejss\ImageHandler;

try
{
	$img = new ImageHandler("cat.png");
	$img->resize(640, 480, true); //width, height, bestfit: true or false
	$img->save('cat-resized.png');
}
catch(FileNotFoundException $exception)
{
	echo $exception->getMessage();
}
catch(JpegQualityOutRangeException $exception)
{
	echo $exception->getMessage();
}
catch(PngCompressionOutRangeException $exception)
{
	echo $exception->getMessage();
}
catch(UnsuportedFileTypeException $exception)
{
	echo $exception->getMessage();
}
catch(UnsuportedImageFormatException $exception)
{
	echo $exception->getMessage();
}


?>