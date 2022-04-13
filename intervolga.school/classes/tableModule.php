<?php
require_once "singleton.php";
abstract class TableModule
{
    abstract protected function getTableName():string;
    public function delete($id)
    {

        $sql="DELETE FROM ".$this->getTableName()." WHERE id=:id";
        $query=Singleton::prepare($sql);
        if (!$query->execute(array(":id"=>$id)))
        {
            throw new PDOExpection("При удалении произошла ошибка");
        }
    }
    public function read($fields=array())
    {
        $sql="SELECT * FROM ".$this->getTableName()." WHERE 1 ";
        while ($field = current($fields))
        {
            $sql.="AND ".key($fields)."=".$field." ";
            next($fields);
        }
        $query=Singleton::prepare($sql);
        $query->execute([]);
        $result=array();
        while($slice=$query->fetch())
        {
            $result[]=$slice;
        }
        return $result;
    }
    public function create($fields)
    {
        $key="(";
        $keyparam="(";
        $arField=array();
        while ($field = current($fields))
        {
            $key.=" ".key($fields).", ";
            $keyparam .=" :".key($fields).", ";
            $arField[":".key($fields)]=$field;
            next($fields);
        }
        $key = substr($key,0,-2);//убираем лишние запятые в конце
        $key.=")";
        $keyparam = substr($keyparam,0,-2);
        $keyparam .=")";
        $sql="INSERT INTO ".$this->getTableName()." ".$key." VALUE ".$keyparam;
        $query=Singleton::prepare($sql);
        if (!$query->execute($arField))
        {
            throw new PDOExpection("При добавлении произошла ошибка");
        }
    }
    public function update($fields)
    {
        $keyparam="";
        $arField=array();
        while ($field = current($fields))
        {
            if(key($fields)!="id") {
                $keyparam .= " `" . key($fields) . "`=:" . key($fields) . ", ";
            }
            $arField[":" . key($fields)] = $field;
            next($fields);
        }
        $keyparam = substr($keyparam,0,-2);
        $sql="UPDATE ".$this->getTableName()." SET ".$keyparam." WHERE id=:id";
        $query=Singleton::prepare($sql);
        if(!$query->execute($arField))
        {
            throw new PDOExpection("При обновлении произошла ошибка");
        }
    }
    public function exists($id):bool
    {
        $query=Singleton::prepare("SELECT * FROM ".$this->getTableName()." WHERE id=".$id);
        $query->execute([]);
        $find=$query->fetch();
        if($find)
        {
            return true;
        }
        else{
            return false;
        }
    }
    public function lastID()
    {
        $query=Singleton::prepare("SELECT MAX(ID) FROM ".$this->getTableName());
        $query->execute([]);
        $find=$query->fetch();
        return $find["MAX(ID)"];
    }
}