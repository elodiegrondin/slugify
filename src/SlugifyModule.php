<?php
/**
 * Slugify module for Craft CMS 3.x
 *
 * Module to update slug on save entry to match with the title.
 *
 * @link      https://www.everythingisfun.com
 * @copyright Copyright (c) 2019 Everything is Fun
 */

namespace modules\slugifymodule;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;

use craft\base\Element;
use craft\helpers\ElementHelper;

use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;

/**
* Class SlugifyModule
*
* @author    Everything is Fun
* @package   SlugifyModule
* @since     1.0.0
*
*/

class SlugifyModule extends Module {
  // Static Properties
  // =================

  /**
  * @var SlugifyModule
  */
  public static $instance;

  // Public Methods
  // ==============

  public function __construct($id, $parent = null, array $config = []) {
    Craft::setAlias('@modules/slugifymodule', $this->getBasePath());
    $this->controllerNamespace = 'modules\slugifymodule\controllers';
    static::setInstance($this);
    parent::__construct($id, $parent, $config);
  }

  public function init() {
   
    parent::init();
    self::$instance = $this;

    // -- Register "Entry Before Save" Events --
    Event::on(
      Element::class,
      Element::EVENT_BEFORE_SAVE,
      function(craft\events\ModelEvent $event) {
        $entry = $event->sender;

        if (!\craft\helpers\ElementHelper::isDraftOrRevision($entry)) {
          // -- Update slug to match the title if updated --
          if (!empty($entry->id)) $entry->slug = ($entry->title !== null) ? \craft\helpers\StringHelper::slugify($entry->title) : $entry->slug;
        }
      },
      false
    );

  }

  // Protected Methods
  // =================
}
