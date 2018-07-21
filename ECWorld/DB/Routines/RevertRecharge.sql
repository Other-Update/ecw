CREATE DEFINER=`root`@`localhost` PROCEDURE `RevertRecharge`(IN `inRequesterID` INT(11), IN `inRequestID` INT(11), IN `inTargetAmount` DECIMAL(13,2), IN `inRespCode` VARCHAR(10), IN `inNow` DATETIME)
    NO SQL
BEGIN

#We have to update by RechargeID only. Since AddRecharge is not sending back inserted ID we are using this method.

UPDATE t_recharge SET Status=4,RcResponse=inRespCode,RcResReceivedDateTime=inNow WHERE RequestID=inRequestID AND CreatedBy=inRequesterID;
#Reverting wallet is done by Update trigger registered with t_recahrge table

#Update request table
#3 is completed
UPDATE t_request SET Status=3 WHERE RequestID=inRequestID;

END