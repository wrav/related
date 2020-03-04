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

use craft\base\Element;
use craft\db\Query;
use craft\elements\MatrixBlock;
use wrav\related\Related;

use Craft;
use craft\base\Component;

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
            if($owner) {
                $matchingBlocks[$owner->id] = $owner;
            }
        }

        /** @var Query $query */
        $query = $elementType::find();
        $query->relatedTo = $element;
        $query->anyStatus();
        /** @var Element[] $blocks */
        $elements = $query->all();

        return array_merge(
            $matchingBlocks,
            $elements,
        );
    }
}
