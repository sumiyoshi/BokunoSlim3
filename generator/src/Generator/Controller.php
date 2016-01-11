<?php

namespace Generator;

use Generator\Lib\Builder;

/**
 * Class Controller
 * @package Generator
 */
class Controller
{
    use Builder;

    /**
     * スキーマ作成
     */
    public function schemaAction()
    {
        $schema_dir = GENERATOR_ROOT . '/workspace/db/';

        //twig
        $view = $this->getView();


        //ディレクトリ生成
        $this->mkDir($schema_dir);

        $db = \ORM::get_db();

        $result = $db->query('SHOW TABLES');

        $tables = array();
        foreach ($result as $row) {
            $tables[] = array_shift($row);
        }


        #region 設定ファイル生成
        foreach ($tables as $table) {
            $php_name = $this->snakeToCamel($table);

            $result = $db->query("SHOW FULL COLUMNS FROM {$table}");
            $columns = array();
            foreach ($result as $row) {

                $_row = $row;

                preg_match("/(.*)\((.*)\)(.*)/is", $_row['Type'], $retArr);

                if ((bool)$retArr) {
                    $_row['Type'] = $retArr[1];
                    $_row['Size'] = $retArr[2];
                } else {
                    $_row['Size'] = '';
                }
                $columns[] = $_row;
            }

            $xml_name = $table . ".xml";
            $template = $this->getTemplate('schema.twig');
            $this->output($schema_dir . $xml_name, $view->render($template, compact('table', 'php_name', 'columns')), true);
        }
        # endregion

        echo " Build Schema complete!\n";
    }

    /**
     * エンティティ生成
     */
    public function ormAction()
    {

        $schema_dir = GENERATOR_ROOT . '/workspace/db/';
        $list = $this->getFileData($schema_dir);

        //twig
        $view = $this->getView();

        # region 設定ファイルごとにEntity生成
        foreach ($list as $data) {

            $table = $data['table'];
            $data['primary_key'] = $this->getPrimary($data['columns']);

            #region ディレクトリのパス
            $model_dir = APP_ROOT . "/src/Component/Data/Model/";
            $entity_dir = APP_ROOT . "/src/Component/Data/Model/Entity/";
            $repository_dir = APP_ROOT . "/src/Component/Data/Table/";
            #endregion

            #region ディレクトリ生成
            $this->mkDir($model_dir);
            $this->mkDir($entity_dir);
            $this->mkDir($repository_dir);
            #endregion


            #region Class名
            $class_model = $table["php_name"] . ".php";
            $class_entity = $table["php_name"] . "Entity.php";
            $class_repository = $table["php_name"] . "Table.php";
            #endregion

            #region view読み込み
            $model_view = $this->getTemplate('model.twig');
            $entity_view = $this->getTemplate('entity.twig');
            $repository_view = $this->getTemplate('table.twig');
            #endregion

            #region ファイル生成
            $this->output($model_dir . $class_model, $view->render($model_view, compact('data')), false);
            $this->output($entity_dir . $class_entity, $view->render($entity_view, compact('data')), true);
            $this->output($repository_dir . $class_repository, $view->render($repository_view, compact('data')), false);
            #endregion

        }
        # endregion

        echo " Build ORM complete!\n";
    }

    /**
     * アプリケーションモジュール追加
     */
    public function appAction()
    {
        $schema_dir = GENERATOR_ROOT . '/workspace/app/';
        $list = $this->getFileData($schema_dir);

        # region 設定ファイルごとに生成
        foreach ($list as $data) {

            $module = $data['table'];

            #region モジュール
            $module_dir = APP_ROOT . "/src/App/" . $module['php_name'];
            $template_dir = APP_ROOT . "/src/App/templates/" . $module['name'];
            $layout_dir = APP_ROOT . "/src/App/templates/layout";
            $router_dir = APP_ROOT . "/config/router/";
            $this->mkDir($module_dir);
            $this->mkDir($module_dir . '/Action/');
            $this->mkDir($template_dir);
            $this->mkDir($layout_dir);
            #endregion


            $routes = array();
            foreach ($data['columns'] as $row) {

                $lower_controller_name = mb_strtolower($row['controller']);
                $lower_action_name = mb_strtolower($row['action']);
                $row['module'] = $module['php_name'];
                $routes[] = $row;

                #region ディレクトリパス
                $action_dir = $module_dir . '/Action/' . $row['controller'];
                $template_action_dir = $template_dir . '/' . $lower_controller_name;
                #endregion

                #region ディレクトリ生成
                $this->mkDir($action_dir);
                $this->mkDir($template_action_dir);
                #endregion


                #region Class名
                $class_action = $row['action'] . "Action.php";
                $class_view = $lower_action_name . ".twig";
                #endregion


                #region view読み込み
                $action_view = $this->getTemplate('action.twig');
                $template_view = $this->getTemplate('twig_view.twig');
                #endregion


                $data['module'] = $module;
                $data['lower_controller_name'] = $lower_controller_name;
                $data['lower_action_name'] = $lower_action_name;
                $data['row'] = $row;


                #region ファイル生成
                $this->output($template_action_dir . '/' . $class_view, $this->getView()->render($template_view, [
                    '_module' => $module['name']
                ]), false);
                $this->output($action_dir . '/' . $class_action, $this->getView()->render($action_view, compact('data')), false);
                #endregion
            }

            #region view読み込み
            $route_view = $this->getTemplate('route.twig');
            $layout_view = $this->getTemplate('layout.twig');
            $abstract_action_view = $this->getTemplate('abstract_action.twig');
            #endregion

            #region ファイル生成
            $this->output($layout_dir . '/layout_' . $module['name'] . '.twig', $this->getView()->render($layout_view, [
                'data' => $module
            ]), false);
            $this->output($module_dir . '/Action/AbstractAction.php', $this->getView()->render($abstract_action_view, compact('data')), false);
            $this->output($router_dir . '/route_' . $module['name'] . '.php', $this->getView()->render($route_view, compact('routes')), true);
            #endregion

        }
        # endregion

        echo " Build APP complete!\n\n";
    }

    public function operationAction()
    {
        $schema_dir = GENERATOR_ROOT . '/workspace/db/';
        $list = $this->getFileData($schema_dir);

        //twig
        $view = $this->getView();

        # region 設定ファイルごとにEntity生成
        foreach ($list as $data) {

            $table = $data['table'];
            $data['primary_key'] = $this->getPrimary($data['columns']);

            #region ディレクトリのパス
            $operation_dir = APP_ROOT . "/src/Component/Operation/".$table['php_name'].'/';
            #endregion

            #region ディレクトリ生成
            $this->mkDir($operation_dir);
            #endregion


            #region Class名
            $class_save =  "SaveOperation.php";
            $class_delete = "DeleteOperation.php";
            #endregion

            #region view読み込み
            $operation_view = $this->getTemplate('operation.twig');
            #endregion

            #region ファイル生成
            $data['type'] = 'Save';
            $this->output($operation_dir . $class_save, $view->render($operation_view, compact('data')), false);
            $data['type'] = 'Delete';
            $this->output($operation_dir . $class_delete, $view->render($operation_view, compact('data')), false);
            #endregion

        }
        # endregion

        echo " Build Operation complete!\n";
    }
}