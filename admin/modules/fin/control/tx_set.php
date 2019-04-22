<?php

defined('InShopBN') or exit('Access Invalid!');
class tx_setControl extends SystemControl
{
    protected $tx_set_model;
    public function __construct()
    {
        parent::__construct();
        $this->tx_set_model = Model('txset');
    }
    public function indexOp(){
        if($_POST){
            $updateData = [
                'fee_rate' => $_POST['fee_rate'],
                'handling_fee_rate' => $_POST['handling_fee_rate'],
                'updated_at' => time()
            ];
            try{
                $res = $this->tx_set_model->updateData($updateData,$_POST['id']);
            }catch (Exception $e){
                echo showMessage($e->getMessage());;
            }

            if($res)
                showMessage(L('nc_common_save_succ'));
            showMessage(L('nc_common_save_fail'));
        }else{
            $info = $this->tx_set_model->getOne(1);
            $info['updated_at'] = date('Y-m-d H:i:s',$info['updated_at']);
            Tpl::output('info',$info);
            Tpl::setDirquna('fin');
            Tpl::showpage('txset.index');
        }

    }


}