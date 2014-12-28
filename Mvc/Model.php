<?php
/**
 * Created by PhpStorm.
 * User: nununo
 * Date: 22/03/14
 * Time: 09:55
 */
namespace Ew\Mvc;

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class NotFoundException extends \Phalcon\Exception{}

class Model extends \Phalcon\Mvc\Model {

    //Preenchimento obrigatorio

    /**
     * Schema da tabela, se necessário
     * @var string
     */
    protected static $_schema = "";
    /**
     * Nome da tabela
     * @var string
     */
    protected static $_tableName = "";

    /**
     * Nome da coluna de auto-incremento
     * @var string
     */
    protected static $_key = "id";

    //Nomes utilizados em mensagens de erro automáticas
    protected static $_friendlyTableName = "Itens";
    protected static $_friendlyRowName = "Item";
    protected static $_friendlyRowsetName = "Itens";

    //Uso interno
    /**
     * Utilzada para getNext e getPrevious, contém o ultimo ID pesquisado.
     * @var string
     */
    protected static $_currentKey = 0;
    

    public function initialize()
    {
        $this::_defineDataBase();
    }

    protected function _defineDataBase()
    {
        if(static::$_schema!=''){
            $this->setSchema(static::$_schema);
        }
        if(static::$_tableName!=''){
            $this->setSource(static::$_tableName);
        }
    }

    public static function getByRawSql($nativeSql, $params=null)
    {
        $model = new static();
        return new Resultset (null, $model, $model->getReadConnection()->query($nativeSql, $params));
    }

    public static function find($parameters=null)
    {
        $result = parent::find($parameters);
        if(count($result)==0){
            throw new NotFoundException("Não foram encontrados registros de " . static::$_friendlyRowsetName);
        }
        return $result;
    }

    public static function findFirst($parameters=null)
    {
        $result = parent::findFirst($parameters);
        if($result===false){
            throw new NotFoundException("Nenhum registro de ". static::$_friendlyRowName. " encontrado");
        }
        return $result;
    }

    public static function get($parameters=null)
    {
        return parent::find($parameters);
    }

    public static function getFirst($parameters=null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Retorna o primeiro registro da tabela
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function findFirstRegister()
    {

        try{
            $return =  static::query()
                ->orderBy(static::$_key)
                ->limit(1)->execute();
        }catch(NotFoundException $e){
            throw new NotFoundException(static::$_friendlyTableName . " está vazia.");
        }
        $key = static::$_key;
        $return = $return->getFirst();
        $meta = $return->getModelsMetaData();
        $att = $meta->getAttributes($return);
        $types = $meta->getDataTypes($return);
        var_dump($types);
        static::$_currentKey = $return->$key;
        return $return;
    }

    /**
     * Retorna o registro anteiror
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function findPreviousRegister()
    {
        try{
            $return =  static::query()->andWhere(static::$_key . ' < ' . static::$_currentKey)
                                  ->orderBy(static::$_key)
                                  ->limit(1)->execute();
        }catch(NotFoundException $e){
            throw new NotFoundException("Primeiro registro encontrado para " . static::$_friendlyTableName);
        }

        $key = static::$_key;
        $return = $return->getFirst();
        static::$_currentKey = $return->$key;
        return $return;
    }

    /**
     * Retorna o próximo registro
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function findNextRegister()
    {
        try{
            $return =  static::query()->andWhere(static::$_key . ' > ' . static::$_currentKey)
                ->orderBy(static::$_key)
                ->limit(1)->execute();
        }catch(NotFoundException $e){
            throw new NotFoundException("Último registro encontrado para " . static::$_friendlyTableName);
        }

        $key = static::$_key;
        $return = $return->getFirst();
        static::$_currentKey = $return->$key;
        return $return;
    }

    /**
     * Retorna o ultimo registro
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function findLastRegister()
    {
        try{
            $return =  static::query()
                ->orderBy(static::$_key . " desc")
                ->limit(1)->execute();
        }catch(NotFoundException $e){
            throw new NotFoundException(static::$_friendlyTableName . " está vazia.");
        }

        $key = static::$_key;
        $return = $return->getFirst();
        static::$_currentKey = $return->$key;
        return $return;
    }

    public static function getFirstById($key=null)
    {
        return parent::findFirst(Array(static::$_key . ' > ' . $key));
    }
} 