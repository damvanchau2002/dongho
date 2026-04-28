<?php
require_once 'models/BaseModel.php';
$model = new BaseModel();
$res = $model->connect->query("SELECT * FROM wards LIMIT 5");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
echo "\n---\n";
$res = $model->connect->query("SELECT * FROM provinces LIMIT 5");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
