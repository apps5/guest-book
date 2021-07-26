<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
class GuestBook_List_View extends A5_View_Controller{
  
  public function process(A5_Request $request){  
    $module = $request->get('module');
    $moduleModel = GuestBook_Module_Model::getInstance(); 
    $listRecords = $moduleModel->getListAll(); 
    $listHeadRecords = $moduleModel->getListHeadRecords($listRecords);        
    $viewer = $this->getViewer($request);
    $viewer->assign('MODULE_HEADER_TITLE', 'Интересно узнать Ваше мнение');
    $viewer->assign('LBL_SEND_MESSAGE', 'Оставить сообщение');
    $viewer->assign('LIST_HEAD_RECORDS', $listHeadRecords);
    $viewer->assign('LIST_RECORDS', $listRecords);
    $viewer->assign('MAX_LEVEL', 3);
    $viewer->assign('LBL_EDIT', 'Редактировать');
    $viewer->assign('LBL_ANSWER', 'Ответить');
    $viewer->assign('LBL_SAVE', 'Сохранить');
    $viewer->assign('LBL_CANCEL', 'Отмена');
    $viewer->view('List.tpl', $module); 
  }
    
} 
