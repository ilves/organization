<?php
/**
 * @copyright Copyright (c) 2017 Taavi Ilves
 */

namespace app\versions\v1;

use yii\base\BootstrapInterface;

/**
 * Module that implements version 1 of the rest api
 * @author Taavi Ilves <ilves.taavi@gmail.com>
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * Default number of results
     */
    const DEFAULT_PAGE_SIZE = 100;

    /**
     * Default page size limit
     */
    const DEFAULT_PAGE_SIZE_LIMIT = [0, 100];

    /**
     * Method adds custom url rules
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => $this->id.'/organization',
                'tokens' => [
                    '{id}' => '<id:\\d[\\d,]*>',
                    '{name}' => '<name>'
                ],
                'patterns' => [
                    'DELETE' => 'clear',
                    'POST' => 'create',
                    'GET' => 'index',
                ]
            ],
        ], false);
    }
}