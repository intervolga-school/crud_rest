<?php
require_once "tableModule.php";
class Groups extends TableModule
{
    protected function getTableName(): string
    {
        return "groups";
    }
    public function create($fields)
    {
        parent::create($fields); // TODO: Change the autogenerated stub
    }
    public function read($fields=array()):array
    {
        return parent::read($fields); // TODO: Change the autogenerated stub
    }
    public function update($fields)
    {
        parent::update($fields); // TODO: Change the autogenerated stub
    }
    public function delete($id)
    {
        parent::delete($id); // TODO: Change the autogenerated stub
    }
    public function exists($id): bool
    {
        return parent::exists($id); // TODO: Change the autogenerated stub
    }
    public function lastID()
    {
        return parent::lastID(); // TODO: Change the autogenerated stub
    }
}