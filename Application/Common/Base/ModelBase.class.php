<?php
namespace Common\Base;

class ModelBase
{
    public $md;
    public $table;
    private $_error = '';
    private $_errors = array();

    public function __construct($t = '')
    {
        $this->md = M();
        $this->table = trim($t);
    }

    /**
     * 设置错误信息
     *
     * @param  string $str
     *
     * @return void
     */
    public function setError($str = '')
    {
        $this->_error = $str;
    }

    /**
     * 取得错误信息
     *
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * 设置错误信息
     *
     * @param  string $str
     *
     * @return void
     */
    public function setErrors($str = '')
    {
        $this->_errors[] = $str;
    }

    /**
     * 取得错误信息
     *
     * @return string
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * 得到表数据
     *
     * @param int   $w    1:计数;  2:数据  3:计数&数据  4:一条数据
     * @param array $sql  array('sql_ct'=>'计数语句', 'sql'=>'数据语句')
     *
     * @return mixed
     */
    public function getData($w=0, $sql=array())
    {
        //只得到计数
        if($w === 1)
        {
            $ct = $this->md->query($sql['sql_ct']);
            return empty($ct) ? 0 : intval($ct[0]['tot']);
        }

        //只得到数据
        if($w === 2)
        {
            return $this->md->query($sql['sql']);
        }

        //得到计数 & 数据
        if($w === 3)
        {
            $datas = array();
            $ct = $this->md->query($sql['sql_ct']);
            $datas['tot'] = empty($ct) ? 0 : intval($ct[0]['tot']);
            $datas['datas'] = $this->md->query($sql['sql']);
            return $datas;
        }

        //得到一条数据
        if($w === 4)
        {
            if(strpos($sql['sql'], 'limit') === false){ $sql['sql'] .= ' limit 1'; }
            $rs = $this->md->query($sql['sql']);
            if(empty($rs)){
                return array();
            }else{
                return array_shift($rs);
            }
        }

        return array();
    }

    /**
     * 新增单表数据
     *
     * @param array  $row
     * @param string $table
     *
     * @return mixed
     */
    public function addRow($row=array(), $table='')
    {
        $table = trim($table);
        if($table === ''){ $table = $this->table; }
        if($table === ''){ $this->setError('缺少表'); return false; }
        if(empty($row) || !is_array($row)){ $this->setError('缺少数据'); return false; }
        $insertID = $this->md->table($table)->add($row);
        $insertID = intval($insertID);
        if($insertID < 1){ $this->setError('新增失败，请稍候重试。'); return false; }
        else{ return $insertID; }
    }

    /**
     * 新增单表数据
     *
     * @param array  $rows
     * @param string $table
     *
     * @return mixed
     */
    public function addRows($rows=array(), $table='')
    {
        $table = trim($table);
        if($table === ''){ $table = $this->table; }
        if($table === ''){ $this->setError('缺少表'); return false; }
        if(empty($rows) || !is_array($rows)){ $this->setError('缺少数据'); return false; }
        $insertIDs = array();
        foreach($rows as $row){
            $insertID = $this->md->table($table)->add($row);
            $insertID = intval($insertID);
            if($insertID < 1){ $this->_errors[] = '新增失败，请稍候重试。'; }
            else{ $insertIDs[] = $insertID; }
        }
        return $insertIDs;
    }

    /**
     * 更新单表数据
     *
     * @param array  $where
     * @param array  $dat
     * @param string $table
     *
     * @return mixed
     */
    public function updateRow($where=array(), $dat=array(), $table='')
    {
        $table = trim($table);
        if($table === ''){ $table = $this->table; }
        if($table === ''){ $this->setError('缺少表'); return false; }
        if(empty($where) || !is_array($where)){ $this->setError('缺少更新条件'); return false; }
        if(empty($dat) || !is_array($dat)){ $this->setError('缺少更新数据'); return false; }
        $numRows = $this->md->table($table)->where($where)->save($dat);
        if($numRows === false){ $this->setError('没有更新'); return false; }
        else{ return $numRows; }
    }

    /**
     * 删除单表数据
     *
     * @param array  $where
     * @param string $table
     *
     * @return mixed
     */
    public function delRow($where=array(), $table='')
    {

        $table = trim($table);
        if($table === ''){ $table = $this->table; }
        if($table === ''){ $this->setError('缺少表'); return false; }
        if(empty($where) || !is_array($where)){ $this->setError('缺少更新条件'); return false; }
        $numRows = $this->md->table($table)->where($where)->delete();
        if($numRows === false){ $this->setError('没有更新'); return false; }
        else{ return $numRows; }
    }
}