<?php

namespace AppBundle\Services;

use AppBundle\Entity\Room;
use Imagine\Image\Box;

class RoomImageResizer extends AbstractImageResizer
{
    /**
     * Execute resize
     *
     * @param Room $object
     * @param string $fullInputfile
     * @param string $relativeOutputFilePath
     * @param string $fullOutputFilePath
     * @param int $size
     * @param int $quality
     * @return string
     */
    protected function execute($object, $fullInputfile, $relativeOutputFilePath, $fullOutputFilePath, $size, $quality): string
    {
        list($width, $height) = getimagesize($fullInputfile);
        $ratio = $height / $width;
        $newWidth = $size;

        if ($width < $newWidth) {
            $newWidth = $width;
        }

        $newHeight = ceil($newWidth * $ratio);

        $this->createFileDirectory($fullOutputFilePath);

        $box = new Box($newWidth, $newHeight);
        $this->imagine->open($fullInputfile)
            ->resize($box)
            ->save($fullOutputFilePath, ['jpeg_quality' => $quality]);

        return $relativeOutputFilePath;
    }

    /**
     * @inheritdoc
     */
    protected function getImageUrl($object)
    {
        return $object->getImageUrl();
    }
}
