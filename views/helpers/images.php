<?php
class ImagesHelper extends AppHelper {
	
	public $helpers = array('Html');
	
	public $cacheDir = 'thumb'; // relative to 'img'.DS
	
	/**
	 * Folder of images (inside of webroot)
	 * @var array
	 */
	public $uploadsDir = array('uploads', 'geecktec_filemanager'); 
	
//	public $types = array("gif", "jpeg", "png", "bmp");

	/**
     * Automatically resizes an image and returns formatted IMG tag
     *
     * @param string $path Path to the image file, relative to the webroot/img/ directory.
     * @param options = array(
     * 		'width' => integer Width image of returned image
     * 		'height' => integer Height image of returned image
     * 		'aspect' => boolean Maintain aspect of returned image (default: true)
     * 		'adaptive' => boolean Croop the image to maintain the size (default: false)
     * @param array    $htmlAttributes Array of HTML attributes.
     * @param boolean $return Wheter this method should return a value or output it. This overrides AUTO_OUTPUT.
     * @return mixed    Either string or echos the value, depends on AUTO_OUTPUT and $return.
     * @access public
     */
	public function resize($path, $options = array(), $htmlAttributes = array(), $return = false){
		$width = $options['width'];
		$height = $options['height'];
		$aspect = isset($options['aspect']) ? $options['aspect'] : true;
		$adaptive = isset($options['adaptive']) ? $options['adaptive'] : false;
	
		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type
		if(empty($htmlAttributes['alt'])) $htmlAttributes['alt'] = 'thumb';  // Ponemos alt default
		
		$uploadsDir = implode(DS,$this->uploadsDir);
		
		$fullpath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.$uploadsDir.DS;
		
		$url = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.$path;
		
		if (!($size = getimagesize($url)))
			return; // image doesn't exist
		
		if ($aspect) { // adjust to aspect.
			if (($size[1]/$height) > ($size[0]/$width))  // $size[0]:width, [1]:height, [2]:type
				$width = ceil(($size[0]/$size[1]) * $height);
			else
				$height = ceil($width / ($size[0]/$size[1]));
		}

		$relfile = $this->webroot.$uploadsDir.'/'.$this->cacheDir.'/thumb.'.$width.'x'.$height.'.'.basename($path); // relative file
		
		$cachefile = $fullpath.$this->cacheDir.DS.'thumb.'.$width.'x'.$height.'.'.basename($path);  // location on server

		if (file_exists($cachefile)) {
			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
			if (@filemtime($cachefile) < @filemtime($url)) // check if up to date
				$cached = false;
		} else {
			$cached = false;
		}

		if (!$cached) {
			$resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height);
		} else {
			$resize = false;
		}

		if ($resize) {
			if(exif_imagetype($url) >= 1 && exif_imagetype($url) <= 16){
				App::import('Vendor', 'GeecktecFilemanager.ThumbLib', array('file' => 'phpthumbnailer'.DS.'ThumbLib.inc.php'));
				$this->PhpThumbFactory = new PhpThumbFactory;
				$thumb = $this->PhpThumbFactory->create($url);
				
				if($adaptive){	
					$thumb->adaptiveResize($width,$height);	
				}else{
					$thumb->resize($width, $height);
				}
				
				$thumb->save($cachefile);	
			}else{
				return; // No image!	
			}
		} else {
			//copy($url, $cachefile);
		}
		return $this->output(sprintf($this->Html->tags['image'], $relfile, $this->Html->_parseAttributes($htmlAttributes, null, '', ' ')), $return);		
	}
}
?>