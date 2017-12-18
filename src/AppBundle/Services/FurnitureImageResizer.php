<?php

namespace AppBundle\Services;

use AppBundle\Entity\Furniture;
use Imagine\Image\Box;

class FurnitureImageResizer extends AbstractImageResizer
{
    /**
     * Execute resize
     *
     * @param Furniture $object
     * @param $fullInputfile
     * @param $relativeOutputFilePath
     * @param $fullOutputFilePath
     * @param $size
     * @param $quality
     * @return string
     * @internal param $resolution
     */
    public function execute($object, $fullInputfile, $relativeOutputFilePath, $fullOutputFilePath, $size, $quality): string
    {
        //Get image default size for ratio computing
        list($width, $height) = getimagesize($fullInputfile);
        $ratio = $width / $height;

        //Compute product height
        $bgScale = $this->getScale($object);
        $productScale = $object->getHeight() / 100 * $bgScale;
        $pieceWidth = $size;
        $newHeight = $productScale * $pieceWidth;


        if ($newHeight > $height) {
            $newHeight = $height;
        }

        $newWidth = floor($newHeight * $ratio);

        $this->createFileDirectory($fullOutputFilePath);

        $box = new Box($newWidth, $newHeight);
        $this->imagine->open($fullInputfile)
            ->resize($box)
            ->save($fullOutputFilePath, [
                'png_compression_level' => ceil($quality * 9 / 100)
            ]);

        return $relativeOutputFilePath;
    }

    /**
     * @param Furniture $product
     *
     * @return float
     */
    private function getScale(Furniture $product): float
    {
        $scale = 0;
        $room = $product->getRoom();
        if (null !== $room)
        {
            $scale = $room->getScale();
        }

        return $scale;
    }

    /**
     * @inheritdoc
     */
    protected function getImageUrl($object)
    {
        return $object->getImageUrl();
    }
}
