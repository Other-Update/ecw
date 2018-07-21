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
END