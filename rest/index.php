<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"] . "/classes/groups.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/classes/students.php";
$app = new Silex\Application();

//для групп:
$app->get('/group/list.json', function () {
	$group = new Groups;
	$list = $group->read();
	return json_encode($list);
});

$app->post('/group/add-item', function () {

	if ($_POST['groupName'] && $_POST['speciality']) {
		$nameGroup = $_POST['groupName'];
		$speciality = $_POST['speciality'];
		$group = new Groups;
		try {
			$group->create(array("groupName" => $nameGroup, "speciality" => $speciality));
			$lastid = $group->lastID();
			return json_encode(array("create-group" => "yes", "create-id" => $lastid));
		} catch (PDOException $e) {
			return json_encode(array("error" => $e->getMessage(), "create-group" => "no"));
		}
	} else {
		return json_encode(array("create-group" => "no"));
	}
});
$app->post('/group/update-item', function () {
	$group = new Groups;
	$idGroup = intval($_POST["idGroup"]);
	$groupName = $_POST["groupName"];
	$speciality = $_POST["speciality"];
	if ($group->exists($idGroup) && $groupName) {
		try {
			$group->update(array("speciality" => $speciality, "id" => $idGroup, "groupName" => $groupName));
			return json_encode(array("update-group" => "yes", "id_update" => $idGroup));
		} catch (PDOException $e) {
			return json_encode(array("error" => $e->getMessage(), "update-group" => "no"));
		}
	} else {
		return json_encode(array("update-group" => "no"));
	}
});

$app->post('/group/delete-item', function () {
	$group = new Groups;
	$id = intval($_POST["id"]);
	if ($group->exists($id)) {
		try {
			$group->delete($id);
			return json_encode(array("delete-group" => "yes", "id_delete" => $id));
		} catch (PDOException $e) {
			return json_encode(array("error" => $e->getMessage(), "delete-group" => "no"));
		}
	} else {
		return json_encode(array("delete-group" => "no"));
	}
});

//для студентов:

$app->get('/student/list.json', function () {

	$student = new Students;
	$list = $student->read();
	return json_encode($list);
});
$app->post('/student/add-item', function () {
	$name = $_POST["name"];
	$surname = $_POST["surname"];
	$patronymic = $_POST["patronymic"];
	$idGroup = intval($_POST["idGroup"]);
	$group = new Groups;
	if ($name && $group->exists($idGroup)) {
		$student = new Students;
		try {
			$student->create(array('name' => $name, "idGroup" => $idGroup, "surname" => $surname, "patronymic" => $patronymic));
			$lastid = $student->lastID();
			return json_encode(array("create-student" => "yes", "create-id" => $lastid));
		} catch (PDOException $e) {
			return json_encode(array("error" => $e->getMessage(), "create-student" => "no"));
		}
	} else {
		return json_encode(array("create-student" => "no"));
	}
});
$app->post('/student/update-item', function () {
	$id = intval($_POST["id"]);
	$name = $_POST["name"];
	$surname = $_POST["surname"];
	$patronymic = $_POST["patronymic"];
	$idGroup = intval($_POST["idGroup"]);
	$student = new Students;
	if ($student->exists($id) && $name) {
		try {
			$student->update(array("id" => $id, "name" => $name, "surname" => $surname, "patronymic" => $patronymic, "idGroup" => $idGroup));
			return json_encode(array("update-student" => "yes", "id_update" => $id));
		} catch (PDOException $e) {
			return json_encode(array("error" => $e->getMessage(), "update-student" => "no"));
		}
	} else {
		return json_encode(array("update-student" => "no"));
	}
});

$app->post('/student/delete-item', function () {

	$id = intval($_POST["id"]);
	$student = new Students;
	if ($student->exists($id)) {
		try {
			$student->delete($id);
			return json_encode(array("delete-student" => "yes", "id_delete" => $id));
		} catch (PDOException $e) {
			return json_encode(array("error" => $e->getMessage(), "delete-student" => "no"));
		}
	} else {
		return json_encode(array("delete-student" => "no"));
	}
});

$app->run();