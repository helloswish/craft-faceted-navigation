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

namespace swishdigital\facetednavigation\services;

use swishdigital\facetednavigation\FacetedNavigation;

use Craft;
use craft\base\Component;
use craft\elements\Category;

/**
 * @author    Swish Digital
 * @package   FacetedNavigation
 * @since     1.0.0
 */
class Navigation extends Component
{
    // Public Methods
    // ==============

    var $activeFilters = array();
    var $activeCategories = array();
    var $categoryGroups = array();
    var $categories = array();
    var $categoryHandles = array();

    public function buildFacets($categoryHandles) {

        $this->categoryHandles = (is_array($categoryHandles)) ? $categoryHandles : array($categoryHandles);
        $this->_setActiveFilters();
        $this->_setCategoryGroups();
        $this->_setCategories();

        $r = array(
            'activeFilters'     => $this->activeFilters,
            'categoryGroups'    => $this->categoryGroups,
            'categories'        => $this->categories,
            'activeCategories'  => $this->activeCategories
        );

        return $r;
    }


    private function _buildUri($slug, $group)
    {
        
        $activeGroups = array();
        $add = $remove = '';

        foreach($this->categoryHandles as $key)
        {
            if(isset($this->activeFilters[$key]))
            {
                $activeGroups[] = $key;
                $filters = $this->activeFilters[$key];
                $add .= '/'.$key.'/'.implode('|', $filters);

                if(!in_array($slug, $this->activeFilters[$key]) && $key == $group)
                {
                    $add .= '|'.$slug;
                }

                foreach($filters as $k => $filter)
                {
                    if($slug == $filter)
                    {
                        unset($filters[$k]);
                    }
                }

                if(!empty($filters))
                {
                    $remove .= '/'.$key.'/'.implode('|', $filters);
                }
            }
        }

        if(!in_array($group, $activeGroups))
        {
            $add .= '/'.$group.'/'.$slug;
        }
        
        return array('add' => $add, 'remove' => $remove);

    }


    private function _setCategories()
    {
        
        foreach($this->categoryGroups as $group)
        {

            $categories = Category::find()
                ->group($group['handle'])
                ->all();

            foreach($categories as $category)
            {
                $active = false;
                if(isset($this->activeFilters[$group['handle']]))
                {
                    if(in_array($category->attributes['slug'], $this->activeFilters[$group['handle']]))
                    {
                        $active = true;
                    }
                }
                $data = array(
                    'attributes' => $category->attributes,
                    'title' => $category->title,
                    'active' => $active,
                    'url' => $this->_buildUri($category->attributes['slug'], $group['handle']),
                    'model' => $category
                );
                $this->categories[$group['handle']][] = $data;

                if($active)
                {
                    $this->activeCategories[$category->attributes['slug']] = $data;
                }
            }
        }
        
    }

    private function _setCategoryGroups()
    {
        foreach($this->categoryHandles as $handle)
        {
            $catGroup = Craft::$app->categories->getGroupByHandle($handle);
            $this->categoryGroups[$handle] = $catGroup->attributes;
        }
    }

    private function _setActiveFilters()
    {

        $segments = Craft::$app->request->getSegments();

        foreach($segments as $key => $segment)
        {
            if(in_array($segment, $this->categoryHandles) && isset($segments[$key+1]))
            {
                $this->activeFilters[$segment] = explode('|', $segments[$key+1]);
            }
        }
        
        asort($this->activeFilters);
        
    }

}
