<?php

namespace Drupal\cull_users\Controller;

use Drupal\Core\Url;
// Change following https://www.drupal.org/node/2457593
use Drupal\Component\Utility\SafeMarkup;

/**
 * Controller routines for Lorem ipsum pages.
 */
class Cull_UsersController {

  /**
   * Removes users without roles.
   * This callback is mapped to the path
   * 'cull_users/cull'.
   *
   */
  public function cull() {
    // Default settings.
    $config = \Drupal::config('cull_users.settings');
    // Page title and source text.
    $page_title = $config->get('cull_users.page_title');
    $source_text = $config->get('cull_users.source_text');

    $element['#title'] = SafeMarkup::checkPlain($page_title);

    $element['#source_text'] = array();
    $repertory = explode(PHP_EOL, $source_text);
    foreach ($repertory as $key => $value) {
      $element['#source_text'][] = SafeMarkup::checkPlain($value);
    }

    // Theme function.
    $element['#theme'] = 'cull_users';

    return $element;
  }
}
