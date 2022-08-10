<?php
/**
 * Related plugin for Craft CMS 3.x
 *
 * A simple plugin that adds a widget within the Craft CP page sidebar, allowing you to quickly and easily access related entries.
 *
 * @link      https://github.com/reganlawton
 * @copyright Copyright (c) 2019 reganlawton
 */

namespace wrav\related\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use wrav\related\Related;
use yii\web\JsonResponseFormatter;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    reganlawton
 * @package   Related
 * @since     1.0.0
 */
class DefaultController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|int|bool $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        $data = Craft::$app->request->getQueryParams();
        $elementId = (int)$data['id'];
        $allowedSections = Related::getInstance()->getSettings()->allowedSections;
        $allowedCategories = Related::getInstance()->getSettings()->allowedCategories;
        $allowedAssetVolumes = Related::getInstance()->getSettings()->allowedAssetVolumes;
        $shouldFetch = false;

        $element = Craft::$app->elements->getElementById($elementId);
        $elementType = Craft::$app->elements->getElementTypeById($elementId);

        switch ($elementType) {
            case 'craft\elements\Entry':
                if (!$allowedSections) {
                    $shouldFetch = true;
                } elseif (is_array($allowedSections)) {
                    $sectionId = $element->sectionId;

                    try {
                        $sectionHandle = Craft::$app->getSections()->getSectionById((int)$data['sectionId'])->handle;
                    } catch (\Throwable $exception) {
                        $sectionHandle = '';
                    }
                    if (in_array($sectionHandle, $allowedSections)) {
                        $shouldFetch = true;
                    }
                }
                break;
            case 'craft\elements\Asset':
                $asset = Craft::$app->elements->getElementById($elementId);
                $volumeId = $asset->volume->id;
                if (!$allowedAssetVolumes) {
                    $shouldFetch = true;
                } elseif (is_array($allowedAssetVolumes)) {
                    if (in_array($volumeId, $allowedAssetVolumes)) {
                        $shouldFetch = true;
                    }
                }
                break;
            case 'craft\elements\User':
                $shouldFetch = true;
                break;
            case 'craft\elements\Category':
                if (!$allowedCategories) {
                    $shouldFetch = true;
                } elseif (is_array($allowedCategories)) {
                    try {
                        $categoryHandle = Craft::$app->getCategories()->getGroupById((int)$data['categoryId'])->handle;
                    } catch (\Throwable $exception) {
                        $categoryHandle = '';
                    }
                    if (in_array($categoryHandle, $allowedCategories)) {
                        $shouldFetch = true;
                    }
                }
                break;
        }

        if ($shouldFetch) {
            $relations = Related::$plugin->relatedService->getRelated($elementId);

            $data = [
                'count' => count($relations),
                'view' => Craft::$app->view->renderTemplate(
                    'related/_modal',
                    [
                        'relations' => $relations,
                    ]
                ),
            ];

            return $this->asJson($data);
        }

        return $this->asJson([
            'count' => 0,
            'view' => Craft::$app->view->renderTemplate(
                'related/_modal',
                [
                    'relations' => [],
                ]
            ),
        ]);

        Craft::$app->end();
    }
}
