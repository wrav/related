<?php
/**
 * Related plugin for Craft CMS 3.x
 *
 * A simple plugin that adds a widget within the Craft CP page sidebar, allowing you to quickly and easily access related entries.
 *
 * @link      https://github.com/reganlawton
 * @copyright Copyright (c) 2019 reganlawton
 */

namespace wrav\related;

use Craft;
use craft\base\Plugin;
use craft\events\PluginEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\services\Plugins;
use craft\web\View;
use wrav\related\models\Settings;
use wrav\related\services\RelatedService as RelatedServiceService;
use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    reganlawton
 * @package   Related
 * @since     1.0.0
 *
 * @property  RelatedServiceService $relatedService
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class Related extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Related::$plugin
     *
     * @var Related
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function(PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

        Event::on(
            View::class,
            View::EVENT_END_PAGE,
            function(Event $event) {
                if (Craft::$app->getRequest()->getIsCpRequest()
                    && (
                        preg_match('/^\/.+\/entries\//', Craft::$app->getRequest()->getUrl())
                        || preg_match('/^\/.+\/categories\//', Craft::$app->getRequest()->getUrl())
                        || preg_match('/^\/.+\/users\//', Craft::$app->getRequest()->getUrl())
                        || preg_match('/^\/.+\/myaccount/', Craft::$app->getRequest()->getUrl())
                        || preg_match('/^\/.+\/assets\//', Craft::$app->getRequest()->getUrl())

                    )
                ) {
                    $url = Craft::$app->assetManager->getPublishedUrl('@wrav/related/assetbundles/related/dist/js/Related.js', true);
                    echo "<script src='$url'></script>";

                    $url = Craft::$app->assetManager->getPublishedUrl('@wrav/related/assetbundles/related/dist/css/Related.css', true);
                    echo "<link rel='stylesheet' href='$url'>";
                }
            }
        );

        Event::on(View::class, View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS, function(RegisterTemplateRootsEvent $event) {
            $event->roots['related'] = __DIR__ . '/templates';
        });

        Craft::info(
            Craft::t(
                'related',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml(): string
    {
        $sections = Craft::$app->getSections()->getAllSections();
        $categories = Craft::$app->getCategories()->getAllGroups();
        $assetVolumes = Craft::$app->getVolumes()->getAllVolumes();

        $optionsSections = [];
        $optionsCategories = [];
        $optionsAssetVolumes = [];

        foreach ($sections as $id => $section) {
            $optionsSections[$section->handle] = $section->name;
        }
        $optionsSections['nobodyIsGoingToCallASectionThis'] = 'None';

        foreach ($categories as $id => $category) {
            $optionsCategories[$category->handle] = $category->name;
        }
        $optionsCategories['nobodyIsGoingToCallACategoryThis'] = 'None';

        foreach ($assetVolumes as $id => $volume) {
            $optionsAssetVolumes[$volume->handle] = $volume->name;
        }
        $optionsAssetVolumes['nobodyIsGoingToCallAnAssetVolumeThis'] = 'None';

        return Craft::$app->view->renderTemplate(
            'related/settings',
            [
                'settings' => $this->getSettings(),
                'optionsSections' => $optionsSections,
                'optionsCategories' => $optionsCategories,
                'optionsAssetVolumes' => $optionsAssetVolumes,
            ]
        );
    }
}
