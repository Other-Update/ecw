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
END