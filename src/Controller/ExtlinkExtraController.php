<?php

namespace Drupal\extlink_extra\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExtlinkExtraController extends ControllerBase {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * Title callback.
   *
   * @return string
   */
  public function getTitle() {
    $config = $this->configFactory->get('extlink_extra.settings');
    $extlink_token_data = [
      'extlink' => [
        'external_url' => $_COOKIE['external_url'],
        'back_url' => $_COOKIE['back_url'],
      ],
    ];

    $page_title = $config->get('extlink_page_title') ?: NULL;

    return token_replace($page_title, $extlink_token_data);
  }

  /**
   * Render the markup for the exit modal.
   *
   * @return array
   */
  public function leave() {
    return [
      '#theme' => 'extlink_extra_leaving',
    ];
  }
}
