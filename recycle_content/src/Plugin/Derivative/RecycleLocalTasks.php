<?php
/**
 * @file
 * Contains \Drupal\recycle_content\Plugin\Derivative\DynamicLocalTasks.
 */

namespace Drupal\recycle_content\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\multiversion\MultiversionManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines dynamic local tasks.
 */
class RecycleLocalTasks extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The multiversion manager service.
   *
   * @var \Drupal\multiversion\MultiversionManagerInterface
   */
  protected $multiversionManager;
  
  /**
   * Constructs a RecycleLocalTasks object.
   *
   * @param \Drupal\multiversion\MultiversionManagerInterface $entity_manager
   *   The entity type manager.
   */
  public function __construct(MultiversionManagerInterface $multiversion_manager) {
    $this->multiversionManager = $multiversion_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('multiversion.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    foreach ($this->multiversionManager->getEnabledEntityTypes() as $entity_type_id => $entity_type) {
      $this->derivatives["recycle_content_$entity_type_id"] = $base_plugin_definition;
      $this->derivatives["recycle_content_$entity_type_id"]['title'] = $entity_type->get('label');
      $this->derivatives["recycle_content_$entity_type_id"]['route_name'] = 'recycle_content.entity_list';
      $this->derivatives["recycle_content_$entity_type_id"]['parent_id'] = 'recycle_content.default';
      $this->derivatives["recycle_content_$entity_type_id"]['route_parameters'] = array('entity_type_id' => $entity_type_id);
    }
    return $this->derivatives;
  }

}
