<?php

namespace AppBundle\Services;

interface ImageResizerStrategy
{
    /**
     * @param object $object
     * @param $resolution
     * @return string
     */
    public function resize($object, $resolution);
}
