<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 26.09.2018
 * Time: 23:46
 */

namespace bobroid\memesRedirectorBot\commands;


use Longman\TelegramBot\Commands\SystemCommand;

abstract class BaseSystemCommand extends SystemCommand
{

    use CheckAccessTrait;

}