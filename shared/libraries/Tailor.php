<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

/**
 * DiliCMS
 *
 * 一款基于并面向CodeIgniter开发者的开源轻型后端内容管理系统.
 *
 * @package     DiliCMS
 * @author      DiliCMS Team
 * @copyright   Copyright (c) 2011 - 2012, DiliCMS Team.
 * @license     http://www.dilicms.com/license
 * @link        http://www.dilicms.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * DiliCMS Tailor Class
 *
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      chekun (aka Jeongee)
 * @link        http://www.dilicms.com
 */
class Tailor {

    protected $imagick = null;

    protected $ext = '';

    protected $app = null;

    protected $filePath = '';


    public function initialize($image, $ext)
    {
        $this->imagick = new Imagick($image);
        $this->ext = strtolower($ext);
    }

    public function measure($size, $rule)
    {

        if ($this->ext == 'gif') {
            $width = 0;
            $height = 0;
            foreach ($this->imagick as $frame) {
                $width = ($frame->getImageWidth() > $width ? $frame->getImageWidth() : $width);
                $height = ($frame->getImageHeight() > $height ? $frame->getImageHeight() : $height);
            }
        } else {
            $width = $this->imagick->getImageWidth();
            $height = $this->imagick->getImageHeight();
        }

        list($newWidth, $newHeight) = explode('x', $size.'x0');

        $needConvert = $newWidth < $width;

        if ($newHeight) {

            $needConvert = ($needConvert or ($newHeight < $height));

        }

        if (! $needConvert) {

            return $this->imagick;

        }

        $rule = ucfirst($rule).'Rule';

        $ruler = new $rule($this->imagick);

        $this->imagick = $ruler->run($width, $height, $newWidth, $newHeight, $this->ext);

        return $this->imagick;

    }

    public function save($path)
    {
        //if the image is not gif, we will save progressive jpeg thumbnail files.
        if ($this->ext != 'gif') {
            $this->imagick->setFormat('jpg');
            $this->imagick->setInterlaceScheme(Imagick::INTERLACE_PLANE);
            $this->filePath = $path.'.jpg';
            return $this->imagick->writeImage($this->filePath);
        } else {
            $this->filePath = $path.'.gif';
            return $this->imagick->writeImages($this->filePath, true);
        }
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

}

interface RuleInterface {

    public function run($width, $height, $newWidth, $newHeight, $ext);

}

class CropRule implements RuleInterface {

    public function __construct(Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    public function run($width, $height, $newWidth, $newHeight, $ext)
    {
        $offsetX = ($width - $newWidth)/2;
        $offsetY = ($height - $newHeight)/2;
        if (strtolower($ext) == 'gif') {
            $this->imagick = $this->imagick->coalesceImages();
            foreach ($this->imagick as $frame) {
                $frame->thumbnailImage($newWidth, $newHeight, true);
            }
            $this->imagick = $this->imagick->optimizeImageLayers();
        } else {
            $this->imagick->setGravity(Imagick::GRAVITY_CENTER);
            $this->imagick->cropImage($newWidth, $newHeight, $offsetX, $offsetY);
        }
        return $this->imagick;
    }
}

class FillRule implements RuleInterface {

    public function __construct(Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    public function run($width, $height, $newWidth, $newHeight, $ext)
    {
        if ($ext == 'gif') {
            $this->imagick = $this->imagick->coalesceImages();
            foreach ($this->imagick as $frame) {
                $frame->resizeImage($newHeight, $newHeight, Imagick::FILTER_LANCZOS, 1, true);
            }
            $this->imagick = $this->imagick->optimizeImageLayers();
        } else {
            $this->imagick->resizeImage($newWidth, $newHeight, Imagick::FILTER_POINT, 1, true);
        }
        return $this->imagick;
    }
}

class FitRule implements RuleInterface {

    public function __construct(Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    public function run($width, $height, $newWidth, $newHeight, $ext)
    {
        if ($ext == 'gif') {
            $this->imagick = $this->imagick->coalesceImages();
            foreach ($this->imagick as $frame) {
                $frame->resizeImage($newHeight, $newHeight, Imagick::FILTER_LANCZOS, 1, false);
            }
            $this->imagick = $this->imagick->optimizeImageLayers();
        } else {
            $this->imagick->resizeImage($newWidth, $newHeight, Imagick::FILTER_POINT, 1, false);
        }
        return $this->imagick;
    }
}

class FitWidthRule implements RuleInterface {

    public function __construct(Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    public function run($width, $height, $newWidth, $newHeight, $ext)
    {
        if ($newHeight == 0) {
            $newHeight = $newWidth * $height / $width;
        }
        if ($ext == 'gif') {
            $this->imagick = $this->imagick->coalesceImages();
            foreach ($this->imagick as $frame) {
                $frame->resizeImage($newHeight, $newHeight, Imagick::FILTER_LANCZOS, 1, false);
            }
            $this->imagick = $this->imagick->optimizeImageLayers();
        } else {
            $this->imagick->resizeImage($newWidth, $newHeight, Imagick::FILTER_POINT, 1, false);
        }
        return $this->imagick;
    }
}

/* End of file Tailor.php */
/* Location: ./shared/libraries/Tailor.php */
