<?php
//得到表名称
function getTable($t = '')
{
    return \Common\Base\TablesBase::getTableName($t);
}

/*调试输出*/
function ddd($var, $exit=false, $printr=false)
{
    if(headers_sent() === false){ header("Content-type:text/html; charset=utf-8"); }
    if($printr){
        echo '<pre>';print_r($var);echo '</pre>';
    }else{
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')){
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>'. htmlspecialchars($output, ENT_QUOTES).'</pre>';
        }
        echo $output;
    }
    if ($exit){exit;}
    else{return;}
}
//获取文件夹中的文件列表
function getDirFile($dir, $pre='')
{
    $dir = $_SERVER['DOCUMENT_ROOT'].$dir;
    $fileArray = array();
    if(is_dir($dir)){

        if (false != ($handle = opendir($dir)))
        {
            $i=0;
            while ( false !== ($file = readdir($handle)) )
            {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file!="." && $file!=".." && strpos($file,"."))
                {
                    $fileArray[$i] = $pre.$file;
                    $i++;
                }
            }
            //关闭句柄
            closedir( $handle );
        }
    }
    return $fileArray;
}

//把xml文件解析成数组
function _xmlToArray($file, $type=false, $returnArray=true)
{
    $file = trim($file);
    if($type){
        if(!is_file($file)){return null;}
        $str = simplexml_load_file($file);
    }else{
        if($file == ''){return null;}
        $str = simplexml_load_string($file);
    }
    return json_decode(json_encode($str), $returnArray);
}

/**
 * 对查询结果集进行排序
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param string $sortby 排序类型 asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby='asc')
{
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pk
 * @param string $pid parent标记字段
 * @param string $child 子数组标记
 * @param int $root
 * @return array
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0)
{
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array())
{
    if(is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            $list[] = $reffer;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
        }
        //$list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}

/**
 * 把多维数组转化成一维数组
 *
 * @param array $arr 需要转换的数组，必须有相同的子节点
 * @param string $sub 子节点名称
 * @param array &$rs 返回的引用数组
 *
 * @return array
 */
function floatArray($arr=array(), $sub='_sub', &$rs)
{
    if(empty($arr)){ return false; }
    foreach($arr as $v){
        $tmp2 = $v;
        if(isset($tmp2[$sub])){
            unset($tmp2[$sub]);
            $rs[] = $tmp2;
            floatArray($v[$sub], $sub, $rs);
        }else{
            $rs[] = $v;
        }
    }
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function formatBytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}
/**
 * 字符串截取，支持中文和其他编码
 *
 * @param string  $str 需要转换的字符串
 * @param int     $start 开始位置
 * @param string  $length 截取长度
 * @param string  $charset 编码格式
 * @param boolean $suffix 截断显示字符
 *
 * @return string
 */
function mSubStr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}
/**
 * 淘宝获取IP地理位置接口
 * @param string $ip
 * @return string
 */
function get_city_by_ip($ip = '')
{
    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
    $ipinfo = json_decode(file_get_contents($url));
    if ($ipinfo->code == '1') {
        return false;
    }
    $city = $ipinfo->data->region . $ipinfo->data->city; //省市县
    $ip = $ipinfo->data->ip; //IP地址
    $ips = $ipinfo->data->isp; //运营商
    $guo = $ipinfo->data->country; //国家
    if ($guo == '中国') {
        $guo = '';
    }
    return $guo . $city . $ips . '[' . $ip . ']';
}
/**
 * 由二维数组生成xls，并下载
 *
 * @param array  $datas     数据
 * @param string $save_name 保存名称
 * @param string $type      驱动 (Excel5生成xls   |   Excel2007生成xlsx)
 * @param array  $cell_color array( array('cell'=>'A1', 'color'=>'F28A8C') )
 *
 * @return void
 */
function outputExcelFromArray($datas=array(), $save_name='', $type='Excel5', $cell_color=array())
{
    if(empty($datas)){ return; }
    $save_name = trim($save_name);
    if($save_name == ''){ $save_name = date('Y-m-d_H-i'); }
    //导入PHPExcel
    import('PHPExcel', COMMON_PATH.'Tool/PHPExcel/', '.php');

    // Create new PHPExcel object
    $objPHPExcel = new \PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("major")
        ->setLastModifiedBy("major")
        ->setTitle("Document")
        ->setSubject("Document")
        ->setDescription("major")
        ->setKeywords("major")
        ->setCategory("major");

    $objPHPExcel->setActiveSheetIndex(0);

    $objPHPExcel->getActiveSheet()->fromArray($datas);

    //设置背景颜色
    if( ! empty($cell_color))
    {
        foreach($cell_color as $cc)
        {
            $cc['cell'] = trim($cc['cell']);
            $cc['color'] = trim($cc['color']);
            if($cc['cell']=='' && $cc['color']==''){ continue; }
            $objPHPExcel->getActiveSheet()->getStyle($cc['cell'])->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => $cc['color']
                )
            ));
        }
    }

    $objPHPExcel->getActiveSheet()->setTitle($save_name);

    $hz = ($type == 'Excel2007') ? 'xlsx' : 'xls';

    ob_end_clean();
    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$save_name.'.'.$hz.'"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $type);
    $objWriter->save('php://output');
    exit;
}

/**
 * 由二维数组生成xls，并下载
 *
 * @param array  $sheets = array(array(...), array(...), ..., =>array(...))
 * @param string $save_name 保存名称
 * @param string $driver      驱动 (Excel5生成xls   |   Excel2007生成xlsx)
 *
 * @return void
 */
function outputExcelMultiSheetFromArray($sheets=array(), $save_name='', $driver='Excel2007')
{
    if(empty($sheets)){ return; }
    $save_name = trim($save_name);
    if($save_name == ''){ $save_name = date('Y-m-d_H-i'); }
    //导入PHPExcel
    import('PHPExcel', COMMON_PATH.'Tool/PHPExcel/', '.php');

    // Create new PHPExcel object
    $objPHPExcel = new \PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("major")
        ->setLastModifiedBy("major")
        ->setTitle("Document")
        ->setSubject("Document")
        ->setDescription("major")
        ->setKeywords("major")
        ->setCategory("major");

    $ki = 0;
    foreach($sheets as $sheet)
    {
        if($ki > 0){
            $objPHPExcel->createSheet($ki);
        }
        $objPHPExcel->setActiveSheetIndex($ki);
        $objPHPExcel->getActiveSheet()->fromArray($sheet);
        $ki++;
        $objPHPExcel->getActiveSheet()->setTitle("sheet".$ki);
    }

    $hz = ($driver === 'Excel2007') ? 'xlsx' : 'xls';

    ob_end_clean();
    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$save_name.'.'.$hz.'"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $driver);
    $objWriter->save('php://output');
    exit;
}
//组装返回信息
function genReturn($sign='y', $note='ok', $extra=array())
{
    $rt = array();
    $rt['st'] = $sign;
    $rt['note'] = $note;
    if( ! empty($extra)){
        foreach($extra as $k=>$v){
            $rt[$k] = $v;
        }
    }
    return $rt;
}

