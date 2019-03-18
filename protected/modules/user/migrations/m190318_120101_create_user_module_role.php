<?php
/**
 * m190318_120101_create_user_module_role
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\db\Schema;

class m190318_120101_create_user_module_role extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_assignment';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_assignment', ['item_name', 'user_id', 'created_at'], [
				['userAdmin', '1', time()],
				['userAdmin', '2', time()],
				['userModerator', '3', time()],
				['userModerator', '4', time()],
			]);
		}
	}
}
