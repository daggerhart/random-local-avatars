<?php
/**
 * Plugin Name: Random Local Avatars
 * Plugin URI: https://github.com/daggerhart/random-local-avatars
 * Description: Because privacy reasons.
 * Author: Jonathan Daggerhart
 * Author URI: http://daggerhart.com
 * Version: 1.2
 * Requires PHP: 5.4
 * License: GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// initialize the plugin
call_user_func(function () {
  new RandomLocalAvatarsPlugin();
});

/**
 * Class RandomLocalAvatars
 */
class RandomLocalAvatarsPlugin {

  /**
   * WP option 'avatar_default'.
   *
   * @var string
   */
  public $wp_avatar_default;

  /**
   * @var \RandomLocalAvatars\FileSystem
   */
  public $fileSystem;

  /**
   * @var \RandomLocalAvatars\GeneratorRegistry
   */
  public $registry;

  /**
   * RandomLocalAvatars constructor.
   */
  public function __construct() {
    // Only register plugin hooks once.
    if (!defined('RANDOM_LOCAL_AVATARS_HOOKS_REGISTERED')) {
      add_action('plugins_loaded', [$this, 'wp_plugins_loaded']);
      add_action('random_local_avatars_initialize_generators', [$this, 'wp_random_local_avatars_initialize_generators'], 0);

      add_filter('avatar_defaults', [$this, 'wp_avatar_defaults']);
      add_filter('user_profile_picture_description', [$this, 'wp_user_profile_picture_description'], 20, 2);
      add_filter('get_avatar_url', [$this, 'wp_get_avatar_url'], 20, 3);

      define('RANDOM_LOCAL_AVATARS_HOOKS_REGISTERED', TRUE);
    }
  }

  /**
   * Setup all the internal dependencies of this plugin.
   */
  public function wp_plugins_loaded() {
    $this->wp_avatar_default = get_option('avatar_default', 'mystery');

    $this->registry = new \RandomLocalAvatars\GeneratorRegistry();
    do_action('random_local_avatars_initialize_generators', $this->registry);

    $this->fileSystem = new \RandomLocalAvatars\FileSystem();

    if ($this->isActive()) {
      $this->fileSystem->setGenerator($this->registry->getGenerator($this->wp_avatar_default));
    }
  }

  /**
   * Action provided by this plugin for registering custom generators.
   *
   * @param $registry \RandomLocalAvatars\GeneratorRegistry
   */
  public function wp_random_local_avatars_initialize_generators($registry) {
    $registry->addGenerator(new \RandomLocalAvatars\Generator\Quilted());
    $registry->addGenerator(new \RandomLocalAvatars\Generator\Rings());
    $registry->addGenerator(new \RandomLocalAvatars\Generator\RetroPixels());
    $registry->addGenerator(new \RandomLocalAvatars\Generator\Monster());
  }

  /**
   * WP Filter get_avatar_url.
   *
   * @param $url
   * @param $id_or_email
   * @param $args
   *
   * @return string
   */
  public function wp_get_avatar_url($url, $id_or_email, $args) {
    // If forcing defaults, return the original url.
    if (!empty($args['force_default'])) {
      return $this->getDefaultAvatarUrl($url, $args);
    }

    // If no generator provided by this plugin is enabled, return original url.
    if (!$this->isActive()) {
      return $url;
    }

    $hash = $this->createAvatarHash($id_or_email);
    $size = $args['size'];

    return $this->fileSystem->generateAvatarUrl($hash, $size);
  }

  /**
   * WP Filter 'avatar_defaults'
   *
   * @param $defaults
   *
   * @return array
   */
  public function wp_avatar_defaults($defaults) {
    /** @var \RandomLocalAvatars\Generator\GeneratorInterface $generator */
    foreach ($this->registry->generators() as $generator) {
      $defaults[$generator->id()] = $generator->label() . ' (' . __('Random Local Avatars') . ')';
    }

    return $defaults;
  }

  /**
   * WP filter 'user_profile_picture_description'
   *
   * @param $description
   * @param $profileuser
   *
   * @return string
   */
  public function wp_user_profile_picture_description($description, $profileuser) {
    if ($this->isActive()) {
      $description = __('Your user picture is generated automatically.');
    }

    return $description;
  }

  /**
   * Simple check to see if the currently enabled avatar option is provided by
   * this plugin.
   *
   * @return bool
   */
  public function isActive() {
    return $this->registry->isGenerator($this->wp_avatar_default);
  }

  /**
   * Return the default avatar url for a given generator or core WP avatar
   * thing.
   *
   * @param $url
   * @param $args
   *
   * @return mixed
   */
  public function getDefaultAvatarUrl($url, $args) {
    // If this is not an avatar type handled by this plugin, return original url.
    if (stripos($args['default'], 'random_local_avatars') === FALSE) {
      return $url;
    }

    $generator = $this->registry->getGenerator($args['default']);

    $this->fileSystem->setGenerator($generator);
    $hash = $this->createAvatarHash($args['default']);
    $size = $args['size'];

    return $this->fileSystem->generateAvatarUrl($hash, $size);
  }

  /**
   * Create a hash from the myriad of user data that could be passed into the
   * 'get_avatar_url' WP filter.
   *
   * @param $data
   *
   * @return string
   */
  protected function createAvatarHash($data) {
    $unique = NULL;

    if (is_numeric($data)) {
      $user = get_userdata((int) $data);
    }
    else if (is_string($data)) {
      $user = get_user_by('email', $data);
    }
    else if (is_a($data, 'WP_Post')) {
      $user = get_userdata((int) $data->user_id);
    }

    // If we have a valid user, get their email.
    if (!empty($user) && is_a($user, 'WP_User')) {
      $unique = $user->user_email;
    }

    if (empty($unique) && is_a($data, 'WP_Comment')) {
      $unique = $data->comment_author_email;
    }

    if (empty($unique)) {
      $unique = maybe_serialize($data);
    }

    return md5($unique);
  }

}
