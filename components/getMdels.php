<?php
require_once __DIR__ . '/../src/helpers.php';

if(isset($_POST['marka_id'])){
    $markaID = $_POST['marka_id'];
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT model_id, model_name FROM model WHERE marka_id = ?");
    $stmt->execute([$markaID]);
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if($models){
        foreach($models as $model){
            echo "<option value='{$model['model_id']}'>{$model['model_name']}</option>";
        }
    } else {
        echo '<option value="">Моделі не знайдено</option>';
    }
}
?>