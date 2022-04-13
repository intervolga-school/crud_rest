<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"] . "/intervolga.school/classes/groups.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/intervolga.school/classes/students.php";
$app = new Silex\Application();

$app->get('/group/list.json', function() {
    $group=new Groups;
    $list=$group->read();
    return json_encode($list);
});

$app->post('/group/add-item', function () {

    if($_POST['groupName'] && $_POST['speciality'])
    {
        $nameGroup=htmlspecialchars($_POST['groupName']);
        $speciality=htmlspecialchars($_POST['speciality']);
        $group=new Groups;
        $group->create(array("groupName"=>$nameGroup, "speciality"=>$speciality));
        $lastid=$group->lastID();
        return json_encode(array("create-group"=>"yes", "create-id"=>$lastid));
    }
    else
    {
        return json_encode(array("create-group"=>"no"));
    }
});
$app->post('/group/update-item', function()
{
    $group=new Groups;
    $idGroup=intval($_POST["idGroup"]);
    $groupName=htmlspecialchars($_POST["groupName"]);
    $speciality=htmlspecialchars($_POST["speciality"]);
    if($group->exists($idGroup) && $groupName)
    {
        $group->update(array("speciality"=>$speciality,"id"=>$idGroup,"groupName"=>$groupName));
        return json_encode(array("update-group"=>"yes", "id_update"=>$idGroup));
    }
    else
    {
        return json_encode(array("update-group"=>"no"));
    }
});

$app->post('/group/delete-item', function ()
{
    $group=new Groups;
    $id=intval($_POST["id"]);
    if($group->exists($id))
    {
        $group->delete($id);
        return json_encode(array("delete-group"=>"yes", "id_delete"=>$id));
    }
    else
    {
        return json_encode(array("delete-group"=>"no"));
    }
});

//для студентов:

$app->get('/student/list.json', function() {

    $student=new Students;
    $list=$student->read();
    return json_encode($list);
});
$app->post('/student/add-item', function () {
    $name=htmlspecialchars($_POST["name"]);
    $surname=htmlspecialchars($_POST["surname"]);
    $patronymic=htmlspecialchars($_POST["patronymic"]);
    $idGroup=intval($_POST["idGroup"]);
    $group=new Groups;
    if($name && $group->exists($idGroup))
    {
        $student=new Students;
        $student->create(array('name'=>$name,"idGroup"=>$idGroup,"surname"=>$surname,"patronymic"=>$patronymic));
        $lastid=$student->lastID();
        return json_encode(array("create-student"=>"yes", "create-id"=>$lastid));
    }
    else
    {
        return json_encode(array("create-student"=>"no"));
    }
});
$app->post('/student/update-item', function()
{
    $id=intval($_POST["id"]);
    $name=htmlspecialchars($_POST["name"]);
    $surname=htmlspecialchars($_POST["surname"]);
    $patronymic=htmlspecialchars($_POST["patronymic"]);
    $idGroup=intval($_POST["idGroup"]);
    $student=new Students;
    if($student->exists($id) &&  $name)
    {
        $student->update(array("id"=>$id,"name"=>$name,"surname"=>$surname,"patronymic"=>$patronymic,"idGroup"=>$idGroup));
        return json_encode(array("update-student"=>"yes", "id_update"=>$id));
    }
    else
    {
        return json_encode(array("update-student"=>"no"));
    }
});

$app->post('/student/delete-item', function ()
{

    $id=intval($_POST["id"]);
    $student=new Students;
    if($student->exists($id))
    {
        $student->delete($id);
        return json_encode(array("delete-student"=>"yes", "id_delete"=>$id));
    }
    else
    {
        return json_encode(array("delete-student"=>"no"));
    }
});

$app->run();