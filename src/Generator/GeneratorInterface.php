<?php

namespace RandomLocalAvatars\Generator;

interface GeneratorInterface {

  /**
   * Simple ID that describes this generator.
   *
   * @return string
   */
  public function id();

  /**
   * Human readable label for the generator.
   *
   * @return string
   */
  public function label();

  /**
   * Subdirectory where images from this generator are stored.
   *
   * @return mixed
   */
  public function subdir();

  /**
   * The PHP Version required to use this library.
   *
   * @return string
   */
  public function minPHPVersion();

  /**
   * Return generated image data.
   *
   * @param $hash string
   * @param $size int
   *
   * @return mixed
   */
  public function generateBits($hash, $size);
}
