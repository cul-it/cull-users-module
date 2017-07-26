<?php

namespace Drupal\Tests\cull_users\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the functionality for the Cull Users.
 *
 * @ingroup cull_users
 *
 * @group cull_users
 * @group examples
 */
class CullUsersTest extends BrowserTestBase {

  /**
   * An editable config object for access to 'cull_users.settings'.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $cronConfig;

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['cull_users', 'node'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    // Create user. Search content permission granted for the search block to
    // be shown.
    $this->drupalLogin($this->drupalCreateUser(['administer site configuration', 'access content']));

    $this->cronConfig = \Drupal::configFactory()->getEditable('cull_users.settings');
  }

  /**
   * Create an example node, test block through admin and user interfaces.
   */
  public function testCullUsersBasic() {
    $assert = $this->assertSession();

    // Pretend that cron has never been run (even though simpletest seems to
    // run it once...).
    \Drupal::state()->set('cull_users.next_execution', 0);
    $this->drupalGet('examples/cron-example');

    // Initial run should cause cull_users_cron() to fire.
    $post = [];
    $this->drupalPostForm('examples/cron-example', $post, t('Run cron now'));
    $assert->pageTextContains('cull_users executed at');

    // Forcing should also cause cull_users_cron() to fire.
    $post['cron_reset'] = TRUE;
    $this->drupalPostForm(NULL, $post, t('Run cron now'));
    $assert->pageTextContains('cull_users executed at');

    // But if followed immediately and not forced, it should not fire.
    $post['cron_reset'] = FALSE;
    $this->drupalPostForm(NULL, $post, t('Run cron now'));
    $assert->statusCodeEquals(200);
    $assert->pageTextNotContains('cull_users executed at');
    $assert->pageTextContains('There are currently 0 items in queue 1 and 0 items in queue 2');

    $post = [
      'num_items' => 5,
      'queue' => 'cull_users_queue_1',
    ];
    $this->drupalPostForm(NULL, $post, t('Add jobs to queue'));
    $assert->pageTextContains('There are currently 5 items in queue 1 and 0 items in queue 2');

    $post = [
      'num_items' => 100,
      'queue' => 'cull_users_queue_2',
    ];
    $this->drupalPostForm(NULL, $post, t('Add jobs to queue'));
    $assert->pageTextContains('There are currently 5 items in queue 1 and 100 items in queue 2');

    $this->drupalPostForm('examples/cron-example', [], t('Run cron now'));
    $assert->responseMatches('/Queue 1 worker processed item with sequence 5 /');
    $assert->responseMatches('/Queue 2 worker processed item with sequence 100 /');
  }

}
