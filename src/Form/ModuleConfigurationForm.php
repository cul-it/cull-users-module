<?php

namespace Drupal\cull_users\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cull_users_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cull_users.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('cull_users.settings');
    $form['status'] = [
      '#type' => 'details',
      '#title' => $this->t('Cron status information'),
      '#open' => TRUE,
    ];
    $form['status']['intro'] = [
      '#type' => 'item',
      '#markup' => $this->t('Cull Users will periodically delete users who have no special roles assigned to them on this site.'),
    ];

    $next_execution = \Drupal::state()->get('cull_users.next_execution');
    $next_execution = !empty($next_execution) ? $next_execution : REQUEST_TIME;

    $args = [
      '%time' => date_iso8601(\Drupal::state()->get('cull_users.next_execution')),
      '%seconds' => $next_execution - REQUEST_TIME,
    ];
    $form['status']['last'] = [
      '#type' => 'item',
      '#markup' => $this->t('cull_users_cron() will next execute the first time cron runs after %time (%seconds seconds from now)', $args),
    ];
    $form['configuration'] = [
      '#type' => 'details',
      '#title' => $this->t('Configuration of cull_users_cron()'),
      '#open' => TRUE,
    ];
    $form['configuration']['cull_users_interval'] = [
      '#type' => 'select',
      '#title' => $this->t('Cron interval'),
      '#description' => $this->t('Time after which cull_users_cron will respond to a processing request.'),
      '#default_value' => $config->get('interval'),
      '#options' => [
        60 => $this->t('1 minute'),
        300 => $this->t('5 minutes'),
        3600 => $this->t('1 hour'),
        86400 => $this->t('1 day'),
      ],
    ];
   return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('cull_users.settings');
    $values = $form_state->getValues();
    if ($values['cull_users_interval'] != $config->get('interval')) {
      $this->config('cull_users.settings')
        ->set('interval', $values['cull_users_interval'])
        ->save();

      \Drupal::state()->set('cull_users.next_execution', REQUEST_TIME + $values['cull_users_interval']);
    }
  }

}
