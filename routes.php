<?php

function routes($path){
    switch($path){
        case '/deposit':
            require("./app/controller/depositController.php");
            break;
        case '/withdraw':
            require("./app/controller/withdrawController.php");
            break;
        case '/deleteacc':
            require("./app/controller/deleteAccController.php");
            break;
        case '/checkacc':
            require("./app/controller/checkaccountController.php");
            break;
        case '/createAccount':
            require("./app/controller/createAccController.php");
            break;
        case '/updateacc':
            require("./app/controller/updateUserinfoController.php");
            break;
        case '/getAllTransactions':
            require("./app/controller/getAllTransactions.php");
            break;
        case '/getdashboarddata':
            require("./app/controller/getdashboarddata.php");
            break;
        case '/getAllAccounts':
            require("./app/controller/userinfo.php");
            break;
        case '/transfer':
            require("./app/controller/transfer.php");
            break;
        default :
         echo "Unknown path";
            break;
    }
}