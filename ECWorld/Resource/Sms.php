<?php
$langSMS = array();
$langAPI = array();
//Common
$langSMS['0'] = 'No Need to Send SMS';
$langAPI['0'] = 'No Need to Send SMS';

$langSMS['01'] = 'Dear [CUSTOMERNAME], Delayed Request(It is more than 5 mins). [FOOTERMESSAGE].';
$langAPI['01'] = 'Delayed Request(It is more than 5 mins).';

$langSMS['02'] ='Dear [CUSTOMERNAME], Unable to create request . [FOOTERMESSAGE].';
$langAPI['02'] ='Unable to create request';

$langSMS['03'] ='Dear [CUSTOMERNAME], Transaction ID not found. Transaction ID=[TRANSACTIONID] . [FOOTERMESSAGE].';
$langAPI['03'] ='Transaction ID not found. Transaction ID=[TRANSACTIONID].';

$langSMS['Duplicate_Request'] ='Dear [CUSTOMERNAME], Duplicate request received,[MESSAGE],Try again after [WAITINGMINUTES].[FOOTERMESSAGE].';//Woerking-1 . With message. Number and amount.Try after X mins. Covered by Recharge_F_SameNo_SameAmnt.
$langAPI['Duplicate_Request'] ='Duplicate Request .';

//Helpers
$langSMS['Helper_Your_Acc_Balance'] ='your bal is rs.[WALLETBALANCE]';

$langSMS['Helper_Dear_Customer'] ='Dear [CUSTOMERNAME]';


//User Section = 1
$langSMS['NewUserToParent_s']='Dear [CUSTOMERNAME], New [USERROLE] [NEWUSERNAME] created successfully with Customer Id : [NEWUSERID].'.$langSMS['Helper_Your_Acc_Balance'].'. [FOOTERMESSAGE]';//Working-2. Fixed.
$langAPI['NewUserToParent_s']='New [USERROLE] [NEWUSERNAME] created successfully with Customer Id : [NEWUSERID].'.$langSMS['Helper_Your_Acc_Balance'].'.';

$langSMS['NewUserToParent_f']='Dear [CUSTOMERNAME], Unable to Create New [USERROLE] - [NEWUSERNAME] ([FAILUREREASON]). Your current balance is Rs.[WALLETBALANCE]. [FOOTERMESSAGE]';//Working-3. Fixed.
$langAPI['NewUserToParent_f']='Unable to Create New [USERROLE] - [NEWUSERNAME] ([FAILUREREASON]). Your current balance is Rs.[WALLETBALANCE].';

$langSMS['NewUserToNewUser_s']='Dear [NEWUSERNAME],Welcome to EC World. Your Customer ID: [NEWUSERID] Username:[NEWUSERMOBILE] Password:[PASSWORD] website: [FOOTERMESSAGE].';// EC Care No: 9940955807(9am to 9.30pm on weekdays, 9am to 9pm on Sundays), Server Nos:9790001882/7708275911, Recharge Format: Number(space)Amount';//Working-4.Fixed.
$langAPI['NewUserToNewUser_s']='Welcome to EC World Communication. Your Customer ID: [NEWUSERID] Username:[NEWUSERMOBILE] Password:[PASSWORD] Pin Number:[PINNUMBER] website: [FOOTERMESSAGE]. EC Care No: 9940955807(9am to 9.30pm on weekdays, 9am to 9pm on Sundays), Server Nos:9790001882/7708275911, Recharge Format: Number <space> Amount';

$langSMS['11']='Dear [CUSTOMERNAME], Parent Not Found. [FOOTERMESSAGE].';
$langAPI['11']='Parent Not Found.';

$langSMS['12']='Dear [CUSTOMERNAME], [NETWORKNAME] recharge permission not allowed.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Handled by 24.. 12 is used.
$langAPI['12']='[NETWORKNAME] recharge permission not allowed..';

$langSMS['13']='Dear [CUSTOMERNAME], Successfully Added. [FOOTERMESSAGE].';
$langAPI['13']='Successfully Added.';

$langSMS['14']='Dear [CUSTOMERNAME], Feature Unassigned. [FOOTERMESSAGE].';
$langAPI['14']='Feature Unassigned.';

$langSMS['15']='Dear [CUSTOMERNAME], Failed to Add User.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Working 6. Fixed but not verified. Rare message. failed for unknown reason.
$langAPI['15']='Failed to Add User.';

$langSMS['16']='Dear [CUSTOMERNAME], User Limit Reached. Contact EC World.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Working 7 . Handled by NewUserToParent_f.
$langAPI['16']='User Limit Reached. Contact EC World.';

$langSMS['17']="Dear [CUSTOMERNAME], Can't be recharge this amount, your balance is low."+$langSMS['Helper_Your_Acc_Balance']+". [FOOTERMESSAGE].";//Working 8.Not being used.
$langAPI['17']="Your balance is low";//.[WALLETBALANCE]";

$langSMS['18']='Unable to Verify User.EC Care No: 9940955807(9am to 9.30pm on weekdays, 9am to 9pm on Sundays) [FOOTERMESSAGE].';//Working-9. Fixed.
$langAPI['18']='Unable to Verify User.EC Care No: 9940955807(9am to 9.30pm on weekdays, 9am to 9pm on Sundays) [FOOTERMESSAGE].';

$langSMS['UserBalanceDetails']="Dear [CUSTOMERNAME], Your current balance is Rs.[WALLETBALANCE]. Today purchase: Rs.[TODAYPURCHASE], Sales: Rs.[TODAYSALES]. [FOOTERMESSAGE]";//Working 10. Fixed.
$langAPI['UserBalanceDetails']='Your current balance is Rs.[WALLETBALANCE]. Today purchase:Rs [TODAYPURCHASE], Sales: Rs [TODAYSALES].';

$langSMS['Balance_Sub_Account']='Dear [CUSTOMERNAME], [SUBACCOUNTID]-[SUBACCOUNTNAME] balance is Rs.[WALLETBALANCE]. [FOOTERMESSAGE]';//Working 11.Fixed.
$langAPI['Balance_Sub_Account']='[SUBACCOUNTID]-[SUBACCOUNTNAME] balance is Rs.[WALLETBALANCE].';

$langSMS['Sub_Account_Invalid']='Dear [CUSTOMERNAME], Invalid sub account.[CUSTOMERMESSAGE]. [FOOTERMESSAGE]';//Working 12.Fixed.
$langAPI['Sub_Account_Invalid']='Invalid sub account - [SUBACCOUNTID].';

$langSMS['111']='Dear [CUSTOMERNAME], Mobile number already exists.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Working 13. Handled by NewUserToParent_f.
$langAPI['111']='Mobile number already exists.[MOBILENUMBER]';

$langSMS['EnableDisableAccount']='Dear [CUSTOMERNAME], [SUBACCOUNTID]-[SUBACCOUNTNAME] is [ACTION]. [FOOTERMESSAGE].';//Working 14. Fixed.
$langAPI['EnableDisableAccount']='[SUBACCOUNTID]-[SUBACCOUNTNAME] is [ACTION].';

$langSMS['113']='Dear [CUSTOMERNAME], Invalid sub account [DISPLAYUSERID] . [FOOTERMESSAGE].';//Working 15. Should have been handled by Sub_Account_Invalid.
$langAPI['113']='[DISPLAYUSERID] is unable to [ACTION].';

//Recharge Section = 2

$langSMS['Recharge_F_SameNo_SameAmnt']='Dear [CUSTOMERNAME], Same number and same amount are not allowed within [DENIEDMINSDURATION] mins.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Working 16.Fixed.
$langAPI['Recharge_F_SameNo_SameAmnt']='Same number and same amount are not allowed within [DENIEDMINSDURATION] mins.';

$langSMS['Recharge_F_SameNo_DiffAmnt']='Dear [CUSTOMERNAME], Same number and different amount are not allowed within [DENIEDMINSDURATION] mins. [FOOTERMESSAGE].';//Working 17. Fixed.
$langAPI['Recharge_F_SameNo_DiffAmnt']='Same number and different amount are not allowed within [DENIEDMINSDURATION] mins.';

$langSMS['Incorrect_Message_Format']='Dear [CUSTOMERNAME], Message Format incorrect.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//working 18.Fixed.
$langAPI['Incorrect_Message_Format']='Message Format incorrect.[CUSTOMERMESSAGE].';

$langSMS['22']='Dear [CUSTOMERNAME], Service not found.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//working 19. Shoudl have been handled by 214.
$langAPI['22']='Service not found.';

$langSMS['23']='Dear [CUSTOMERNAME], New operator. Use operator code.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Not in automnp table
//Working 20.Fixed.
$langAPI['23']='New operator. Use operator code.';

$langSMS['recharge_f_invalidamount']='Dear [CUSTOMERNAME], Invalid Amount.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Workin 21.Fixed.
$langAPI['recharge_f_invalidamount']='Invalid Amount.';

$langSMS['24']='Dear [CUSTOMERNAME], [NETWORKNAME] recharge permission not allowed.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Working-5. Fixed.
$langAPI['24']='[NETWORKNAME] recharge permission not allowed..';

$langSMS['25']='Dear [CUSTOMERNAME], Amount should be greater than Rs.[MAXMINAMOUNT].[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Working 22.Fxied.
$langAPI['25']='Amount should be greater than Rs.[MAXMINAMOUNT]';

$langSMS['26']='Dear [CUSTOMERNAME], Amount should be lesser than Rs.[MAXMINAMOUNT].[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Working 23.Fxied.
$langAPI['26']='Amount should be lesser than Rs.[MAXMINAMOUNT].';

$langSMS['27']='Dear [CUSTOMERNAME], Invalid Amount, Kindly Contact EC Care.www.ecworld.in';//covered by 'recharge_f_invalidamount'
$langAPI['27']='Invalid Amount, Kindly Contact EC Care.';

$langSMS['28']='Dear [CUSTOMERNAME], No Gateway Assigned.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';
//Woerking-24.Should work fine.
$langAPI['28']='No Gateway Assigned.';

$langSMS['29']='Dear [CUSTOMERNAME], No Gateway Found.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';
//Working 25. Should work fine.
$langAPI['29']='No Gateway Found.';

$langSMS['210']='Dear [CUSTOMERNAME], Recharge success for mobile [MOBILENUMBER]. [FOOTERMESSAGE].';//Suspense message. Delay this message for X seconds - configured in gen settings. Working 26. Need to check later.
$langAPI['210']='Recharge success.';

$langSMS['211']='Dear [CUSTOMERNAME], [NETWORKNAME] Rch Success and Confirm, Num:[RECHARGENUMBER] Amt:[RECHARGEAMOUNT] Txid:[OPERATORTRANSACTIONID]. Your ec world bal is:[WALLETBALANCE], [FOOTERMESSAGE]';//Working 27.Fixed.
$langAPI['211']='Rch Success and Confirm, Num:[RECHARGENUMBER] Amt:[RECHARGEAMOUNT] Txid:[OPERATORTRANSACTIONID].';

$langSMS['212']='Dear [CUSTOMERNAME], [NETWORKNAME] Rch Failure, Num:[RECHARGENUMBER] Amt:[RECHARGEAMOUNT] Txid:[OPERATORTRANSACTIONID]. Your ec world bal is:[WALLETBALANCE], [FOOTERMESSAGE]';//Working 28.Fixed.
$langAPI['212']='[NETWORKNAME] Rch Failure, Num:[RECHARGENUMBER] Amt:[RECHARGEAMOUNT] Txid:[OPERATORTRANSACTIONID].';

$langSMS['213']='Dear [CUSTOMERNAME], Invalid Mobile Number. [FOOTERMESSAGE].';
//Working 29.Should work fine.
$langAPI['213']='Invalid Mobile Number.';

$langSMS['214']='Dear [CUSTOMERNAME], Service Not Found.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Possible duplicate of Recharge_DTH_Not_Found
$langAPI['214']='Service Not Found.';

$langSMS['Recharge_DTH_Not_Found']='Dear [CUSTOMERNAME], DTH service is not found.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Covered by 215
$langAPI['Recharge_DTH_Not_Found']='DTH service is not found.';

$langSMS['215']='Dear [CUSTOMERNAME], DTH service is not found.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Possible duplicate of 214
$langAPI['215']='DTH service is not found.';//Working 30. Fixed.

$langSMS['216']='Dear [CUSTOMERNAME], No Network found. Could be postpaid. [FOOTERMESSAGE].';//Dev message
$langAPI['216']='No Network found. Could be postpaid.';

$langSMS['217']='Dear [CUSTOMERNAME], Not Enough Balance. [FOOTERMESSAGE].';
$langAPI['217']='Not Enough Balance.';//Covered by 17

//Network problem. Message will be set by admin in through the web app.
$langSMS['218']='Dear [CUSTOMERNAME], [MESSAGE].[CUSTOMERMESSAGE]. [FOOTERMESSAGE].';//Working 31 . Fixed.
$langAPI['218']='[MESSAGE]';

$langSMS['Payment_s']="Dear [CUSTOMERNAME], Fund Rs.[AMOUNT] successfully transferred to [RECEIVERROLE] ID:[RECEIVERUSERID]([RECEIVERUSERNAME]). Your Bal is [WALLETBALANCE]. www.ecworld.co.in, Thanks";
//Payment-3
//Working 32. Fixed.
$langAPI['Payment_s']="Balance of Rs. [AMOUNT] successfully transferred to [RECEIVERROLE] [RECEIVERUSERNAME] ID:[RECEIVERUSERID]([RECEIVERMOBILE]). Ref id is:[TRANSREQUESTID]. Your Balance is [WALLETBALANCE].";

$langSMS['Payment_Receiver_s']="Dear [RECEIVERNAME], Balance of Rs. [AMOUNT] successfully credited to your account. Your Balance is [RECEIVERBALANCE]. [FOOTERMESSAGE].";//Working 33. Fixed.
$langAPI['Payment_Receiver_s']="Rs[AMOUNT] credited succesfull. Sent by [SENDERID]-[SENDENAME]. Reference id is [TRANSREQUESTID].";//No need of langAPI

$langSMS['Payment_f_NotAuth']="Dear [CUSTOMERNAME], You are not authorized to send amount to this member.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].";//Working 34.Fixed.
$langAPI['Payment_f_NotAuth']="You are not authorized to send amount to this member.";

$langSMS['Payment_f_NoBalance']="Dear [CUSTOMERNAME], Can't transfer this amount, your balance is low.".$langSMS['Helper_Your_Acc_Balance'].".[CUSTOMERMESSAGE]. [FOOTERMESSAGE].";//Working 35. Fixed
$langAPI['Payment_f_NoBalance']="Can't transfer this amount, your balance is low. Your current balance is Rs.[WALLETBALANCE].";

$langSMS['Payment_f_MinMaxTrans']="Dear [CUSTOMERNAME], You are allowed to transfer amount is from Rs.[MINIMUMTRANSFERAMOUNT] to Rs.[MAXIMUMTRANSFERAMOUNT] per member.[CUSTOMERMESSAGE]. [FOOTERMESSAGE].";//Working 36. Fixed
$langAPI['Payment_f_MinMaxTrans']="You are allowed to transfer amount is from Rs.[MINIMUMTRANSFERAMOUNT] to Rs.[MAXIMUMTRANSFERAMOUNT] per member.";

$langSMS['Payment_f']="Dear [CUSTOMERNAME], Failed to transfer Rs [AMOUNT] to [RECEIVERUSERID]. Your ec world bal is:[WALLETBALANCE].[CUSTOMERMESSAGE]. [FOOTERMESSAGE].";//Working 37.Fixed.
$langAPI['Payment_f']="Failed to transfer Rs [AMOUNT] to [RECEIVERUSERID]. Your ec world bal is [WALLETBALANCE].";

$langSMS['Payment_Rev_s']=$langSMS['Helper_Dear_Customer'].", Balance of Rs. [AMOUNT] credited to your account. From ID: [ORIGINALRECEIVERID]([ORIGINALRECEIVERNAME]). Ref ID is [TRANSREQUESTID]. Your Balance is [WALLETBALANCE]. [FOOTERMESSAGE].";//Working 38.Fixed.
$langAPI['Payment_Rev_s']="Balance of Rs. [AMOUNT] credited to your account. From ID: [ORIGINALRECEIVERID]([ORIGINALRECEIVERNAME]). Ref ID is [TRANSREQUESTID]. Your Balance is [WALLETBALANCE].";

$langSMS['Payment_Rev_Receiver_s']="Dear [ORIGINALRECEIVERNAME],Balance of Rs.[AMOUNT] debited to your account. Ref ID is [TRANSREQUESTID].Your account balance is Rs [ORIGINALRECEIVERBALANCE]. [FOOTERMESSAGE].";//Workin 39.Fixed.
$langAPI['Payment_Rev_Receiver_s']="Balance of Rs.[AMOUNT] debited to your account. Ref ID is[TRANSREQUESTID].Your account balance is Rs [ORIGINALRECEIVERBALANCE].";//No need langAPI

$langSMS['Payment_Rev_f']=$langSMS['Helper_Dear_Customer'].", Failed to revert your fund transfer for reference id [TRANSREQUESTID] ".$langSMS['Helper_Your_Acc_Balance'].". [FOOTERMESSAGE].";//Working 40.Fixed.
$langAPI['Payment_Rev_f']="Failed to revert your fund transfer for reference id [TRANSREQUESTID].";
?>