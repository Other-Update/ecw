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

END