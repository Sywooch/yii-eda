<?php
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Restaurant */
/* @var $image app\models\Image */
/* @var $form kartik\widgets\ActiveForm */

$cities = ArrayHelper::map(app\models\City::find()->where(['status' => 1])->asArray()->all(), 'id', 'name');
$foodTypes = ArrayHelper::map(app\models\FoodType::find()->where(['status' => 1])->asArray()->all(), 'id', 'name');
$users = ArrayHelper::map(app\models\User::find()->where(['status' => 1, 'group' => 'restaurant'])->asArray()->all(), 'id', 'username');
?>

<div class="restaurant-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="col-sm-4">
        <?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
            'pluginOptions' => [
                'onColor' => 'success',
                'offColor' => 'danger',
                'onText' => 'Да',
                'offText' => 'Нет',
            ]
        ]) ?>
    </div>

    <div class="col-sm-8">
        <?= $form->field($model, 'order_available')->widget(SwitchInput::classname(), [
            'pluginOptions' => [
                'onColor' => 'success',
                'offColor' => 'danger',
                'onText' => 'Да',
                'offText' => 'Нет',
            ]
        ]) ?>
    </div>

    <div class="col-sm-8">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-4">
        <?= $form->field($model, 'system_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-4">
        <?= $form->field($model, 'city_id')->dropDownList($cities, ['prompt' => '- Выберите город -'])?>
    </div>

    <div class="col-sm-4">
        <?= $form->field($model, 'rating')->dropDownList(array(
            1 => '1 звезда',
            2 => '2 звезды',
            3 => '3 звезды',
            4 => '4 звезды',
            5 => '5 звезд',
        ), ['prompt' => '- Не указано -']) ?>
    </div>

    <div class="col-sm-4">
        <?= $form->field($model, 'user')->widget(\kartik\widgets\Select2::className(), [
            'language' => 'ru',
            'data' => $users,
            'options' => ['placeholder' => 'Привязать пользователя...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'addon' => [
                'prepend' => [
                    'content' => Html::icon('user'),
                ],
            ]
        ]); ?>
    </div>

    <?php
    $pluginOptions = [
        'showPreview' => true,
        'showCaption' => false,
        'showRemove' => false,
        'showUpload' => false,
        'browseClass' => 'btn btn-success btn-block',
        'browseIcon' => '<i class="glyphicon glyphicon-file"></i> ',
        'browseLabel' =>  'Select icon'
    ];
    if($model->image_id) {
        $pluginOptions['initialPreview'] = [Html::img($image->getInitialPreview(), ['class'=>'file-preview-image'])];
        $pluginOptions['browseClass'] = 'btn btn-default btn-block';
    }
    ?>
    <div class="col-sm-12">
        <?= $form->field($image, 'file')->widget(FileInput::className(), ['pluginOptions' => $pluginOptions])->label('Иконка') ?>
    </div>

    <div class="col-sm-12">
        <?= $form->field($model, 'foodTypes')->checkboxButtonGroup($foodTypes)->label('Категории') ?>
    </div>

    <div class="col-sm-4">
        <?= $form->field($model, 'work_time', ['addon' => ['prepend' => ['content'=>Html::icon('time')]]])->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-4">
        <?= $form->field($model, 'delivery_price', ['addon' => ['append' => ['content'=>'рублей']]]) ?>
    </div>

    <div class="col-sm-4">
        <?= $form->field($model, 'delivery_free', ['addon' => ['append' => ['content'=>'рублей']]]) ?>
    </div>

    <div class="col-sm-12">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить изменения', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
