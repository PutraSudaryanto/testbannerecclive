<?php
/**
 * @var $this app\components\View
 * @var $this app\controllers\SettingController
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 22 April 2019, 23:47 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ActiveForm;
use app\models\BaseSetting;
use yii\helpers\ArrayHelper;

\yii2mod\selectize\SelectizeAsset::register($this);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$getSublayoutUrl = Url::to(['sublayout', 'theme'=>'']);
$js = <<<JS
	var xhr;
	var backend_theme, f_backend_theme;
	var backend_sublayout, f_backend_sublayout;
	var front_theme, f_front_theme;
	var front_sublayout, f_front_sublayout;
	var v_backend_sublayout = '$model->backoffice_theme_sublayout';
	var v_front_sublayout = '$model->theme_sublayout';

	f_backend_theme = $('#basesetting-backoffice_theme').selectize({
		onChange: function(value) {
			if (!value.length) return;
			backend_sublayout.disable();
			backend_sublayout.clearOptions();
			backend_sublayout.removeOption(value, true);
			backend_sublayout.load(function(callback) {
				xhr && xhr.abort();
				xhr = $.ajax({
					url: '$getSublayoutUrl' + value,
					success: function(results) {
						backend_sublayout.removeOption(v_backend_sublayout);
						backend_sublayout.showInput();
						backend_sublayout.enable();
						callback(results);
					},
					error: function() {
						callback();
					}
				})
			});
		}
	});

	f_front_theme = $('#basesetting-theme').selectize({
		onChange: function(value) {
			if (!value.length) return;
			front_sublayout.disable();
			front_sublayout.clearOptions();
			front_sublayout.load(function(callback) {
				xhr && xhr.abort();
				xhr = $.ajax({
					url: '$getSublayoutUrl' + value,
					success: function(results) {
						front_sublayout.removeOption(v_front_sublayout);
						front_sublayout.showInput();
						front_sublayout.enable();
						callback(results);
					},
					error: function() {
						callback();
					}
				})
			});
		}
	});

	f_backend_sublayout = $('#basesetting-backoffice_theme_sublayout').selectize({
		valueField: 'id',
		labelField: 'label',
		searchField: ['label'],
		onChange: function(value) {
			v_backend_sublayout = value;
		}
	});

	f_front_sublayout = $('#basesetting-theme_sublayout').selectize({
		valueField: 'id',
		labelField: 'label',
		searchField: ['label'],
		onChange: function(value) {
			v_front_sublayout = value;
		}
	});

	backend_sublayout  = f_backend_sublayout[0].selectize;
	backend_theme = f_backend_theme[0].selectize;
	front_sublayout  = f_front_sublayout[0].selectize;
	front_theme = f_front_theme[0].selectize;

	$('#basesetting-online input[name="BaseSetting[online]"]').on('change', function() {
		var id = $(this).val();
		if(id == '1') {
			$('div#construction').hide();
		} else {
			$('div#construction').show();
			if(id == '0') {
				$('.field-basesetting-construction_text-comingsoon').hide();
				$('.field-basesetting-construction_text-maintenance').show();
			} else {
				$('.field-basesetting-construction_text-maintenance').hide();
				$('.field-basesetting-construction_text-comingsoon').show();
			}
		}
	});
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="base-setting-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php $appType = BaseSetting::getAppType();
echo $form->field($model, 'app_type')
	->dropDownList($appType, ['prompt'=>''])
	->label($model->getAttributeLabel('app_type')); ?>

<?php // echo $form->errorSummary($model);?>

<?php $nameSmall = $form->field($model, 'name[small]', ['template' => '<div class="h5">Small Name</div>{input}', 'options' => ['tag' => null]])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'name[long]', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}'.$nameSmall.'<div class="h5">Long Name</div>{input}{error}{endWrapper}'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('name'))
	->hint(Yii::t('app', 'Give your website a unique name. This will appear in the &lt;title&gt; tag throughout most of your site.')); ?>

<?php echo $form->field($model, 'pagetitle_template')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('pagetitle_template'))
	->hint(Yii::t('app', 'e.g. {title} | {small-name} - {long-name}')); ?>

<?php echo $form->field($model, 'description', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
	->textarea(['rows'=>3, 'cols'=>50, 'maxlength' => true])
	->label($model->getAttributeLabel('description'))
	->hint(Yii::t('app', 'Enter a brief, concise description of your website. Include any key words or phrases that you want to appear in search engine listings.')); ?>

<?php echo $form->field($model, 'keywords', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
	->textarea(['rows'=>4, 'cols'=>50, 'maxlength' => true])
	->label($model->getAttributeLabel('keywords'))
	->hint(Yii::t('app', 'Provide some keywords (separated by commas) that describe your website. These will be the default keywords that appear in the tag in your page header. Enter the most relevant keywords you can think of to help your website\'s search engine rankings.')); ?>

<div class="ln_solid"></div>

<?php $online = BaseSetting::getOnline();
echo $form->field($model, 'online', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
	->radioList($online)
	->label($model->getAttributeLabel('online'))
	->hint(Yii::t('app', 'Maintenance Mode will prevent site visitors from accessing your website.')); ?>

<div id="construction" <?php echo $model->online == '1' ? 'style="display: none;"' : ''; ?>>
	<?php $model->construction_date = !in_array($model->construction_date, array('0000-00-00','1970-01-01','0002-12-02','-0001-11-30')) ? $model->construction_date : '';
	echo $form->field($model, 'construction_date')
		->textInput(['type' => 'date'])
		->label($model->getAttributeLabel('construction_date')); ?>

	<?php $options = [];
	if($model->online != '2')
		$options = ArrayHelper::merge($options, ['style' => 'display: none;']);
	echo $form->field($model, 'construction_text[comingsoon]', ['options'=>$options])
		->textarea(['rows'=>4, 'cols'=>50])
		->label($model->getAttributeLabel('construction_text[comingsoon]')); ?>

	<?php $options = [];
	if($model->online != '0')
		$options = ArrayHelper::merge($options, ['style' => 'display: none;']);
	echo $form->field($model, 'construction_text[maintenance]', ['options'=>$options])
		->textarea(['rows'=>4, 'cols'=>50])
		->label($model->getAttributeLabel('construction_text[maintenance]')); ?>
</div>

<div class="ln_solid"></div>

<?php $appType = BaseSetting::getAnalytics();
echo $form->field($model, 'analytic')
	->dropDownList($appType, ['prompt'=>''])
	->label($model->getAttributeLabel('analytic'))
	->hint(Yii::t('app', 'Want to use Google Analytics to keep track of your site\'s traffic data? Setup is super easy. Just enter your Google Analytics Tracking ID and *bam*... you\'re tracking your site\'s traffic stats! If you need help finding your ID, check here.')); ?>

<?php echo $form->field($model, 'analytic_property')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('analytic_property'))
	->hint(Yii::t('app', 'Enter the Google Analytics Website Property (Tracking ID).')); ?>


<div class="ln_solid"></div>

<?php echo $form->field($model, 'backoffice_theme')
	->dropDownList($themes, ['prompt'=>''])
	->label($model->getAttributeLabel('backoffice_theme'));?>

<?php echo $form->field($model, 'backoffice_theme_sublayout')
	->dropDownList($backSubLayout, ['prompt'=>''])
	->label($model->getAttributeLabel('backoffice_theme_sublayout'));?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'theme')
	->dropDownList($themes, ['prompt'=>''])
	->label($model->getAttributeLabel('theme'));?>

<?php echo $form->field($model, 'theme_sublayout')
	->dropDownList($frontSubLayout, ['prompt'=>''])
	->label($model->getAttributeLabel('theme_sublayout'));?>

<?php echo $form->field($model, 'theme_include_script', ['template' => '{beginLabel}{labelTitle}{hint}{endLabel}{beginWrapper}{input}{error}{endWrapper}'])
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('theme_include_script'))
	->hint(Yii::t('app', 'Anything entered into the box below will be included at the bottom of the <head> tag. If you want to include a script or stylesheet, be sure to use the &lt;script&gt; or &lt;link&gt; tag.')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 col-12 col-sm-offset-3">
		<?php echo Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>