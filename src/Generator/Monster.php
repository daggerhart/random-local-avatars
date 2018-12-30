<?php

namespace RandomLocalAvatars\Generator;

use SandFoxMe\MonsterID\Monster as MonsterID;

/**
 * Class Quilted
 *
 * @link https://github.com/sandfoxme/monsterid
 */
class Monster implements GeneratorInterface {

  /**
   * {@inheritdoc}
   */
  public function id() {
    return 'random_local_avatars_monster';
  }

  /**
   * {@inheritdoc}
   */
  public function subdir() {
    return 'monster';
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return __('Monster');
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
    $icon = new MonsterID($hash);

    return $icon->build($size);
  }

}
