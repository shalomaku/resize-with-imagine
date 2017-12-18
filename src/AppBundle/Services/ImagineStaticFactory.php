<?php

namespace AppBundle\Services;

use Imagine\Gd\Imagine as GDImagine;
use Imagine\Gmagick\Imagine as GImagine;
use Imagine\Image\ImagineInterface;
use Imagine\Imagick\Imagine as IImagine;

class ImagineStaticFactory
{
    /**
     * @return ImagineInterface
     */
    public static function getImagine() {
        if (class_exists('Imagick')) {
            $imagine = new IImagine();
        } else if (class_exists('Gmagick')) {
            $imagine = new GDImagine();
        } else {
            $imagine = new GImagine();
        }
        return $imagine;
    }
}
