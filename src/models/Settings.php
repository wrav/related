<?php
/**
 * Related plugin for Craft CMS 3.x
 *
 * A simple plugin that adds a widget within the Craft CP page sidebar, allowing you to quickly and easily access related entries.
 *
 * @link      https://github.com/reganlawton
 * @copyright Copyright (c) 2019 reganlawton
 */

namespace wrav\related\models;

use wrav\related\Related;

use Craft;
use craft\base\Model;

/**
 * Related Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    reganlawton
 * @package   Related
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $allowedSections;

    /**
     * @var string
     */
    public $allowedCategories;

    /**
     * @var string
     */
    public $allowedAssetVolumes;

}
