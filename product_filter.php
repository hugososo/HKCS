<?php
session_start();
include( 'conn.php' );

if ( isset( $_POST['action'] ) ) {
    $sql = "SELECT *
            FROM goods
            INNER JOIN showcase
            ON goods.showcaseID = showcase.showcaseID
            WHERE goodsID >0 "; //default sql

    if ( isset( $_POST['shop'] ) ) {    //add shop filter sql
        $shop = implode( "','", $_POST['shop'] );
        $sql .= "AND shopID IN ('$shop') ";
    }
    
    if ( isset( $_POST['status'] ) ) {      //add status filter sql
        $status = implode( "','", $_POST['status'] );
        if($status!="all")
            $sql .= "AND status IN ('$status') ";
    }
    
    if (isset($_POST['search']) && $_POST['search'] != "" ) {       //add search box filter sql
        $search = $_POST['search'];
        $sql .= "AND goodsName LIKE ('%$search%') ";
    }

    
    if ( isset( $_POST['price'] ) ) {   //add price order filter sql
        $sql .= "Order by " . $_POST['price'];
    }
    
    
    $rs = mysqli_query( $conn, $sql ) or die( mysqli_error() );
    $output = '';

    if ( mysqli_num_rows($rs)>0 ) {
        while( $rc = mysqli_fetch_assoc( $rs ) ) {
            extract( $rc );
            if ( $status == 1 ) {
                $statusStr = 'Available';
                $statusColor = 'text-success';
                $statusBtn = '';
            }
            if ( $status == 2 ) {
                $statusStr = 'Unavailable';
                $statusColor = 'text-danger';
                $statusBtn = 'disabled';
            }
            if(strlen($goodsName)>=20){ //if goods name too long, make a short one
                $shortGoodsName = substr($goodsName,0,20)."...";
            } else
                $shortGoodsName = $goodsName;
            
            $output .= <<<EOD
            <form>
                <div class="col mb-4">
                    <div class="card">
                        <img src="image/$goodsImg" class="card-img-top" alt="$goodsName" data-toggle="tooltip" title="$goodsName">
                        <div class="card-body">
                            <h5 class="card-title">$shortGoodsName</h5>
                            <p class="card-text $statusColor">$statusStr</p>
                             <input type="hidden" name="goodsID" value="$goodsID">
                            <p class="card-text">$$stockPrice</p>
                        </div>
                        <input type="button" class="btn btn-warning buyProduct" value="Add to Cart" $statusBtn>
                        <div class="input-group mb-3 qty">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Qty</span>
                            </div>
                            <input type="number" class="form-control" name="buyQty" value="1" min="1" max="$remainingStock">
                        </div>
                    </div>
                </div>
            </form>
EOD;
        }
    } else {
        $output = <<<EOD
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-5">
        <h3>No Products Found!</h3>
        </div>
EOD;
    }
    mysqli_free_result($rs);
    mysqli_close($conn);
    echo $output;
}
?>
