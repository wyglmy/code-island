<?php
namespace Common\Base;

class LogicBase
{
    private $_error = '';

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
}