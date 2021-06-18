<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\components\globalSetting\models\Setting;
use common\components\globalSetting\Module;

$this->title = Module::t('global-setting', 'Setting');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs($this->render('js/index.js'));

$items = [];
foreach($settingParent as $parent)
{
    $items['label'][] = Module::t('global-setting', $parent->code);

    $str = '';
    $children = Setting::find()->where(['parent_id' => $parent->id])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
    foreach($children as $child)
    {
        $str .= '<div class="layui-form-item"> <label class="layui-form-label" style="width: 115px;">'.Module::t('global-setting', $child->code).'</label><div class="layui-input-inline" style="width: 500px;">';

        if($child->type == 'text') {
            $str .= Html::textInput("Setting[$child->code]", $child->value, ["class" => "layui-input"]);
        }elseif($child->type == 'password') {
            $str .= Html::passwordInput("Setting[$child->code]", $child->value, ["class" => "layui-input"]);
        }elseif($child->type == 'select') {
            $options = [];
            $arrayOptions = explode(',', $child->store_range);
            foreach($arrayOptions as $option)
                $options[$option] = Module::t('global-setting', $option);

            $str .= Html::dropDownList("Setting[$child->code]", $child->value, $options);
        }elseif($child->type == 'textarea'){
            $str .= Html::textarea("Setting[$child->code]", $child->value, ["class" => "layui-input",'style' => 'height:70px;']);
        }

        $str .= '</div></div>';
    }
    $items['content'][] = $str;
}

?>

<style>
.tab-pane {padding-top: 20px;}
</style>

<div class="layui-fluid">
    <div class="layui-card">
        <!--<div class="layui-card-header"></div>-->
        <div class="layui-card-body">
            <div class="layui-form">
                <?php $form = ActiveForm::begin([
                    'id' => 'setting-form',
                    'fieldConfig' => [
                        'template' => "<div class='layui-form-item'>{label}<div class='layui-input-block'>{input}{hint}{error}</div></div>",
                        'labelOptions' => ['class' => 'layui-form-label'],
                    ],
                ]); ?>

                <div class="layui-tab">
                    <ul class="layui-tab-title">
                        <?php foreach($items['label'] as $index => $label):?>
                            <?php if($index == 0):?>
                                <li class="layui-this"><?=$label?></li>
                            <?php else:?>
                                <li><?=$label?></li>
                            <?php endif;?>
                        <?php endforeach;?>
                    </ul>
                    <div class="layui-tab-content">
                        <?php foreach($items['content'] as $key => $content):?>
                            <?php if($key == 0):?>
                                <div class="layui-tab-item layui-show"><?=$content?></div>
                            <?php else:?>
                                <div class="layui-tab-item"><?=$content?></div>
                            <?php endif;?>
                        <?php endforeach;?>
                    </div>
                </div>

                <div class="layui-input-block">
                    <?= Html::submitButton(Module::t('global-setting', 'Update'), ['class' => 'layui-btn layui-btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
