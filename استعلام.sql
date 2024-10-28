SELECT
    gom1.*,
    gom1.`totalNet` - gom1.`costLast` AS lastPro,
    gom1.`totalNet` - gom1.`costAvr` AS avrPro
FROM
    (
    SELECT
        om.*,
        gocj.`total`,
        SUM(gocj.`total`) *(1 - om.`discountRatio` / 100) AS totalNet,
        gocj.`costLast`,
        gocj.`costAvr`,
        gocj.`orderVersion`
    FROM
        `orders_meta` om
    LEFT JOIN (
        SELECT
            goc.`orderID`,
            goc.`orderVersion`,
            SUM(goc.`totalItemPrice`) AS total,
            SUM(i.`payPrice` * goc.`itemQty`) AS costLast,
            SUM(i.`avrPayPrice` * goc.`itemQty`) AS costAvr
        FROM
            `get_order_content` goc
        LEFT JOIN `items` i USING(`itemID`)
        GROUP BY
            `orderID`,
            `orderVersion`
    ) gocj USING(`orderID`)
GROUP BY
    `orderID`,
    `orderVersion`
) gom1






















(
    SELECT
        `alln`.`readersID` AS `readersID`,
        `alln`.`noteID` AS `noteID`,
        `alln`.`launcherID` AS `launcherID`,
        `alln`.`recipientID` AS `recipientID`,
        `alln`.`type` AS `type`,
        `alln`.`importance` AS `importance`,
        `alln`.`referenceID` AS `referenceID`,
        `alln`.`createdDate` AS `createdDate`,
        `alln`.`noteGroup` AS `noteGroup`,
        `alln`.`launcherName` AS `launcherName`,
        `alln`.`launcherPharmacy` AS `launcherPharmacy`,
        `us`.`username` AS `recipientName`
    FROM
        (
            (
            SELECT
                `stage1`.`readersID` AS `readersID`,
                `stage1`.`noteID` AS `noteID`,
                `stage1`.`launcherID` AS `launcherID`,
                `stage1`.`recipientID` AS `recipientID`,
                `stage1`.`type` AS `type`,
                `stage1`.`importance` AS `importance`,
                `stage1`.`referenceID` AS `referenceID`,
                `stage1`.`createdDate` AS `createdDate`,
                `stage1`.`noteGroup` AS `noteGroup`,
                `us`.`username` AS `launcherName`,
                `us`.`pharmacy` AS `launcherPharmacy`
            FROM
                (
                    (
                        (
                        SELECT
                            `sn`.`isRead` AS `readersID`,
                            `sn`.`noteID` AS `noteID`,
                            `sn`.`launcherID` AS `launcherID`,
                            `sn`.`recipientID` AS `recipientID`,
                            `sn`.`type` AS `type`,
                            `sn`.`importance` AS `importance`,
                            `sn`.`referenceID` AS `referenceID`,
                            `sn`.`createdDate` AS `createdDate`,
                            'specific' AS `noteGroup`
                        FROM
                            `lb_medical_castle`.`specific_notifications` `sn`
                    )
                UNION ALL
                    (
                    SELECT
                        `bnr`.`readerID` AS `readersID`,
                        `bn`.`noteID` AS `noteID`,
                        `bn`.`launcherID` AS `launcherID`,
                        `bn`.`recipients` AS `recipientID`,
                        `bn`.`type` AS `type`,
                        `bn`.`importance` AS `importance`,
                        `bn`.`referenceID` AS `referenceID`,
                        `bn`.`createdDate` AS `createdDate`,
                        'broad' AS `broad`
                    FROM
                        (
                            `lb_medical_castle`.`broad_notifications` `bn`
                        LEFT JOIN `lb_medical_castle`.`broad_notifications_readers` `bnr`
                        ON
                            (`bnr`.`noteID` = `bn`.`noteID`)
                        )
                )
                    ) `stage1`
                LEFT JOIN `lb_medical_castle`.`users` `us`
                ON
                    (`stage1`.`launcherID` = `us`.`userID`)
                )
        ) `alln`
    LEFT JOIN `lb_medical_castle`.`users` `us`
    ON
        (`alln`.`recipientID` = `us`.`userID`)
        )
)


(select `alln`.`readersID` AS `readersID`,`alln`.`noteID` AS `noteID`,`alln`.`launcherID` AS `launcherID`,`alln`.`recipientID` AS `recipientID`,`alln`.`type` AS `type`,`alln`.`importance` AS `importance`,`alln`.`referenceID` AS `referenceID`,`alln`.`createdDate` AS `createdDate`,`alln`.`noteGroup` AS `noteGroup`,`alln`.`launcherName` AS `launcherName`,`us`.`username` AS `recipientName` from ((select `stage1`.`readersID` AS `readersID`,`stage1`.`noteID` AS `noteID`,`stage1`.`launcherID` AS `launcherID`,`stage1`.`recipientID` AS `recipientID`,`stage1`.`type` AS `type`,`stage1`.`importance` AS `importance`,`stage1`.`referenceID` AS `referenceID`,`stage1`.`createdDate` AS `createdDate`,`stage1`.`noteGroup` AS `noteGroup`,`us`.`username` AS `launcherName` from (((select `sn`.`isRead` AS `readersID`,`sn`.`noteID` AS `noteID`,`sn`.`launcherID` AS `launcherID`,`sn`.`recipientID` AS `recipientID`,`sn`.`type` AS `type`,`sn`.`importance` AS `importance`,`sn`.`referenceID` AS `referenceID`,`sn`.`createdDate` AS `createdDate`,'specific' AS `noteGroup` from `lb_medical_castle`.`specific_notifications` `sn`) union all (select `bnr`.`readerID` AS `readersID`,`bn`.`noteID` AS `noteID`,`bn`.`launcherID` AS `launcherID`,`bn`.`recipients` AS `recipientID`,`bn`.`type` AS `type`,`bn`.`importance` AS `importance`,`bn`.`referenceID` AS `referenceID`,`bn`.`createdDate` AS `createdDate`,'broad' AS `broad` from (`lb_medical_castle`.`broad_notifications` `bn` left join `lb_medical_castle`.`broad_notifications_readers` `bnr` on(`bnr`.`noteID` = `bn`.`noteID`)))) `stage1` left join `lb_medical_castle`.`users` `us` on(`stage1`.`launcherID` = `us`.`userID`))) `alln` left join `lb_medical_castle`.`users` `us` on(`alln`.`recipientID` = `us`.`userID`)))