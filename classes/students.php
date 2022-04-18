<?php
require_once "tableModule.php";

class Students extends TableModule
{
	protected function getTableName(): string
	{
		return "students";
	}
}