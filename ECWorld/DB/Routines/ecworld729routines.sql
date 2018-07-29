-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: localhost    Database: ecworld
-- ------------------------------------------------------
-- Server version	5.6.17
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddPayment`(IN `inFromUserID` INT(11), IN `inToUserID` INT(11), IN `inAmount` DECIMAL(13,2), IN `inCommissionPercent` DECIMAL(13,2), IN `inType` INT(11), IN `inMode` INT(11), IN `inRemark` VARCHAR(100), IN `inPaidAmount` DECIMAL(13,2), IN `inTotalAmount` DECIMAL(13,2), IN `inCommissionAmountPrevPur` DECIMAL(13,2), IN `inRequestID` INT(11))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
#Set user diaplay id's
SELECT DisplayID,Name INTO @fromUserDisplayID,@fromUserName FROM m_users WHERE UserID=inFromUserID;
SELECT DisplayID,Name INTO @toUserDisplayID,@toUserName FROM m_users WHERE UserID=inToUserID;
#For From User. who is giving amount
SELECT BalanceToBePaid INTO @balanceToPay FROM t_payment WHERE UserID=inFromUserID AND FromOrToUserID=inToUserID ORDER BY CreatedDate DESC LIMIT 1;
IF @balanceToPay IS NULL THEN SET @balanceToPay=0; END IF;
#SELECT Wallet into @
#SELECT ClosingBalance INTO @currentBalance FROM t_payment WHERE UserID=inFromUserID ORDER BY CreatedDate DESC LIMIT 1;
SELECT ClosingBalance INTO @currentBalance FROM t_transaction WHERE UserID=inFromUserID ORDER BY CreatedDate DESC,TransactionID DESC LIMIT 1;
IF @currentBalance IS NULL THEN SET @currentBalance=0; END IF;
#inType=1 is credit . inType=2 is debit
IF inType=1 THEN 
BEGIN
SET @amount=inAmount*-1;
SET @closingbalance=@currentBalance-inTotalAmount;
SET @balancetobepaid=@balanceToPay-inAmount;

IF @amount>0 THEN 
BEGIN
SET @remark=CONCAT_WS(' ','Received from (',@toUserDisplayID,'-',@toUserName,')');
END;
ELSE
BEGIN
SET @remark=CONCAT_WS('','Credited to (',@toUserDisplayID,'-',@toUserName,')');
END;
END IF;

END;
ELSE
BEGIN
SET @amount=inAmount;
SET @closingbalance=@currentBalance+inTotalAmount;
SET @balancetobepaid=@balanceToPay+inAmount;

IF @amount>0 THEN 
BEGIN
SET @remark=CONCAT_WS(' ','Debited from(',@toUserDisplayID,'-',@toUserName,')');
END;
ELSE
BEGIN
SET @remark=CONCAT_WS('','Reverted from (',@toUserDisplayID,'-',@toUserName,')');
END;
END IF;

END;
END IF;
INSERT INTO t_payment(UserID,FromOrToUserID,RequestID,Amount,CommissionPercent,CommissionAmountPrevPur,Type,Mode,Remark,TotalAmount,OpeningBalance,ClosingBalance,BalanceToBePaid,CreatedDate,ModifiedDate) VALUES(inFromUserID,inToUserID,inRequestID,@amount,inCommissionPercent,inCommissionAmountPrevPur,inType,inMode,@remark,inTotalAmount,@currentBalance,@closingbalance,@balancetobepaid,NOW(),NOW());
#Update From User Wallet
#UPDATE m_users SET Wallet=Wallet-inTotalAmount WHERE UserID=inFromUserID;

#For to user who is getting
SET @balanceToPay1=0;
SELECT BalanceToBePaid INTO @balanceToPay1 FROM t_payment WHERE UserID=inToUserID AND FromOrToUserID=inFromUserID ORDER BY CreatedDate DESC LIMIT 1;
IF @balanceToPay1 IS NULL THEN SET @balanceToPay1=0; END IF;
#SELECT Wallet into @wallet FROM m_users WHERE UserID=inToUserID;
#SELECT ClosingBalance INTO @currentBalance1 FROM t_payment WHERE UserID=inToUserID ORDER BY CreatedDate DESC LIMIT 1;
SELECT ClosingBalance INTO @currentBalance1 FROM t_transaction WHERE UserID=inToUserID ORDER BY CreatedDate DESC,TransactionID DESC LIMIT 1;
IF (@currentBalance1 IS NULL) THEN SET @currentBalance1=0; END IF;
#inType=1 is credit . inType=2 is debit
IF inType=1 THEN 
BEGIN
SET @amount=inAmount;
SET @closingbalance1=@currentBalance1+inTotalAmount;
SET @balancetobepaid1=@balanceToPay1+inAmount-inPaidAmount;

IF @amount>0 THEN 
BEGIN
SET @remark=CONCAT_WS(' ','Received from (',@fromUserDisplayID,'-',@fromUserName,')');
END;
ELSE
BEGIN
SET @remark=CONCAT_WS('','Credited to (',@fromUserDisplayID,'-',@fromUserName,')');
END;
END IF;

END;
ELSE
BEGIN
SET @amount=inAmount*-1;
SET @closingbalance1=@currentBalance1-inTotalAmount;
SET @balancetobepaid1=@balanceToPay1-inAmount-inPaidAmount;

IF @amount>0 THEN 
BEGIN
SET @remark=CONCAT_WS(' ','Debited from(',@fromUserDisplayID,'-',@fromUserName,')');
END;
ELSE
BEGIN
SET @remark=CONCAT_WS('','Reverted from (',@fromUserDisplayID,'-',@fromUserName,')');
END;
END IF;

END;
END IF;

#SELECT @currentBalance1;
INSERT INTO t_payment(UserID,FromOrToUserID,RequestID,Amount,CommissionPercent,CommissionAmountPrevPur,Type,Mode,Remark,PaidAmount,TotalAmount,OpeningBalance,ClosingBalance,BalanceToBePaid,CreatedDate,ModifiedDate) VALUES(inToUserID,inFromUserID,inRequestID,@amount,inCommissionPercent,inCommissionAmountPrevPur,inType,inMode,@remark,inPaidAmount,inTotalAmount,@currentBalance1,@closingbalance1,@balancetobepaid1,NOW(),NOW());
#SELECT @currentBalance1;
#Update To User Wallet is happening in trigger
#UPDATE m_users SET Wallet=Wallet+inTotalAmount WHERE UserID=inToUserID;

#SET @ioIsSuccess=1;
END ;;
DELIMITER ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddRecharge`(IN `inReachargeNo` VARCHAR(100), IN `inAmount` DECIMAL(13,2), IN `inStatus` INT(11), IN `inNetworkProviderName` VARCHAR(100), IN `inRequestID` INT(11), IN `inReqDateTime` DATETIME, IN `inReqReceivedDateTime` DATETIME, IN `inCreatedDate` DATETIME, IN `inCreatedBy` INT(11), IN `inTotalAmount` DECIMAL(13,2))
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
#Note: inStatus isn't used. Column has been removed.
#Note: inReqDateTime isn't used. Column has been removed.
#Note: inReqReceivedDateTime isn't used. Column has been removed.

SET @currentBalance=0;
SELECT ClosingBalance INTO @currentBalance FROM t_transaction WHERE UserID=inCreatedBy ORDER BY CreatedDate DESC,TransactionID DESC LIMIT 1;
IF @currentBalance IS NULL THEN SET @currentBalance=0; END IF;
INSERT INTO t_recharge(UserID,ReachargeNo,Amount,NetworkProviderName,RequestID,CreatedDate,CreatedBy,ModifiedBy,Balance,TotalAmount) VALUE(inCreatedBy,inReachargeNo,inAmount,inNetworkProviderName,inRequestID,inCreatedDate,inCreatedBy,inCreatedBy,@currentBalance-TotalAmount,inTotalAmount);
END ;;
DELIMITER ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddTransaction`(IN `inTransactionType` INT(11), IN `inUserID` INT(11), IN `inType` INT(11), IN `inAmount` DECIMAL(13,2), IN `inRequestID` INT(11), IN `inClosingBalance` DECIMAL(13,2), IN `inRemark` VARCHAR(255), IN `inReferenceTable` VARCHAR(100), IN `inReferenceID` INT(11), IN `inCreatedBy` INT(11))
    NO SQL
    SQL SECURITY INVOKER
BEGIN
#Note1: In coming OpeningBal and ClosingBal aren't used
#Note2: inType Column removed from transaction table
#Note: inCreatedBy Shouldn't be used
#Note: Caller1. Insert trigger on t_payment. 
#Note: caller2. Insert trigger on t_recharge on insert.
#Note: Caller3: failure update on t_request(Only for recharge)

#Calculate opening balance and closing balance here only.
#intype=1 is Credit. 2 is debit
SET @currentBalance=0;
SELECT ClosingBalance INTO @currentBalance FROM t_transaction WHERE UserID=inUserID ORDER BY CreatedDate DESC,TransactionID DESC LIMIT 1;
IF @currentBalance IS NULL OR @currentBalance=0 THEN 
BEGIN
SET @currentBalance=0; 
INSERT INTO t_transaction(TransactionType,RequestID,UserID,Amount,OpeningBalance,ClosingBalance,Remark,ReferenceTable,ReferenceID,CreatedBy) VALUES(inTransactionType,inRequestID,inUserID,inAmount,@currentBalance,@currentBalance+inAmount,inRemark,inReferenceTable,inReferenceID,inUserID);
END;
ELSE
BEGIN
INSERT INTO t_transaction(TransactionType,RequestID,UserID,Amount,OpeningBalance,ClosingBalance,Remark,ReferenceTable,ReferenceID,CreatedBy) VALUES(inTransactionType,inRequestID,inUserID,inAmount,((SELECT t.ClosingBalance FROM t_transaction t WHERE t.UserID=inUserID ORDER BY t.CreatedDate DESC,t.TransactionID DESC LIMIT 1)),(SELECT t.ClosingBalance FROM t_transaction t WHERE t.UserID=inUserID ORDER BY t.CreatedDate DESC,t.TransactionID DESC LIMIT 1)+inAmount,inRemark,inReferenceTable,inReferenceID,inUserID);
END;
END IF;
/*IF inType=1 THEN 
BEGIN
INSERT INTO t_transaction(TransactionType,UserID,Amount,OpeningBalance,ClosingBalance,Remark,ReferenceTable,ReferenceID,CreatedBy) VALUES(inTransactionType,inUserID,inAmount,@currentBalance,@currentBalance+inAmount,inRemark,inReferenceTable,inReferenceID,inUserID);
END;
ELSE
BEGIN
INSERT INTO t_transaction(TransactionType,UserID,Amount,OpeningBalance,ClosingBalance,Note,ReferenceTable,ReferenceID,CreatedBy) VALUES(inTransactionType,inUserID,inAmount*-1,@currentBalance,@currentBalance-inAmount,inRemark,inReferenceTable,inReferenceID,inUserID);
END;
END IF;
*/
#Example call
#CALL AddTransaction(1,1,1,100,100,100,"test","test",1,1)
END ;;
DELIMITER ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `RevertRecharge`(IN `inRequesterID` INT(11), IN `inRequestID` INT(11), IN `inTargetAmount` DECIMAL(13,2), IN `inRespCode` VARCHAR(10), IN `inNow` DATETIME)
    NO SQL
BEGIN

#We have to update by RechargeID only. Since AddRecharge is not sending back inserted ID we are using this method.

UPDATE t_recharge SET Status=4,RcResponse=inRespCode,RcResReceivedDateTime=inNow WHERE RequestID=inRequestID AND CreatedBy=inRequesterID;
#Reverting wallet is done by Update trigger registered with t_recahrge table

#Update request table
#3 is completed
UPDATE t_request SET Status=3 WHERE RequestID=inRequestID;

END ;;
DELIMITER ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateRecharge`(IN `inRechargeID` INT(11), IN `inecwRCStatus` INT(11), IN `inReceivedDT` DATETIME, IN `inResponse` TEXT, IN `inRcResOpTransID` VARCHAR(100), IN `inResponseAsIs` TEXT)
    NO SQL
BEGIN

#We have to update by RechargeID only. Since AddRecharge is not sending back inserted ID we are using this method.
#Now inRechargeID is requestID . Once SP is returnning proper ID then it will be proper RechargeID
#UPDATE t_request SET Status=inecwRCStatus WHERE RequestID=inRechargeID;

UPDATE t_recharge SET RcResponse=inResponse,RcResReceivedDateTime=inReceivedDT,RcResOpTransID=inRcResOpTransID,ResponseAsIs=inResponseAsIs WHERE RequestID=inRechargeID;
#AND CreatedBy=inRequesterID;

#Reverting wallet is done by Update trigger registered with t_recahrge table in case of failure

#Update request table. Move to update trigger
#3 is completed
#UPDATE t_request SET Status=3 WHERE RequestID=inRequestID;

END ;;
DELIMITER ;
-- Dump completed on 2018-07-29  8:15:03
