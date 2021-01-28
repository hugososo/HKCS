// This js handle the pagination of tent_order.php, cust_order.php, Inventory.php

// default variable for processInfo()
var sortBy ="DESC";
var where ="";

//used to keep track if user want new result set
var isNew = true;

// two ajax call to show the info of customer and tenant pages
function processInfo(page,table,where,args,sort){

    if(table === "orderitem,orders"){

        //to keep the past result set if user not searching
        if(where !== "" && args !== ""){
            isNew = true;
        }else{
            isNew = false;
        }
    }

    // first ajax call find total rows, then create page buttons from that result
    $.ajax({
        type: "GET",
        url: "selectPage.php",
        // cache:false,
        data: { currentPage:page, where:where, table:table,args:args,sort:sort,isNew:isNew},
        success: function(response){

            // if success, put all html in selectPage.php into #pageRow
            $("#pageRow").html(response);

            // after first successful call, the second one echo the actual content
            // of customer and tenant pages
            $.ajax({
                type: "GET",
                // cache:false,
                url: "FindResultsByArgs.php",
                // retreive db result using these parameter
                data: { table:table, where:where, args:args,sort:sort,isNew:isNew},
                success: function(res){

                    // if success, put all db result  into #collapseRecord
                    $("#collapseRecord").html(res);
                }
            });
        }
    });
}

// show previous page using processInfo()
function prev(table, where){
    // get the current pageNum
    var pageNum = $("#pageSelector option").filter(':selected').val();
    pageNum = parseInt(pageNum);
    processInfo(pageNum-1,table,where,"",sortBy);
}

// show next page using processInfo()
function next(table, where){
    // get the current pageNum
    var pageNum = $("#pageSelector option").filter(':selected').val();
    pageNum = parseInt(pageNum);
    processInfo(pageNum+1,table,where,"",sortBy);
    // $("#pageSelector").val(pageNum+1);
}

// show selected page using processInfo()
function changePage(table, where){
    // get the current pageNum
    var pageNum = $("#pageSelector option").filter(':selected').val();
    processInfo(pageNum,table,where,"",sortBy);
    // $("#pageSelector").val(pageNum+1);
}


