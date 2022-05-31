<?php
/**
 * Faceted Navigation plugin for Craft CMS 3.x/4.x
 *
 * Faceted Navigation
 *
 * @link      https://swishdigital.co
 * @copyright Copyright (c) 2019-2022 Swish Digital
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
    public string $schemaVersion = '1.0.0';

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
    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate(
            'faceted-navigation/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
