<?php

namespace RandomLocalAvatars;

class GeneratorRegistry {

  /**
   * @var \RandomLocalAvatars\Generator\GeneratorInterface[]
   */
  protected $generators = [];

  /**
   * @return \RandomLocalAvatars\Generator\GeneratorInterface[]
   */
  public function generators() {
    return $this->generators;
  }

  /**
   * Add a generator to the registry.
   *
   * @param $generator \RandomLocalAvatars\Generator\GeneratorInterface
   */
  public function addGenerator($generator) {
    if (version_compare(PHP_VERSION, $generator->minPHPVersion(), '>=')) {
      $this->generators[$generator->id()] = $generator;
    }
  }

  /**
   * Test if a generator exists within the registry.
   *
   * @param $key
   *
   * @return bool
   */
  public function isGenerator($key) {
    return !empty($this->generators[$key]);
  }

  /**
   * Get a generator if it exists.
   *
   * @param $key
   *
   * @return null|\RandomLocalAvatars\Generator\GeneratorInterface
   */
  public function getGenerator($key) {

    if ($this->isGenerator($key)) {
      return $this->generators[$key];
    }

    return NULL;
  }

}
