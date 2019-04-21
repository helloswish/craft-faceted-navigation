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
