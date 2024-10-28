<?php
session_start();
const ORDER_VERSION = 0; /* 0 refers to this item inserted by costumer */
if(isset($_SESSION['userID'], $_SESSION['regStatus']) && $_SESSION['regStatus'] > 0){
    $dir = "../";
    $isLoggedIn = false;
    $noHeader = "No";
    require("../init.php");
    if(isset($_POST['orderID']) && is_numeric($_POST['orderID'])){
        $orderID = intval($_POST['orderID']);
        /*get and validate order */
        $orderData = new order($is,$orderID);
        $orderData->orderVersion = 0; /** JUST TO GET SINGLE ROW FROM THE VIEW */
        if(!($orderData->getOrderByID())){
            echo "error: 6013.  Saving Faild.";
            exit();
        }elseif($orderData->costumerID !== $_SESSION['userID']){
            echo "error: 6016.  Saving Faild.";
            exit();
        }else{
            $propsToUpdate = [];
            if(isset($_POST['op'])){
                $op = $_POST['op'] ;
                if($op == 'save'){
                    if($orderData->procStatus != 1){ /** 1 refers to opened order */
                        if($orderData->procStatus == 2){
                            echo "error: 6024. The Order is Already Saved.";
                        }else{
                            echo "error: 6026.  Saving Faild.";
                        }
                        exit();
                    }else{
                        $procStatus = 2;
                        $propDate = 'saveDate';
                        $notiType = "order//save";
                        $importance = 0;
                        $faildOP = 'Saving' ;
                    }
                }elseif($op == 'submit'){
                    if($orderData->procStatus > 2 || $orderData->procStatus < 1){ /** 1 refers to opened order */
                        if($orderData->procStatus == 3){
                            echo "error: 6035.  The Order is Already requisted.";
                        }else{
                            echo "error: 6037.  Submitting Faild.";
                        }
                        exit();
                    }else{
                        $procStatus = 3;
                        $propDate = 'receiveDate';
                        $notiType = "order//submit";
                        $importance = 2;
                        $faildOP = 'Submitting' ;
                    }
                }elseif($op == 'edit'){
                    if($orderData->procStatus != 2){ /** 2 refers to saved order */
                        echo "error: 6047.  Can't edit ". lang(PROCSTATUS[$orderData->procStatus]. " ORD") ;
                        exit();
                    }else{
                        $procStatus = 1;
                        $propDate = 'lastEditDate';
                        $notiType = "order//edit";
                        $importance = 0;
                        $faildOP = 'Editing' ;
                    }
                }elseif($op == 'confirm'){
                    if($orderData->procStatus != 4){ /** 2 refers to saved order */
                        echo "error: 6061.  Confirming faild" ;
                        exit();
                    }else{
                        $procStatus = 5;
                        $propDate = 'confirmDate';
                        $notiType = "order//confirm";
                        $importance = 2;
                        $faildOP = 'Confirming' ;
                    }
                }elseif($op == 'subProposal'){
                    if($orderData->procStatus != 4){ /** 2 refers to saved order */
                        echo "error: 6074.  Submitting faild" ;
                        exit();
                    }else{
                        $procStatus = 4;
                        $propDate = 'lastProposalDate';
                        $notiType = "order//subProposal";
                        $importance = 2;
                        $faildOP = 'Submitting' ;
                    }
                }elseif($op == 'cancel'){
                    $cancelIF = setting::getSpecificSetting('costCancelXorders')['value'];
                    $cancelIF = explode("/", $cancelIF);
                    if($orderData->procStatus < 1){ /** 0 refers to canceled order */
                        echo "error: 6086.  Order is already canceled !!" ;
                        exit();
                    }elseif(!in_array($order->procStatus, $cancelIF)){
                        echo "error: 6092.  Cancelling faild !!" ;
                        exit();
                    }else{
                        $procStatus = 0;
                        $propDate = 'cancelDate';
                        $orderData->cancelBy = $_SESSION['userID'];
                        $propsToUpdate[] = 'cancelBy';
                        $notiType = "order//cancel";
                        $importance = 2;
                        $faildOP = 'Cancelling' ;
                    }
                }else{
                    echo "error: 6085.  Operation Faild.";
                    exit();
                }

                $orderData->procStatus = $procStatus;
                $orderData->$propDate = date("Y-m-d H:i");
                $propsToUpdate[] = 'procStatus';
                $propsToUpdate[] = $propDate;
                if($orderData->updateOrder($err, 'orderID', ...$propsToUpdate)){
                    $notification = new broadNotifications($is, null, $_SESSION['userID'], BROAD_RECIPIENTS_ID['allAdmins'], $notiType, $importance, $orderID);
                    $notification->insertNotification($e, 'ALL');
                    echo 1;
                }else{
                    echo "error: 7096.  $faildOP Faild.";
                }
                exit();
        
            }else{
                echo "error: 6101.  Operation Faild.";
                exit();
            }

            
        }
    }
}
echo "error: 7109, Operation Faild";
exit();