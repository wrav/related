<?php
/**
 * Related plugin for Craft CMS 3.x
 *
 * A simple plugin that adds a widget within the Craft CP page sidebar, allowing you to quickly and easily access related entries.
 *
 * @link      https://github.com/reganlawton
 * @copyright Copyright (c) 2019 reganlawton
 */

namespace wrav\related\services;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\db\Query;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\MatrixBlock;
use craft\elements\User;
use wrav\related\Related;

/**
 * RelatedService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    reganlawton
 * @package   Related
 * @since     1.0.0
 */
class RelatedService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * @return array
     */
    public function getRelated($elementId)
    {
        if (!$elementId) {
            return [];
        }

        /** @var Element $element */
        $element = Craft::$app->elements->getElementById($elementId);
        /** @var Element $elementType */
        $elementType = Craft::$app->elements->getElementTypeById($elementId);

        $query = MatrixBlock::find();
        $query->relatedTo = $element;
        $query->anyStatus();
        /** @var MatrixBlock[] $blocks */
        $blocks = $query->all();

        $matchingBlocks = [];
        foreach ($blocks as $block) {
            $owner = $block->getOwner();
            if ($owner) {
                $matchingBlocks[$owner->id] = $owner;
            }
        }

        /** @var Query $query */
        $query = Entry::find();
        $query->relatedTo = $element;
        $query->anyStatus();
        /** @var Element[] $entries */
        $entries = $query->all();

//        dd($entries);

        /** @var Query $query */
        $query = Category::find();
        $query->relatedTo = $element;
        $query->anyStatus();
        /** @var Element[] $categories */
        $categories = $query->all();

        /** @var Query $query */
        $query = User::find();
        $query->relatedTo = $element;
        $query->anyStatus();
        /** @var Element[] $users */
        $users = $query->all();

        /** @var Query $query */
        $query = User::find();
        $query->relatedTo = $element;
        $query->anyStatus();
        /** @var Element[] $users */
        $users = $query->all();

        $elements = array_merge(
            $entries,
            $categories,
            $users,
        );

        return collect(
            array_merge(
                $matchingBlocks,
                $elements
            )
        )->unique('id')->toArray();
    }
}
