<!--            Report of particular order when user click view button from tent_order.php-->

<!--salesRecords.php,showTenantOrders.php, delTenantOrder.php, tent_order.php show all tenant order info-->

<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    include("conn.php");
    include("showCustOrder.php");
    define('nani',true);
    include("functionForTAndC.php");


    $sql = "";
    extract($_GET);
    extract($_SESSION);

//    check if this order belong to tenant
    $sql = "SELECT orders.orderID as orderID 
                        FROM orders,orderitem,tenant,showcase,goods
                        where tenant.tenantID = showcase.tenantID
                        and showcase.showcaseID = goods.showcaseID and goods.goodsID = orderitem.goodsID
                        and orderitem.orderID = orders.orderID and tenant.tenantID = '$id' and orders.orderID = '$name'";
    $rs = mysqli_query($conn, $sql);

//    flag to check tenant identity
    $isFake = false;
    if($rs == false){
        $isFake = true;
    }

    if(count($_GET) == 0 || $_SESSION['role'] != "T" || $isFake){
        header("Location:index.php");
    }

// retreive the order detail from that particular order
    $stmt = $conn->prepare("SELECT orderID,orderDateTime,status,customerEmail,shop.shopID,shop.shopName FROM orders,shop WHERE orderID = ? and shop.shopID = orders.shopID");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $set= $stmt->get_result();

//    global variable to store shop name used inside and outside the loop
    $simpleShopName= "";
    $subTotal = 0;

    while($rc = $set->fetch_assoc()){
        extract($rc);

        $simpleShopName = $shopName;
    ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/Inventory.css">
    <link rel="stylesheet" href="css/cust_tent_order.css">

    <title><?php echo "Detail for Order # $orderID";?></title>
</head>

<body>
    <section>
        <div class="container" id="sales-rec">

            <div class="row">
                <h1>Order # <?php echo $orderID;?></h1>
            </div>

            <!--      this button will disapper when print-->
            <div class="row">
                <button type="button" id="print-bt" class="btn btn-outline-dark" onClick="window.print();">Print</button>
            </div>

            <!--      order info-->
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="desc-tag-s">Date Time:</span>
                        </div>
                        <div class="col-md-6">
                            <span class="desc-tag-s">Status:</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
<!--                            show time-->
                            <span class="bold-desc"><?php echo $orderDateTime;?></span>
                        </div>
                        <div class="col-md-6">
<!--                            show order status-->
                            <span class="bold-desc"><?php echo getStatus($status, $conn);?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="desc-tag-s">Customer ID:</span>
                        </div>
                        <div class="col-md-6">
                            <span class="desc-tag-s">Customer Name:</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="bold-desc"><?php echo $customerEmail;?></span>
                        </div>
                        <div class="col-md-6">
<!--                            show customer name using function from functionForTAndC.php-->
                            <span class="bold-desc"><?php echo getCustName($customerEmail, $conn);?></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-6 text-right">
                    <span class="desc-tag-s">Shop Address:</span><br>
<!--                    show address-->
                    <p class="bold-desc"><?php echo getAddress($shopID, $conn);?></p>
                    <span class="desc-tag-s">Shop Name:</span><br>
                    <span class="bold-desc"><?php echo $simpleShopName;?></span><br>
                </div>
            </div>
            <br>
            <?php

}
mysqli_free_result($set);
            ?>

            <div class="row">
                <div class="col-md-12">
                    <h3>Order List</h3>
                </div>
            </div>

            <!--      item list-->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Goods ID</th>
                                <th>Goods Name</th>
                                <th>Showcase ID</th>
                                <th>Location</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>SubTotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

//                        find all related order info in that particular order
                            $stmt = $conn->prepare("SELECT orders.orderID ,orders.orderDateTime, orderitem.quantity, orderitem.sellingPrice, 
                                                orders.status, orderitem.quantity*orderitem.sellingPrice as total, goods.goodsName, showcase.showcaseID, orderitem.goodsID  
                                            FROM orders,orderitem,tenant,showcase,goods 
                                            where tenant.tenantID = showcase.tenantID and 
                                            showcase.showcaseID = goods.showcaseID and 
                                            goods.goodsID = orderitem.goodsID and 
                                            orderitem.orderID = orders.orderID and tenant.tenantID = '$id' and orders.orderID = ?");
                            $stmt->bind_param("s", $name);
                            $stmt->execute();
                            $set= $stmt->get_result();

//                            keep track how many item in the order
                            $counter = 1;
                            while($rc = mysqli_fetch_assoc($set)){
                                extract($rc);
                        ?>
                            <tr>
<!--                                number of items-->
                                <th><?php echo $counter?></th>

                                <td><?php echo $goodsID?></td>
                                <td><?php echo $goodsName?></td>
                                <td><?php echo $showcaseID?></td>
                                <td><?php echo $simpleShopName?></td>
                                <td><?php echo $quantity?></td>
                                <td class="bold-desc"><?php echo $sellingPrice?></td>
                                <td class="bold-desc"><?php echo $total?></td>
                            </tr>
                        <?php

//                                calculate grand total
                              $subTotal += $total;
                                $counter++;   }
                        ?>

                            <!--              summary-->
                            <tr>
                                <td colspan=8>
                                    <div class="row">
                                        <div class="col-md-8 text-right">
                                            <span class="desc-tag-s">Subtotal:</span>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <span class="bold-desc"><?php echo $subTotal?></span>
                                        </div>
                                    </div>
                                    <br>

                                    <div class="row">
                                        <div class="col-md-8 text-right">
                                            <span class="desc-tag-s">Additional Fees:</span>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <span class="bold-desc">$0.0</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-8 text-right">
                                            <span class="bold-desc">Grand Total:</span>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <span class="bold-desc"><?php echo $subTotal?></span>
                                        </div>
                                    </div>
                                    <br>
                                </td>
                            </tr>
                        <?php
                                 mysqli_free_result($rs); mysqli_close($conn);
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</body>

</html>