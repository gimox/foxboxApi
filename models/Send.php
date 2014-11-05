<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Send extends Model
{
    public $from;
    public $to;
    public $alphabet;
    public $smsc;
    public $report;
    public $autosplit;
    public $text;
    public $flash;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [

            [['from', 'to', 'text'], 'required'],
            [['from'], 'string', 'length' => [1, 30]],
            [['to'], 'string', 'length' => [3, 30]],
            ['report', 'in', 'range' => ['YES', 'NO']],
            ['flash', 'in', 'range' => ['YES', 'NO']],
            ['alphabet', 'in', 'range' => ['ISO', 'GSM', 'UCS', 'BINARY']],
            [['alphabet'],'default','value'=>'ISO'],
            [['autosplit'], 'number'],
            [['text'], 'string', 'length' => [1, 160]],
            [['smsc'], 'string', 'length' => [2, 30]],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'from'      => 'From',
            'to'        => 'To',
            'report'    => 'report',
            'alphabet'  => 'alphabet',
            'autosplit' => 'autosplit',
            'text'      => 'text',
            'smsc'      => 'SMSC'
        ];
    }

}