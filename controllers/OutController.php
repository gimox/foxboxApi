<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Send;


/**
 * Class SiteController
 *
 * @package app\controllers
 */
class OutController extends Controller
{
    /**
     * @var string foxbox spooler base path
     */
    public $spoolerPath = "/mnt/flash/spool/outgoing";

    /**
     * @var string dev test base path
     */
    //public $spoolerPath = "/var/www/html/app/web/testfile";


    /**
     * Declaring behaviours
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'app\components\Access',
            ],
        ];
    }


    /**
     * Actions
     *
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'app\components\ErrorWs',
            ],
        ];
    }


    public function actionSend()
    {
        $model  = new Send();
        $params = ['Send' => \Yii::$app->request->post()];

        if ($model->load($params) && $model->validate()) {

            $stream = $this->createStream($model);
            $this->saveToFile($stream);

            return [
                'status'  => 1,
                'message' => 'saved',
                'stream'=>$stream
            ];
        }

        return [
            'status'  => 2,
            'message' => $model->getFirstErrors()
        ];
    }


    protected function createStream($model)
    {
        $stream = '';
        $stream .= 'From: ' . $model->from . PHP_EOL;
        $stream .= 'To: ' . $model->to . PHP_EOL;
        $stream .= 'Alphabet: ' . $model->alphabet . PHP_EOL;

        if($model->flash) {
            $stream .= 'Flash: ' . $model->flash . PHP_EOL;
        }

        if($model->smsc) {
            $stream .= 'SMSC: ' . $model->smsc . PHP_EOL;
        }

        if($model->report) {
            $stream .= 'Report: ' . $model->report . PHP_EOL;
        }

        if($model->autosplit) {
            $stream .= 'Autosplit: ' . $model->autosplit . PHP_EOL;
        }

        $stream .= PHP_EOL.$model->text;


        return $stream;

    }


    protected function saveToFile($stream)
    {
        $tmpfname = tempnam($this->spoolerPath,'fapi');
        $handle = fopen($tmpfname, "wb");
        fwrite($handle, $stream);
        fclose($handle);
    }


}