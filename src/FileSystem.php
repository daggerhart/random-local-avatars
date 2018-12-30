<?php

namespace RandomLocalAvatars;

/**
 * Class FileSystem
 *
 * @package RandomLocalAvatars
 */
class FileSystem {

  /**
   * Base subdirectory where generated images are stored.
   *
   * @var string
   */
  protected $subdir_base = '/random-local-avatars';

  /**
   * Dynamic subdirectory per generator.
   *
   * @var string
   */
  protected $subdir;

  /**
   * @var \RandomLocalAvatars\Generator\GeneratorInterface
   */
  public $generator;

  /**
   * FileSystem constructor.
   */
  public function __construct() {
    $this->setup();
  }

  /**
   * Ensure basic folder structures are in place.
   */
  public function setup() {
    $dir = wp_upload_dir();

    $avatars_dir = $dir['basedir'] . $this->subdir;

    if (!file_exists($avatars_dir)) {
      wp_mkdir_p($avatars_dir);
    }
  }

  /**
   * Set the active generator.
   *
   * @param $generator \RandomLocalAvatars\Generator\GeneratorInterface
   */
  public function setGenerator($generator) {
    $this->generator = $generator;
    $this->subdir = "{$this->subdir_base}/{$generator->subdir()}";
    $this->setup();
  }

  /**
   * Return an avatar URL. Generate the avatar if the file doesn't exist.
   *
   * @param $hash
   * @param $size
   *
   * @return string
   */
  public function generateAvatarUrl($hash, $size) {
    // Filter uploads directory to include our sub-directories.
    add_filter('upload_dir', [$this, 'upload_dir']);

    $dir = wp_upload_dir();
    $file = "{$hash}-{$size}.png";
    $url = "{$dir['url']}/{$file}";

    if (!file_exists("{$dir['path']}/{$file}")) {
      wp_upload_bits($file, NULL, $this->generator->generateBits($hash, $size));
    }

    // Remove the filter.
    remove_filter('upload_dir', [$this, 'upload_dir']);

    return $url;
  }

  /**
   * Modify the uploads destination.
   *
   * @link https://wordpress.stackexchange.com/questions/25894/how-can-i-organize-the-uploads-folder-by-slug-or-id-or-filetype-or-author
   *
   * @param $path array
   *
   * @return array
   */
  public function upload_dir($path) {
    //remove default subdir (year/month)
    $path['path'] = str_replace($path['subdir'], '', $path['path']);
    $path['url'] = str_replace($path['subdir'], '', $path['url']);
    $path['subdir'] = $this->subdir;
    $path['path'] .= $this->subdir;
    $path['url'] .= $this->subdir;

    return $path;
  }

}
