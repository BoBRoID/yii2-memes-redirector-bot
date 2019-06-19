<?php


namespace bobroid\memesRedirectorBot\commands;

use Longman\TelegramBot\Commands\AdminCommand;

abstract class BaseAdminCommand extends AdminCommand
{

    use CheckAccessTrait;

}