<?php
/**
 * Send Controller
 *
 * @authors Giorgio Modoni <modogio@gmail.com>
 */

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

    public $spoolerPath = "/mnt/flash/spool";



    /**
     * @var string dev test base path
     */
    // public $spoolerPath = "/var/www/html/app/web/testfile";


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

    /**
     * Send
     * main function for sms send
     * check validation and prepare to send SMS
     *
     * @return array
     */
    public function actionSend()
    {
        $model  = new Send();
        $params = ['Send' => \Yii::$app->request->post()];

        if ($model->load($params) && $model->validate()) {

            $stream = $this->createStream($model);
            $modem  = $this->getModem($model);
            $this->saveToFile($stream, $modem);

            return [
                'status'  => 10,
                'message' => 'saved',
                'number'  => $model->to
            ];
        }

        return [
            'status'  => 101,
            'message' => $model->getFirstErrors(),
            'number'  => $model->to
        ];
    }

    /**
     * createStream
     * Create stream file from model data
     *
     * @param $model
     * @return string
     */
    protected function createStream($model)
    {
        $stream = '';
        $stream .= 'From: ' . $model->from . PHP_EOL;
        $stream .= 'To: ' . $model->to . PHP_EOL;
        $stream .= 'Alphabet: ' . $model->alphabet . PHP_EOL;

        if ($model->flash) {
            $stream .= 'Flash: ' . $model->flash . PHP_EOL;
        }

        if ($model->smsc) {
            $stream .= 'SMSC: ' . $model->smsc . PHP_EOL;
        }

        if ($model->report) {
            $stream .= 'Report: ' . $model->report . PHP_EOL;
        }

        if ($model->autosplit) {
            $stream .= 'Autosplit: ' . $model->autosplit . PHP_EOL;
        }

        $stream .= PHP_EOL . $model->text;

        return $stream;
    }

    /**
     * getModem
     * set the endPoint (= modem) to save file
     * if not specified modem is ALL and endPath is "outgoing"
     * the modem dictionary is in app/config/params.php
     *
     * @param $model
     * @return string
     */
    protected function getModem($model)
    {
        if ($model->modem == 'ALL') {
            return '/outgoing';
        }

        return '/' . $model->modem;
    }

    /**
     * saveToFile
     * save the stream to endPoint(=modem)
     * the system fire the sms automatically
     *
     * @param $stream
     * @param $modem
     */
    protected function saveToFile($stream, $modem)
    {
        $tmpfname = tempnam($this->spoolerPath . $modem, 'fapi');
        $handle   = fopen($tmpfname, "wb");
        fwrite($handle, $stream);
        fclose($handle);
    }

}