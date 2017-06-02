<?php
namespace Common\Logic\Member;

use Common\Base\LogicBase;
use Common\Model\Member\MemberModel;

class MemberLogic extends LogicBase
{
    public $m_mem;

    public function __construct()
    {
        $this->m_mem = new MemberModel();
    }
}