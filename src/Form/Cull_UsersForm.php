<?php

namespace Drupal\cull_users\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class Cull_UsersForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cull_users_form';
  }

 /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('cull_users.settings');
    // Page title field.
    $form['page_title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cull Users generator page title:'),
      '#default_value' => $config->get('cull_users.page_title'),
      '#description' => $this->t('Give your Cull Users generator page a title.'),
    );
    // Source text field.
    $form['source_text'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Source text for Cull Users generation:'),
      '#default_value' => $config->get('cull_users.source_text'),
      '#description' => $this->t('Write one sentence per line. Those sentences will be used to generate random text.'),
    );

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('cull_users.settings');
    $config->set('cull_users.source_text', $form_state->getValue('source_text'));
    $config->set('cull_users.page_title', $form_state->getValue('page_title'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cull_users.settings',
    ];
  }
  }
