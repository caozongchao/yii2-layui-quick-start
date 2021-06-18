<?php
namespace backend\controllers;

use yii\web\Controller;

class ToolsController extends Controller
{
	public function actionIco(){
		return $this->render('ico');
	}
}
