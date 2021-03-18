ImageHandler

Class written in PHP to manipulate file images

suported source images formats
	bmp, gif, jpeg, png, wbmp, webp, xbm, xpm
suported destination formats
	bmp, gif, jpeg, jpg, png, wbmp, wepb, xbm, xpm

Methods:

```PHP
$instance = new ImageHandler($imagePath);
```
Constructor, $imagePath is image path to edit

```PHP
$instance->resize($newWidth, $newHeight, $bestFit = false);
```
resize image, if bestFit true, keep original image proportion

```PHP
$instance->setJpegQuality($quality);
```
Set jpeg quality on destination image (takes effect only in jpeg destination images)

```PHP
$instance->setPngCompression($quality);
```
 Set png compression on destination image (takes effect only in png destination images)

```PHP
$instance->save($path, $destinationFormat = false)
```
save image to $path, overrride original save format setting $destination to preferred file format. Suported formats is png, jpg, gif, bmp

Exceptions:

```
FileNotFoundException
```
Exception image file not found
```
JpegQualityOutRangeException
```
raise if parameter of setJpegQuality is out of range between 0 and 100 (not implemented yet)

```
PngCompressionOutRangeException
```
raise if parameter of setJpegQuality is out of range between 0 and 9 (not implemented yet)

```
UnsuportedFileTypeException
```
raise if source file is invalid image format

```
UnsuportedImageFormatException
```
raise if override file format on save is invalid

check example in ```example.php```
