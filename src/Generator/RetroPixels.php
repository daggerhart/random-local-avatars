<?php

namespace RandomLocalAvatars\Generator;

use Identicon\Identicon;

/**
 * Class Quilted
 *
 * @link https://github.com/yzalis/Identicon
 */
class RetroPixels implements GeneratorInterface {

  /**
   * {@inheritdoc}
   */
  public function id() {
    return 'random_local_avatars_retro_pixels';
  }

  /**
   * {@inheritdoc}
   */
  public function subdir() {
    return 'retro_pixels';
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return __('Retro Pixels');
  }

  /**
   * {@inheritdoc}
   */
  public function minPHPVersion() {
    return '5.5';
  }

  /**
   * {@inheritdoc}
   */
  public function generateBits($hash, $size) {
    $icon = new Identicon();

    return $icon->getImageData($hash, $size);
  }

}
