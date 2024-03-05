<?php
include './components/connect.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    include 'admin/blocked_user.php'; 
} else {
    header('location:user_login.php');
}


if( isset($_REQUEST['oid']) &&
	isset( $_REQUEST['amt']) &&
	isset( $_REQUEST['refId'])
	)
    {
        $sql = $conn->prepare("SELECT * FROM orders WHERE invoice_no = ?");
        $sql->execute([$_REQUEST['oid']]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if($result){
        $oid= $result['invoice_no'];
        $total= $result['total_price'];
        $refId = $_REQUEST['refId'];
        $id = $result['id'];
	
			$url = "https://uat.esewa.com.np/epay/transrec";
		
			$data =[
			'amt'=> $total,
			'rid'=>  $refId,
			'pid'=>  $oid,
			'scd'=> 'epay_payment'
			];

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($curl);
			$response_code = get_xml_node_value('response_code',$response  );
			if ( trim($response_code)  == 'Success')
			{
                $stmt = $conn->prepare("UPDATE orders SET method=?, payment_Status=?, idx = ? WHERE invoice_no = ?");
                $stmt->execute([ "Esewa", "Success", $refId, $oid ]);
                header('Location: success_payment.php?Payment_id=' . $id);
			}
            else{

                header('Location:paymentfail.php');
            }
	
        }else{
            header('Location:paymentfail.php');
        }

}else{
    header('Location:paymentfail.php');
}


function get_xml_node_value($node, $xml) {
    if ($xml == false) {
        return false;
    }
    $found = preg_match('#<'.$node.'(?:\s+[^>]+)?>(.*?)'.
'</'.$node.'>#s', $xml, $matches);
if ($found != false) {

return $matches[1];

}

return false;
}

?>