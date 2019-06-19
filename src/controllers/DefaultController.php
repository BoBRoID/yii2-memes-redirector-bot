<?php


namespace bobroid\memesRedirectorBot\controllers;


use bobroid\memesRedirectorBot\actions\GetWebHookAction;
use bobroid\memesRedirectorBot\actions\SetWebHookAction;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ErrorAction;

class DefaultController extends Controller
{
    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' =>  ErrorAction::class,
            ],
            'hook'  =>  [
                'class' =>  GetWebHookAction::class
            ],
            'set'  =>  [
                'class' =>  SetWebHookAction::class
            ]
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if($action->id == 'hook'){
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

}