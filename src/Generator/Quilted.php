<?php

namespace RandomLocalAvatars\Generator;

use Jdenticon\Identicon;

/**
 * Class Quilted
 *
 * @link https://github.com/dmester/jdenticon-php
 * @link https://jdenticon.com/php-api/
 */
class Quilted implements GeneratorInterface {

  /**
   * {@inheritdoc}
   */
  public function id() {
    return 'random_local_avatars_quilted';
  }

  /**
   * {@inheritdoc}
   */
  public function subdir() {
    return 'quilted';
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return __('Quilted');
  }

  /**
   * {@inheritdoc}
   */
  public function minPHPVersion() {
    return '5.3';
  }

  /**
   * {@inheritdoc}
   */
  public function generateBits($hash, $size) {
    $icon = new Identicon([
      'value' => $hash,
      'size'  => $size,
      'style' => [
        'padding' => 0,
      ],
    ]);

    return $icon->getImageData();
  }

}
