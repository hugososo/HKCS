<!--salesRecords.php,showTenantOrders.php, delTenantOrder.php, tent_order.php show all tenant order info-->

<?php

if (!defined('nani')) {
    header("refresh:3;url=index.php");
    die("<h2>Direct access not permitted</h2>");
}

function showAllTenantOrders($rs){
    include("conn.php");

//    mysqli_num_rows($rs);
    while($rc = mysqli_fetch_assoc($rs)){
        extract($rc);

//        get info from functionForTAndC.php
        $date = getOrderDate($datetime);
        $status = getStatus($orderStatus,$conn);

        echo<<<row
            <tr>
              <td>$orderID</td>
              <td>$date</td>
              <td>$total</td>
              <td>$status</td>
              <td><button type="button" class="btn btn-secondary" onClick="window.open('salesRecords.php?name=$orderID',
'mywindow','menubar=1,resizable=1,width=800,height=700')">View</button>
row;


//        if order is in delivery
        if($orderStatus == "1"){

//            check if there are 1 item in orderitem
                    $sql = "SELECT COUNT(orderID) as count from orderitem where orderID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $orderID);
                    $stmt->execute();
                    $set= $stmt->get_result();
                    $rec = $set->fetch_assoc();
                    extract($rec);


//                    if yes, show delete button
                    if($count == 1){
                        echo "<button type=\"button\" class=\"btn btn-secondary btn_del\" data-key='$orderID'>Delete</button>";
                    }

                }
                echo "</td></tr>";
    }
}
