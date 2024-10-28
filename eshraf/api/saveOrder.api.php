<?php
session_start();
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0 && $_SESSION['groupID'] > 0){
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require_once("../init.php");
    if(isset($_POST['orderID']) && is_numeric($_POST['orderID'])){
        $orderID = intval($_POST['orderID']);
        /*get and validate order */
        $orderData = new order($is,$orderID);
        $orderData->orderVersion = 1; /** JUST TO GET SINGLE ROW FROM THE VIEW */
        if(!($orderData->getOrderByID())){
            echo "error: 6012.  Saving Faild.";
            exit();
        }else{
            if(isset($_POST['op'])){
                $op = $_POST['op'] ;
                if($op == 'done'){
                    if($orderData->procStatus < 3 || $orderData->procStatus == 6){ /** 3 refers to wait for reply order */
                            echo "error: 6019. Can't finish (". lang(PROCSTATUS[$orderData->procStatus]). ") Orders.";
                        exit();
                    }else{
                        $notiType = "order//done";
                        $importance = 1;
                        $faildOP = 'Finishing';
                        $orderData->procStatus = 6;
                        $orderData->doneBy = $_SESSION['userID'];
                        $orderData->doneDate = date("Y-m-d H:i");
                        $cond = $orderData->updateOrder($err, 'orderID', 'procStatus', 'doneBy','doneDate');
                    }
                }elseif($op == 'accept'){
                    if($orderData->procStatus == 3 || ($orderData->procStatus == 4 && strtotime($orderData->lastProposalDate) > strtotime($orderData->lastResponseDate) )){ /** 3 refers to wait for reply order */
                        $notiType = "order//accept";
                        $importance = 1;
                        $faildOP = 'Accepting';
                        $orderData->procStatus = 5;
                        $orderData->confirmDate = date("Y-m-d H:i");
                        /**القبول accept المقصود به قبول اقتراح الزبون كما هو من دون تعديلات ، لذلك يجب ملئ الفاتورة النهائية كنسخة من مقترح الزبون */
                        $orderItems = 0; /**قيمة مبدئية مؤقتة فقط لعدم ظهور تحذير عند استعماله في دالة is_array() "ملاحظة: يمكن أن تكون القيمة أيّاً كانت فلن تؤثر في الشرط مالم تكن مصفوفة"*/
                        if(strtotime($orderData->lastProposalDate) > strtotime("0000-00-00")){
                            $orderItems = stat_getObjects::getOrderItems($orderID, 1, 2);
                        }
                        if(!is_array($orderItems)){ /**إذا لم يكن هناك عناصر في فاتورة الاقتراح الخاصة بالزبون فهذا يعني أنه لم يضع اقتراح بعد الفاتورة الأساسية */
                            $orderItems = stat_getObjects::getOrderItems($orderID, 1, 0);
                        }
                        if(is_array($orderItems)){
                            $orderItems[0]->orderVersion = 1; /** to delete previous final version (This set is temprory, just for condition in deleteOrderItem())*/
                            $orderItems[0]->deleteOrderItem($err, 'orderID', 'orderVersion');
                            foreach($orderItems as $orderItem){
                                $orderItem->ID = null;
                                $orderItem->itemName = null;
                                $orderItem->itemNum = null;
                                $orderItem->orderVersion = 1; /** refers to final invoice version */
                                $orderItem->insertDate = date("Y-m-d H:i");
                                if($orderItem->insertOrderItem($err, 'ALL') === false){
                                    fnc::redirectHome('خطأ :7051', 'back');
                                }
                            }
                        }
                        $cond = $orderData->updateOrder($err, 'orderID', 'procStatus', 'confirmDate');
                    }else{
                        echo "error: 6061. Can't accept (". lang(PROCSTATUS[$orderData->procStatus]). ") Orders.";
                        exit();
                    }
                }elseif($op == 'reply'){
                    if($orderData->procStatus < 3 || $orderData->procStatus > 4){ /** 3 refers to wait for reply order */
                        echo "error: 6066. Can't set a reply for (". lang(PROCSTATUS[$orderData->procStatus]). ") Orders.";
                        exit();
                    }else{
                        $notiType = "order//reply";
                        $importance = 2;
                        $faildOP = 'replying';
                        $propsToUpdate = ['procStatus'];
                        if($orderData->procStatus == 3){
                            $orderData->procStatus = 4;
                            $orderData->firstResponseBy = $_SESSION['userID'];
                            $orderData->firstResponseDate = date("Y-m-d H:i");
                            $propsToUpdate[] = "firstResponseBy" ;
                            $propsToUpdate[] = "firstResponseDate" ;
                        }
                        $orderData->lastResponseBy = $_SESSION['userID'];
                        $orderData->lastResponseDate = date("Y-m-d H:i");
                        $propsToUpdate[] = "lastResponseBy" ;
                        $propsToUpdate[] = "lastResponseDate" ;
                        $cond = $orderData->updateOrder($err, 'orderID', ...$propsToUpdate);
                    }
                }elseif($op == 'edit'){
                    if($orderData->createdBy == 0){
                        echo "error: 6088. Can't edit orders that created by costumers .";
                        exit();
                    }
                    $isEditable = ($_SESSION['groupID'] == 3 || setting::getSpecificSetting('isOrdersCreatedByAdminsEditableByAllAdmins')['value'] == 1) ? true : false;
                    if(!$isEditable){
                        if($_SESSION['userID'] != $orderData->createdBy){
                            echo "error: 6094. You have no permission to edit this order.";
                            exit();
                        }
                    }
                    if($orderData->procStatus != 4){ /** 4 refers to wait for confirm order  */
                        echo "error: 6100. Can't Edit (". lang(PROCSTATUS[$orderData->procStatus]). ") Orders.";
                        exit();
                    }else{
                        $notiType = "order//edit";
                        $importance = 1;
                        $faildOP = 'editing';
                        $orderData->procStatus = 1;
                        $orderData->lastEditDate = date("Y-m-d H:i");
                        $cond = $orderData->updateOrder($err, 'orderID', 'procStatus', 'lastEditDate');
                    }
                }elseif($op == 'cancel'){
                    $cancelCond = ($_SESSION['groupID'] >= setting::getSpecificSetting('minPermissionToCancelOrder')['value']);
                    if(!$cancelCond){
                        echo "error: 6112. You have no permission to cancelling an order.";
                        exit();
                    }
                    if($orderData->procStatus > 5){ /** 6 refers to done order */
                        echo "error: 6116. Can't cancel completed Orders.";
                        exit();
                    }else{
                        $notiType = "order//cancel";
                        $importance = 1;
                        $faildOP = 'cancelling';
                        $orderData->procStatus = 0;
                        $orderData->cancelBy = $_SESSION['userID'];
                        $orderData->cancelDate = date("Y-m-d H:i");
                        $cond = $orderData->updateOrder($err, 'orderID', 'procStatus', 'cancelBy','cancelDate');
                    }
                }else{
                    echo "error: 6128.  operation Faild.";
                    exit();
                }
                if($cond){
                    $recipientID = $orderData->costumerID;
                    $notification = new specificNotifications($is, null, $_SESSION['userID'], $recipientID, $notiType, $importance, $orderID);
                    $notification->insertNotification($e, 'ALL');
                    echo 1;
                }else{
                    echo "error: 7137.  $faildOP Faild.";
                }
                exit();
        
            }else{
                echo "error: 7142.  Operation Faild.";
                exit();
            }
        }
    }
}
echo "error: 7148, Operation Faild";
exit();