<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;


use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Accounts;
use app\models\Deposits;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class EachDayController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionRun()
    {
       // setTimeZone('Europe/Kiev');
        date_default_timezone_set ( 'Europe/Kiev' );
        $deposits = new Deposits();
        $deposits->doDepositActions();
        $deposits->doComissionsActions();
    }


}
