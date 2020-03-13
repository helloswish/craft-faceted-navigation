<?php
/**
 * Faceted Navigation plugin for Craft CMS 3.x
 *
 * Provides faceted navigation of entries, using categories, which allows site users to narrow the list of entries they see by applying multiple filters (think Amazon or eBay left sidebar).
 *
 * @link      https://helloswish.com
 * @copyright Copyright (c) 2019 Swish Digital
 *
 * Adapted for Craft 3.x with permission from its original
 * author, the incomparable Iain Urquhart (http://iain.co.nz)
 */

namespace swishdigital\facetednavigation;

use swishdigital\facetednavigation\models\Settings;
use swishdigital\facetednavigation\services\Navigation as NavigationService;
use swishdigital\facetednavigation\variables\FacetedNavigationVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class FacetedNavigation
 *
 * @author    Swish Digital
 * @package   FacetedNavigation
 * @since     1.0.0
 *
 * @property  NavigationService $navigation
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class FacetedNavigation extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var FacetedNavigation
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('facetedNavigation', FacetedNavigationVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'faceted-navigation',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'faceted-navigation/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
