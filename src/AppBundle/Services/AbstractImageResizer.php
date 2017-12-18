<?php

namespace AppBundle\Services;

use Imagine\Image\ImagineInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class ImageResizer
 * @package AppBundle\Services
 */
abstract class AbstractImageResizer implements ImageResizerStrategy
{
    /**
     * @var string
     */
    protected $outputDir;

    /**
     * @var string
     */
    protected $resolutions;

    /**
     * @var string
     */
    protected $webDir;

    /**
     * @var ImagineInterface
     */
    protected $imagine;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var string
     */
    private $class;

    /**
     * BackgroundImageResizer constructor.
     * @param string $class
     * @param ImagineInterface $imagine
     * @param Filesystem $filesystem
     * @param $webDir
     * @param $outputDir
     * @param $resolutions
     */
    public function __construct($class, ImagineInterface $imagine, Filesystem $filesystem, $webDir, $outputDir, $resolutions)
    {
        $this->imagine = $imagine;
        $this->webDir = $webDir;
        $this->outputDir = $outputDir;
        $this->resolutions = $resolutions;
        $this->fs = $filesystem;
        $this->class = $class;
    }

    /**
     * @param object $object
     * @return string
     */
    protected abstract function getImageUrl($object);

    /**
     * Execute resize
     *
     * @param $object
     * @param $fullInputFile
     * @param $relativeOutputFile
     * @param $fullOutputFile
     * @param $size
     * @param $quality
     * @return string
     */
    protected abstract function execute($object, $fullInputFile, $relativeOutputFile, $fullOutputFile, $size, $quality): string;

    /**
     * @param object $object
     * @param $resolution
     * @return mixed|string
     */
    public function resize($object, $resolution): string
    {
        $this->check($object);
        $inputFile = $this->getFullWebPath($this->getImageUrl($object));
        $fullOutputFilePath = $this->generateOutputFile($this->getImageUrl($object), $resolution, true);
        $relativeOutputFilePath = $this->generateOutputFile($this->getImageUrl($object), $resolution, false);

        if ($this->fs->exists($fullOutputFilePath)) {
            return $relativeOutputFilePath;
        }

        if (!$this->fs->exists($inputFile)) {
            throw new FileNotFoundException($inputFile);
        }

        return $this->execute($object, $inputFile, $relativeOutputFilePath, $fullOutputFilePath, $this->getSize($resolution), $this->getQuality($resolution));
    }

    /**
     * @param $resolution
     * @return int
     */
    protected function getQuality($resolution): int
    {
        $quality = 75;
        if (isset($this->resolutions[$resolution]) and isset($this->resolutions[$resolution]['quality'])) {
            $quality = $this->resolutions[$resolution]['quality'];
        }

        return $quality;
    }

    /**
     * @param $resolution
     * @return int
     */
    protected function getSize($resolution)
    {
        $size = 0;
        if (isset($this->resolutions[$resolution]) and isset($this->resolutions[$resolution]['size'])) {
            $size = $this->resolutions[$resolution]['size'];
        }

        return $size;
    }

    /**
     * @param $fileName
     * @return string
     */
    protected function getFullWebPath($fileName)
    {
        return $this->webDir . '/'.trim($fileName, '/');
    }

    /**
     * @param $inputFileName
     * @param $resolution
     * @param bool $fullPath
     * @return string
     */
    protected function generateOutputFile($inputFileName, $resolution, $fullPath = true)
    {
        $outputFile = "{$this->outputDir}/$resolution/" . trim($inputFileName, "/");
        if ($fullPath) {
            $outputFile = $this->getFullWebPath($outputFile);
        }

        return $outputFile;
    }

    /**
     * @param $file
     */
    protected function createFileDirectory($file)
    {
        $dir = dirname($file);
        if (!$this->fs->exists($dir)) {
            $this->fs->mkdir($dir, 0777);
        }
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param $object
     */
    protected function check($object)
    {
        if (!$object instanceof $this->class)
            throw new \RuntimeException(sprintf('%s expected %s get', $this->class, get_class($object)));
    }
}
