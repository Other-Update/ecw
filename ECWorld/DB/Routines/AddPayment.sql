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
END