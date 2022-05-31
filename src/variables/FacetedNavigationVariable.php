<?php
/**
 * Faceted Navigation plugin for Craft CMS 3.x/4.x
 *
 * Faceted Navigation
 *
 * @link      https://swishdigital.co
 * @copyright Copyright (c) 2019-2022 Swish Digital
 */

namespace swishdigital\facetednavigation\variables;

use swishdigital\facetednavigation\FacetedNavigation;

use Craft;

/**
 * @author    Swish Digital
 * @package   FacetedNavigation
 * @since     1.0.0
 */
class FacetedNavigationVariable
{
    // Public Methods
    // ==============

    public function buildFacets($categoryHandles = array())
    {
        if(empty($categoryHandles))
        {
            throw new HttpException('501', 'No category group handles supplied in createFacets, eg: craft.facetedNavigation.createFacets(["plants", "sun", "soil"])');
        }
        return FacetedNavigation::$plugin->navigation->buildFacets($categoryHandles);
    }
}
