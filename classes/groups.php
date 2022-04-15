<?php
require_once "tableModule.php";
class Groups extends TableModule
{
    protected function getTableName(): string
    {
        return "groups";
    }
}