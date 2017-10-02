<?php

namespace Drupal\extlink_extra\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Utility\Token;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExtlinkExtraController extends ControllerBase {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, Token $token) {
    $this->configFactory = $config_factory;
    $this->token = $token;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('token')
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

    return $this->token->replace($page_title, $extlink_token_data);
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
