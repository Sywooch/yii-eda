<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */

$restaurants = ArrayHelper::map(app\models\Restaurant::find()->where(['status' => 1])->asArray()->all(), 'id', 'name');

?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusOptions()) ?>

    <?= $form->field($model, 'restaurant_id')->dropDownList($restaurants, ['prompt' => '- Не выбрано -'])?>

    <?= $form->field($model, 'phone') ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'street') ?>
    <?= $form->field($model, 'house') ?>
    <?= $form->field($model, 'apartment') ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 5]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
