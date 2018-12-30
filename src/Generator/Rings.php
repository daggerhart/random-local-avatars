<?php

namespace RandomLocalAvatars\Generator;

/**
 * Class Rings
 *
 * Pretty much stolen from here:
 *
 * @link https://github.com/splitbrain/php-ringicon
 *
 * Had to steal it instead of use it because the library had some limitations.
 *
 * @package RandomLocalAvatars\Generator
 */
class Rings implements GeneratorInterface {

  /**
   * @var int
   */
  protected $size;

  /**
   * @var int
   */
  protected $fullsize;

  /**
   * @var int
   */
  protected $rings;

  /**
   * @var int
   */
  protected $center;

  /**
   * @var int
   */
  protected $ringwidth;

  /**
   * @var string
   */
  protected $seed;

  /**
   * @return string
   */
  public function id() {
    return 'random_local_avatars_rings';
  }

  /**
   * {@inheritdoc}
   */
  public function subdir() {
    return 'rings';
  }

  /**
   * @return string
   */
  public function label() {
    return __('Rings');
  }

  /**
   * {@inheritdoc}
   */
  public function minPHPVersion() {
    return '5.3';
  }

  /**
   * @param $hash string
   * @param $size int
   *
   * @return mixed
   */
  public function generateBits($hash, $size) {
    return $this->createImageData($hash, $size);
  }

  /**
   * Allocate a transparent color
   *
   * @param resource $image
   *
   * @return int
   */
  protected function transparentColor($image) {
    return imagecolorallocatealpha($image, 0, 0, 0, 127);
  }

  /**
   * Allocate a random color
   *
   * @param $image
   *
   * @return int
   */
  protected function randomColor($image) {
    return imagecolorallocate($image, $this->rand(0, 255), $this->rand(0, 255), $this->rand(0, 255));
  }

  /**
   * Generate number from seed
   *
   * Each call runs MD5 on the seed again
   *
   * @param int $min
   * @param int $max
   *
   * @return int
   */
  protected function rand($min, $max) {
    $this->seed = md5($this->seed);
    $rand = hexdec(substr($this->seed, 0, 8));
    return ($rand % ($max - $min + 1)) + $min;
  }

  /**
   * Version of createImage() that doesn't output directly.
   *
   * @see RingIcon::createImage()
   */
  public function createImageData($seed, $size, $rings = 3) {
    $this->size = $size;
    $this->fullsize = $this->size * 5;
    $this->rings = $rings;

    $this->center = floor($this->fullsize / 2);
    $this->ringwidth = floor($this->fullsize / $rings);

    $this->size = $size;

    if (!$seed) {
      $seed = mt_rand() . time();
    }
    $this->seed = $seed;

    // create
    $image = $this->createTransparentImage($this->fullsize, $this->fullsize);
    $arcwidth = $this->fullsize;

    for ($i = $this->rings; $i > 0; $i--) {
      $this->drawRing($image, $arcwidth);
      $arcwidth -= $this->ringwidth;
    }

    // resample for antialiasing
    $out = $this->createTransparentImage($this->size, $this->size);
    imagecopyresampled($out, $image, 0, 0, 0, 0, $this->size, $this->size, $this->fullsize, $this->fullsize);

    ob_start();
    imagepng($out);
    $image_data = ob_get_clean();

    imagedestroy($out);
    imagedestroy($image);

    return $image_data;
  }

  /**
   * Drawas a single ring
   *
   * @param resource $image
   * @param int $arcwidth outer width of the ring
   */
  protected function drawRing($image, $arcwidth) {
    $color = $this->randomColor($image);
    $transparency = $this->transparentColor($image);

    $start = $this->rand(20, 360);
    $stop = $this->rand(20, 360);
    if ($stop < $start) {
      list($start, $stop) = [$stop, $start];
    }

    imagefilledarc($image, $this->center, $this->center, $arcwidth, $arcwidth, $stop, $start, $color, IMG_ARC_PIE);
    imagefilledellipse($image, $this->center, $this->center, $arcwidth - $this->ringwidth, $arcwidth - $this->ringwidth, $transparency);
    imagecolordeallocate($image, $color);
    imagecolordeallocate($image, $transparency);
  }

  /**
   * Create a transparent image
   *
   * @param int $width
   * @param int $height
   *
   * @return resource
   * @throws \Exception
   */
  protected function createTransparentImage($width, $height) {
    $image = @imagecreatetruecolor($width, $height);
    if (!$image) {
      throw new \Exception('Missing libgd support');
    }
    imagealphablending($image, FALSE);
    $transparency = $this->transparentColor($image);
    imagefill($image, 0, 0, $transparency);
    imagecolordeallocate($image, $transparency);
    imagesavealpha($image, TRUE);
    return $image;
  }

}
