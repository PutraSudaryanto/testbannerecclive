<?php
/**
 * @var $this DefaultController
 * @var $this \yii\web\View
 * @var $generators \yii\gii\Generator[]
 * @var $content string
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 24 September 2017, 12:38 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use yii\helpers\Html;

$generators = Yii::$app->controller->module->generators;?>

<div class="row">
	<?php foreach ($generators as $id => $generator): ?>
	<div class="col-md-4 col-sm-4 col-xs-12">
		<div class="x_panel tile fixed_height_320">
			<div class="x_title">
				<h2><?php echo Html::encode($generator->getName()) ?></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					<li><a class="close-link"><i class="fa fa-close"></i></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<p><?php echo $generator->getDescription() ?></p>
				<p class="lead">Start the fun with the following code generators:</p>
				<div class="widget_summary">
					<?php echo Html::a('Start &raquo;', ['default/view', 'id' => $id], ['class' => 'btn btn-success']) ?>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>

<p><a class="btn btn-success" href="http://www.yiiframework.com/extensions/?tag=gii">Get More Generators</a></p>