-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2018 at 03:40 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maxpro_erp_new`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `generate_chalan_by_order` (IN `orderID` VARCHAR(255), IN `type` VARCHAR(120), IN `client_id` INT(11))  SELECT sp.quantity,sp.price,sp.bonus,iv.invoice_code,iv.order_no,coa.acc_final_name,sp.vat,sp.total_amount_w_vat,sp.sale_date,
GROUP_CONCAT(p.name,'(',pak.name,'(',pak.quantity,')', unt.name , '(' , pak.unit_quantity, ')' ) as product_name,unt.name as unit

FROM mxp_sale_products sp 
JOIN mxp_invoice iv ON(iv.id=sp.invoice_id)
JOIN mxp_product p ON(p.id=sp.product_id)
JOIN mxp_chart_of_acc_heads coa ON(coa.chart_o_acc_head_id=sp.client_id)
JOIN mxp_packet pak ON(pak.id=p.packing_id)
JOIN mxp_unit unt ON(unt.id=pak.unit_id)
WHERE iv.type=type AND iv.order_no=orderID AND coa.chart_o_acc_head_id=client_id
GROUP BY iv.id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_accounts_sub_head` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))  SELECT mash.*,

CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details



FROM mxp_accounts_sub_heads mash

inner join mxp_accounts_heads mah on(mash.accounts_heads_id = mah.accounts_heads_id)



WHERE mash.group_id=grp_id

AND mash.company_id=comp_id

AND mash.is_deleted=0

order by mash.accounts_sub_heads_id desc 

limit startedAt,limits$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_acc_class` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))  SELECT mac.*,

mash.sub_head,

CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details



FROM mxp_acc_classes mac

inner join mxp_accounts_heads mah on(mac.accounts_heads_id = mah.accounts_heads_id)

inner join mxp_accounts_sub_heads mash on(mash.accounts_sub_heads_id = mac.accounts_sub_heads_id)



WHERE mac.group_id=grp_id

AND mac.company_id=comp_id

AND mac.is_deleted=0

order by mac.mxp_acc_classes_id desc 

limit startedAt,limits$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_acc_sub_class` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))  SELECT mahsc.*,

mac.head_class_name,

mash.sub_head,

CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details



FROM mxp_acc_head_sub_classes mahsc

inner join mxp_acc_classes mac on(mac.mxp_acc_classes_id = mahsc.mxp_acc_classes_id)

inner join mxp_accounts_heads mah on(mah.accounts_heads_id = mahsc.accounts_heads_id)

inner join mxp_accounts_sub_heads mash on(mash.accounts_sub_heads_id = mahsc.accounts_sub_heads_id)



WHERE mahsc.group_id=grp_id

AND mahsc.company_id=comp_id

AND mahsc.is_deleted=0

order by mahsc.mxp_acc_head_sub_classes_id desc 

limit startedAt,limits$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_chart_of_accounts` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))  SELECT mcoah.*,

mahsc.head_sub_class_name,

mac.head_class_name,

mash.sub_head,

CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details



FROM mxp_chart_of_acc_heads mcoah

inner join mxp_acc_head_sub_classes mahsc on(mahsc.mxp_acc_head_sub_classes_id = mcoah.mxp_acc_head_sub_classes_id)

inner join mxp_acc_classes mac on(mac.mxp_acc_classes_id = mcoah.mxp_acc_classes_id)

inner join mxp_accounts_heads mah on(mah.accounts_heads_id = mcoah.accounts_heads_id)

inner join mxp_accounts_sub_heads mash on(mash.accounts_sub_heads_id = mcoah.accounts_sub_heads_id)



WHERE mcoah.group_id=grp_id

AND mcoah.company_id=comp_id

AND mcoah.is_deleted=0

order by mcoah.chart_o_acc_head_id desc 

limit startedAt,limits$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_lc_pro_in_stocks` (IN `grp_id` INT, IN `comp_id` INT)  SELECT mlp.*,

GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,

pg.name as product_group, 

ca.acc_final_name as client_details,un.name as unit



FROM mxp_lc_purchases mlp

inner join mxp_product pr on(mlp.product_id = pr.id)

inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)

inner join mxp_unit un ON(un.id=pk.unit_id)

inner JOIN mxp_product_group pg on( pr.product_group_id=pg.id) 

inner join mxp_chart_of_acc_heads ca on(ca.chart_o_acc_head_id=mlp.client_id)



WHERE mlp.com_group_id=grp_id

AND mlp.company_id=comp_id

AND mlp.is_deleted=0

AND mlp.stock_status=0

AND mlp.lc_status=1

GROUP BY mlp.lc_purchase_id

order by mlp.lc_purchase_id desc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_lc_purchase_products` (IN `grp_id` INT(11), IN `comp_id` INT(11))  SELECT mlp.*,

GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,

mu.acc_final_name as client_details,

mi.invoice_code



FROM mxp_lc_purchases mlp

inner join mxp_product pr on(mlp.product_id = pr.id)

inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)

inner join mxp_unit un ON(un.id=pk.unit_id)
inner join mxp_chart_of_acc_heads mu on(mlp.client_id=mu.chart_o_acc_head_id)

inner join mxp_invoice mi on(mlp.invoice_id=mi.id)



WHERE mlp.com_group_id=grp_id

AND mlp.company_id=comp_id

AND mlp.is_deleted=0

GROUP BY mlp.lc_purchase_id

order by mlp.lc_purchase_id desc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_lc_stocks_by_productGrpId` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `pro_grp_id` INT(11))  NO SQL
Select mpr.id as pro_id, 

mpr.name as product_name, 

mpr.product_code, 

pk.name as packet_name, 

pk.quantity as packet_quantity, 

u.name as unit_name, 

pk.unit_quantity, 

pg.name as product_group, 

mu.first_name as client_name, 

mu.address 



from mxp_lc_purchases pp 

inner join mxp_users mu on (pp.user_id = mu.user_id) 

inner join mxp_product mpr on(pp.product_id = mpr.id) 

inner JOIN mxp_packet pk ON(mpr.packing_id=pk.id) 

inner JOIN mxp_product_group pg on( mpr.product_group_id=pg.id) 

inner join mxp_unit u ON(u.id=pk.unit_id) 

inner join mxp_stock st ON(st.product_id=mpr.id)




WHERE pp.com_group_id=grp_id

AND pp.company_id=comp_id 

AND mpr.product_group_id=pro_grp_id 

AND pp.is_deleted=0 AND pp.lc_status=1

AND st.product_id IS NOT NULL

GROUP BY mpr.id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_packets` (IN `com_id` INT(11), IN `group_id` INT(11))  SELECT pk.*, u.name as unit_name 

FROM mxp_packet pk 

inner JOIN mxp_unit u ON(pk.unit_id=u.id) 

WHERE pk.company_id=com_id 

AND pk.com_group_id=group_id 

AND pk.is_deleted=0 

AND pk.is_active=1

order by pk.id desc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_products` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))  if(startedAt >= 0) then

SELECT pr.*, GROUP_CONCAT(pk.name,'(',pk.quantity,') ',u.name,'(',pk.unit_quantity,')') as packet_name, pg.name as pord_grp_name  FROM mxp_product pr

 inner JOIN mxp_packet pk ON(pr.packing_id=pk.id) inner JOIN mxp_product_group pg on(

 pr.product_group_id=pg.id)

 inner join mxp_unit u ON(u.id=pk.unit_id)

 WHERE pr.com_group_id=grp_id AND pr.company_id=comp_id

  AND pr.is_active=1 AND pr.is_deleted=0

 GROUP BY pr.id

order by pr.id desc

limit startedAt,limits;

else

SELECT pr.*, GROUP_CONCAT(pk.name,'(',pk.quantity,') ',u.name,'(',pk.unit_quantity,')') as packet_name, pg.name as pord_grp_name  FROM mxp_product pr

 inner JOIN mxp_packet pk ON(pr.packing_id=pk.id) inner JOIN mxp_product_group pg on(

 pr.product_group_id=pg.id)

 inner join mxp_unit u ON(u.id=pk.unit_id)

 WHERE pr.com_group_id=grp_id AND pr.company_id=comp_id

  AND pr.is_active=1 AND pr.is_deleted=0

 GROUP BY pr.id;

 end if$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_products_of_stock` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))  select st.*, 

pp.price, mu.acc_final_name as client_name, 

pg.name as product_group, 

pr.name as product_name, 

pk.name as packet_name,

pk.quantity as packet_quantity, 

u.name as unit_name, 

pk.unit_quantity



from mxp_stock st 

inner join mxp_product_purchase pp on(st.purchase_id = pp.id) 

inner join mxp_chart_of_acc_heads mu on (pp.client_id = mu.chart_o_acc_head_id)

inner join mxp_product pr on(st.product_id = pr.id) 

inner JOIN mxp_packet pk ON(pr.packing_id=pk.id) 

inner JOIN mxp_product_group pg on ( pr.product_group_id=pg.id) 

inner join mxp_unit u ON(u.id=pk.unit_id)



WHERE st.com_group_id=grp_id

    AND st.company_id=comp_id

    AND st.status=1

    AND st.is_deleted=0

order by st.id desc

limit startedAt,limits$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_product_purchase_info` (IN `grp_id` INT(11), IN `comp_id` INT(11))  SELECT 

mpp.id, mpp.price, mpp.quantity,

mp.name as product_name,

mc.name as client_name,

mi.id,

GROUP_CONCAT(mtc.id) as txvid,

GROUP_CONCAT(mtc.total_amount) as total, 

GROUP_CONCAT(mtc.calculate_amount) as calculate,

GROUP_CONCAT(mt.name) as txvat_name

FROM mxp_product_purchase mpp



LEFT JOIN mxp_taxvat_cals  mtc

ON mtc.purchase_id = mpp.id 



LEFT JOIN mxp_product mp 

ON mp.id = mpp.product_id



LEFT JOIN mxp_invoice mi 

ON mi.id = mpp.invoice_id



LEFT JOIN mxp_companies mc

ON mc.id = mpp.company_i



LEFT JOIN mxp_taxvats mt

ON mt.id = mtc.vat_tax_id



WHERE mpp.com_group_id = 1 AND mpp.company_id = 0 AND mpp.is_deleted = 0 AND mpp.is_active =1 AND mtc.type ='purchase'

GROUP BY mtc.purchase_id

order by mtc.purchase_id desc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_purchase_products` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))  SELECT mpp.*,

 pr.name as product_name,

 pk.name as packet_name,

 pk.quantity as packet_quantity,

 u.name as unit_name,

 pk.unit_quantity,

 mu.acc_final_name as client_name,

 mi.invoice_code,

 mtvc.total_amount_with_vat,

 GROUP_CONCAT(mtvc.calculate_amount) as new_vat_tax,

 GROUP_CONCAT(mt.name) as vat_tax_name,

 GROUP_CONCAT(mtvc.vat_tax_id) as vat_tax_id

 

 FROM mxp_product_purchase mpp

 inner join mxp_product pr on(mpp.product_id = pr.id)

 inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)

 inner join mxp_unit u ON(u.id=pk.unit_id)

 inner join mxp_chart_of_acc_heads mu on(mpp.client_id=mu.chart_o_acc_head_id)

 inner join mxp_invoice mi on(mpp.invoice_id=mi.id)

 inner join mxp_taxvat_cals mtvc on(mtvc.invoice_id = mpp.invoice_id

    AND mtvc.product_id = mpp.product_id

    AND mpp.id = mtvc.sale_purchase_id)

 inner join mxp_taxvats mt on(mtvc.vat_tax_id=mt.id)

    

 

 WHERE mpp.com_group_id=grp_id

 AND mpp.company_id=comp_id

 AND mpp.is_deleted=0 

 group by mtvc.sale_purchase_id

 order by mtvc.sale_purchase_id desc 

limit startedAt,limits$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_role_list_by_group_id` (IN `grp_id` INT(11))  SELECT GROUP_CONCAT(DISTINCT(c.name)) as c_name,r.* FROM mxp_role r join mxp_companies c on(c.id=r.company_id)

where c.group_id=grp_id GROUP BY r.cm_group_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_sales_product` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `startedAt` INT(11), IN `limits` INT(11))  SELECT 

mp.name as product_name,

mpk.name as packet_name,

mpk.quantity as packet_quantity,

munit.name as unit_name,

mpk.unit_quantity,

mu.acc_final_name as client_name, 

msp.quantity, 

msp.price, 

mi.invoice_code, 

mt.transport_name, 

msp.sale_date,

GROUP_CONCAT(mtvc.calculate_amount) as new_vat_tax,

GROUP_CONCAT(mtv.name) as vat_tax_name,

GROUP_CONCAT(mtvc.vat_tax_id) as vat_tax_id

from mxp_sale_products msp 

LEFT JOIN mxp_product mp ON mp.id = msp.product_id

LEFT JOIN mxp_invoice mi ON mi.id = msp.invoice_id

LEFT join mxp_chart_of_acc_heads mu on msp.client_id=mu.chart_o_acc_head_id

LEFT JOIN mxp_transports mt ON mt.invoice_id = msp.invoice_id

LEFT JOIN mxp_packet mpk ON mpk.id = mp.packing_id

LEFT JOIN mxp_unit munit ON munit.id = mpk.unit_id 

LEFT JOIN mxp_taxvat_cals mtvc ON mtvc.invoice_id = msp.invoice_id

LEFT JOIN mxp_taxvats mtv ON mtvc.vat_tax_id=mtv.id

WHERE msp.com_group_id=grp_id

AND msp.company_id=comp_id

AND msp.is_deleted=0

GROUP BY mtvc.sale_purchase_id

limit startedAt,limits$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_stocks` (IN `grp_id` INT, IN `comp_id` INT)  select pp.*, 

mpr.name as product_name, 

pk.name as packet_name, 

pk.quantity as packet_quantity, 

u.name as unit_name, 

pk.unit_quantity, 

pg.name as product_group, 

mu.acc_final_name as client_name



from mxp_product_purchase pp 

inner join mxp_chart_of_acc_heads mu on(pp.client_id=mu.chart_o_acc_head_id)

inner join mxp_product mpr on(pp.product_id = mpr.id) 

inner JOIN mxp_packet pk ON(mpr.packing_id=pk.id) 

inner JOIN mxp_product_group pg on( mpr.product_group_id=pg.id) 

inner join mxp_unit u ON(u.id=pk.unit_id) 



WHERE pp.com_group_id=grp_id 

AND pp.company_id=comp_id 

AND pp.is_active=1 

AND pp.is_deleted=0 

AND pp.stock_status=0 

order by pp.id desc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_stocks_by_productGrpId` (IN `grp_id` INT, IN `comp_id` INT, IN `pro_grp_id` INT)  Select mpr.id as pro_id, 

mpr.name as product_name, 

mpr.product_code, 

pk.name as packet_name, 

pk.quantity as packet_quantity, 

u.name as unit_name, 

pk.unit_quantity, 

pg.name as product_group, 

mu.first_name as client_name, 

mu.address 



from mxp_product_purchase pp 

inner join mxp_users mu on (pp.user_id = mu.user_id) 

inner join mxp_product mpr on(pp.product_id = mpr.id) 

inner JOIN mxp_packet pk ON(mpr.packing_id=pk.id) 

inner JOIN mxp_product_group pg on( mpr.product_group_id=pg.id) 

inner join mxp_unit u ON(u.id=pk.unit_id) 

inner join mxp_stock st ON(st.product_id=mpr.id)




WHERE pp.com_group_id=grp_id

AND pp.company_id=comp_id 

AND mpr.product_group_id=pro_grp_id 

AND pp.is_active=1 

AND pp.is_deleted=0 AND pp.stock_status=1

AND st.product_id IS NOT NULL

GROUP BY mpr.id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_translation` ()  SELECT tr.*,tk.translation_key FROM mxp_translation_keys tk INNER JOIN mxp_translations tr ON(tr.translation_key_id=tk.translation_key_id)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_translation_with_limit` (IN `startedAt` INT(11), IN `limits` INT(11))  SELECT tr.*,tk.translation_key, ml.lan_name FROM mxp_translation_keys tk INNER JOIN

 mxp_translations tr ON(tr.translation_key_id=tk.translation_key_id) 

 INNER JOIN mxp_languages ml ON(ml.lan_code=tr.lan_code)order by tk.translation_key_id desc limit startedAt,limits$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_child_menu_list` (IN `p_parent_menu_id` INT(11), IN `role_id` INT(11), IN `comp_id` INT(11))  if(comp_id !='') then

SELECT m.* FROM mxp_user_role_menu rm inner JOIN mxp_menu m ON(m.menu_id=rm.menu_id) WHERE rm.role_id=role_id AND rm.company_id=comp_id AND m.parent_id=p_parent_menu_id AND m.is_active=1 order by m.order_id ASC;

else

SELECT m.* FROM mxp_user_role_menu rm inner JOIN mxp_menu m ON(m.menu_id=rm.menu_id) WHERE rm.role_id=role_id AND m.parent_id=p_parent_menu_id  order by m.order_id ASC;

end if$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_companies_by_group_id` (IN `grp_id` INT(11))  select * from mxp_companies where group_id=grp_id and is_active = 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_lc_purchase_for_excel_by_date` (IN `fromdate` VARCHAR(20), IN `todate` VARCHAR(20))  SELECT mlp.lc_no as LcNo,mlp.purchase_date as OpenDate,

mlp.lc_amount_taka LCValue,mlp.lc_margin as Margin,

mlp.total_bank_charges as BankCharges,mlp.others_cost as CustomDutyOthersCost,

mlp.total_amount_with_other_cost as TotalCost,mlp.total_paid as PaidAmount,

mlp.due_payment as DuePayment,

GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as ProductDetails,

mu.acc_final_name as ClientDetails



FROM mxp_lc_purchases mlp

inner join mxp_product pr on(mlp.product_id = pr.id)

inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)

inner join mxp_unit un ON(un.id=pk.unit_id)

inner join mxp_chart_of_acc_heads mu on(mlp.client_id=mu.chart_o_acc_head_id)

inner join mxp_invoice mi on(mlp.invoice_id=mi.id)

WHERE mlp.purchase_date>=fromdate AND mlp.purchase_date<=todate

GROUP BY mlp.lc_purchase_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_lc_purchase_product_by_id` (IN `lc_product_id` INT(11))  SELECT mlp.*,

GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,

mca.acc_final_name as client_details,

pr.product_group_id,

pr.id as product_id,

mi.invoice_code,

mca.acc_final_name



FROM mxp_lc_purchases mlp

inner join mxp_product pr on(mlp.product_id = pr.id)

inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)

inner join mxp_unit un ON(un.id=pk.unit_id)

inner join mxp_invoice mi on(mlp.invoice_id=mi.id)

inner join mxp_chart_of_acc_heads mca on(mca.chart_o_acc_head_id=mlp.client_id)



WHERE mlp.lc_purchase_id=lc_product_id

GROUP BY mlp.lc_purchase_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_ledgers_by_acc_head_id` (IN `acc_head_id` INT(11), IN `fromDate` VARCHAR(20), IN `toDate` VARCHAR(20))  SELECT x.journal_posting_id,

x.journal_date,

x.particular,

mca.acc_final_name as ledgerName,

x.transaction_amount_debit as debit, 

x.transaction_amount_credit as credit, 

SUM(y.bal) openingbalance

    

FROM

 ( 

   SELECT *,transaction_amount_debit-transaction_amount_credit as bal FROM mxp_journal_posting  WHERE account_head_id=acc_head_id  AND journal_date >= fromDate and journal_date <= toDate

 ) x



JOIN

 ( 

   SELECT *,transaction_amount_debit-transaction_amount_credit as bal FROM mxp_journal_posting  WHERE account_head_id=acc_head_id 

 ) y

     

   ON y.journal_posting_id <= x.journal_posting_id

   

JOIN mxp_chart_of_acc_heads mca on(mca.chart_o_acc_head_id = x.account_head_id)

    

GROUP BY x.journal_posting_id

ORDER BY x.journal_posting_id ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_ledgers_by_client_id` (IN `client_id` INT(11), IN `fromDate` VARCHAR(20), IN `toDate` VARCHAR(20))  SELECT x.journal_posting_id,

x.journal_date,

x.particular,

mca.acc_final_name as ledgerName,

x.transaction_amount_debit as debit, 

x.transaction_amount_credit as credit, 

SUM(y.bal) openingbalance

    

FROM

 ( 

   SELECT *,transaction_amount_debit-transaction_amount_credit as bal FROM mxp_journal_posting  WHERE client_id=client_id AND ledger_client_id=client_id AND journal_date >= fromDate and journal_date <= toDate

 ) x



JOIN

 ( 

   SELECT *,transaction_amount_debit-transaction_amount_credit as bal FROM mxp_journal_posting  WHERE client_id=client_id AND ledger_client_id=client_id

 ) y

     

   ON y.journal_posting_id <= x.journal_posting_id

JOIN mxp_chart_of_acc_heads mca on(mca.chart_o_acc_head_id = x.client_id)

    

GROUP BY x.journal_posting_id

ORDER BY x.journal_posting_id ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_ledger_details_journal_by_id` (IN `journal_id` INT(11), IN `acc_head_id` INT(11))  if(acc_head_id = -1) then

SELECT mjp.*,

 mu.acc_final_name as client_details, 

 mjp.account_head_id as acc_final_name,

 CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as productDetails

 

FROM mxp_journal_posting mjp 

inner JOIN mxp_chart_of_acc_heads mu ON(mu.chart_o_acc_head_id=mjp.client_id) 

inner JOIN mxp_product pr ON(pr.id=mjp.product_id) 

inner JOIN mxp_packet pk ON(pk.id=pr.packing_id) 

inner JOIN mxp_unit un ON(un.id=pk.unit_id) 



WHERE mjp.journal_posting_id=journal_id;

else

SELECT mjp.*,

 mu.acc_final_name as client_details, 

 mcah.acc_final_name,CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as productDetails

 

FROM mxp_journal_posting mjp

inner JOIN mxp_chart_of_acc_heads mu ON(mu.chart_o_acc_head_id=mjp.client_id) 

inner JOIN mxp_chart_of_acc_heads mcah ON(mcah.chart_o_acc_head_id=mjp.account_head_id) 

inner JOIN mxp_product pr ON(pr.id=mjp.product_id) 

inner JOIN mxp_packet pk ON(pk.id=pr.packing_id) 

inner JOIN mxp_unit un ON(un.id=pk.unit_id) 



WHERE mjp.journal_posting_id=journal_id;

 end if$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_local_purchase_product_by_id` (IN `lc_product_id` INT(11))  SELECT mlp.*,

GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,

GROUP_CONCAT(mu.first_name,'(',mu.address,')') as client_details,

mu.user_id,

pr.product_group_id,

pr.id as product_id,

mi.invoice_code,

mca.acc_final_name



FROM mxp_lc_purchases mlp

inner join mxp_product pr on(mlp.product_id = pr.id)

inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)

inner join mxp_unit un ON(un.id=pk.unit_id)

inner join mxp_users mu on(mlp.client_id=mu.user_id)

inner join mxp_invoice mi on(mlp.invoice_id=mi.id)

inner join mxp_chart_of_acc_heads mca on(mca.chart_o_acc_head_id=mlp.account_head_id)



WHERE mlp.lc_purchase_id=lc_product_id

GROUP BY mlp.lc_purchase_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_order_noBY_type` (IN `type` VARCHAR(120), IN `company_id` INT(11), IN `com_group_id` INT(11))  SELECT order_no FROM mxp_invoice 
WHERE type=type AND company_id=company_id
AND com_group_id=com_group_id
AND order_no is not null
GROUP BY invoice_code$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_permission` (IN `role_id` INT(11), IN `route` VARCHAR(120), IN `comp_id` INT(11))  if(comp_id !='')then

SELECT COUNT(*) as cnt FROM mxp_user_role_menu rm inner JOIN mxp_menu m ON(m.menu_id=rm.menu_id) WHERE m.route_name=route AND rm.role_id=role_id AND rm.company_id=comp_id;

else

SELECT COUNT(*) as cnt FROM mxp_user_role_menu rm inner JOIN mxp_menu m ON(m.menu_id=rm.menu_id) WHERE m.route_name=route AND rm.role_id=role_id ;

end if$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_productDetails_by_id` (IN `product_id` INT(11))  SELECT CONCAT(mp.name,'(',mpk.name,'(',mpk.quantity,')',mu.name,'(',mpk.unit_quantity,'))') as product_details



FROM mxp_product mp

inner join mxp_packet mpk on(mpk.id = mp.packing_id)

inner join mxp_unit mu on(mu.id = mpk.unit_id)



WHERE mp.id = product_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_product_with_packet_and_unit` (IN `grp_id` INT(11), IN `comp_id` INT(11), IN `pro_grp_id` INT(11))  SELECT mpro.id, mpro.name AS pro_name, 

mpro.product_code, 

mpac.name AS pac_name, mpac.quantity, 

munit.name AS unit_name, mpac.unit_quantity 



FROM mxp_product mpro 

LEFT JOIN mxp_packet mpac ON mpac.id = mpro.packing_id 

LEFT JOIN mxp_unit munit ON mpac.unit_id = munit.id 



WHERE mpro.com_group_id=grp_id 

AND mpro.company_id=comp_id 

AND mpro.product_group_id = pro_grp_id

AND mpro.is_active=1 

AND mpro.is_deleted=0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_purchase_productBy_id` (IN `purchase_product_id` INT(11))  SELECT mpp.*,

 pr.name as product_name,

 pk.name as packet_name,

 pk.quantity as packet_quantity,

 u.name as unit_name,

 pk.unit_quantity,

 mu.acc_final_name as client_name,

 mi.invoice_code,

 GROUP_CONCAT(mtvc.calculate_amount) as new_vat_tax,

 GROUP_CONCAT(mt.name) as vat_tax_name,

 GROUP_CONCAT(mtvc.vat_tax_id) as vat_tax_id

 

 FROM mxp_product_purchase mpp

 inner join mxp_product pr on(mpp.product_id = pr.id)

 inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)

 inner join mxp_unit u ON(u.id=pk.unit_id)

 inner join mxp_chart_of_acc_heads mu on(mpp.client_id=mu.chart_o_acc_head_id)

 inner join mxp_invoice mi on(mpp.invoice_id=mi.id)

 inner join mxp_taxvat_cals mtvc on(mtvc.invoice_id = mpp.invoice_id

	AND mtvc.product_id = mpp.product_id

    AND mpp.id = mtvc.sale_purchase_id)

 inner join mxp_taxvats mt on(mtvc.vat_tax_id=mt.id)

    

 

 WHERE mpp.id=purchase_product_id

 group by mtvc.sale_purchase_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_roles_by_company_id` (IN `cmpny_id` INT(11), IN `cm_grp_id` INT(11))  SELECT rl.name as roleName, cm.name as companyName, cm.id as company_id, rl.cm_group_id, rl.is_active FROM mxp_role rl INNER JOIN mxp_companies cm ON(rl.company_id=cm.id) where cm.group_id = `cmpny_id` and rl.cm_group_id = `cm_grp_id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_searched_trans_key` (IN `_key` VARCHAR(255))  SELECT distinct(tk.translation_key),tk.translation_key_id, tk.is_active FROM mxp_translation_keys tk

 inner join mxp_translations tr on(tk.translation_key_id = tr.translation_key_id)

 WHERE tk.translation_key LIKE CONCAT('%', _key , '%')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_translations_by_key_id` (IN `key_id` INT)  select translation_id, translation, lan_code from mxp_translations

 where translation_key_id= `key_id` and is_active = 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_translations_by_locale` (IN `locale_code` VARCHAR(255))  SELECT tr.translation,tk.translation_key FROM mxp_translation_keys tk INNER JOIN mxp_translations tr ON(tr.translation_key_id=tk.translation_key_id)

WHERE tr.lan_code=locale_code$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_translation_by_key_id` (IN `tr_key_id` INT(11))  SELECT tr.translation,tk.translation_key,tk.translation_key_id,tk.is_active,ln.lan_name FROM mxp_translation_keys tk INNER JOIN mxp_translations tr ON(tr.translation_key_id=tk.translation_key_id)

INNER JOIN mxp_languages ln ON(ln.lan_code=tr.lan_code)

WHERE tr.translation_key_id=tr_key_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_trial_balances` (IN `fromDate` VARCHAR(20), IN `toDate` VARCHAR(20))  SELECT ad.head_name_type, ad.accounts_heads_id,

ac.acc_final_name,

jp.account_head_id as chart_of_acc_id,

sub.head_sub_class_name, sub.mxp_acc_head_sub_classes_id,

cls.head_class_name, cls.mxp_acc_classes_id,

sd.sub_head, sd.accounts_sub_heads_id,

sum(jp.transaction_amount_debit-jp.transaction_amount_credit) as balance



FROM mxp_journal_posting jp

JOIN mxp_chart_of_acc_heads ac ON(jp.account_head_id=ac.chart_o_acc_head_id)

JOIN mxp_acc_head_sub_classes sub ON(sub.mxp_acc_head_sub_classes_id=ac.mxp_acc_head_sub_classes_id)

JOIN mxp_acc_classes cls ON(cls.mxp_acc_classes_id=ac.mxp_acc_classes_id)

JOIN mxp_accounts_sub_heads sd ON(sd.accounts_sub_heads_id=ac.accounts_sub_heads_id)

JOIN mxp_accounts_heads ad ON(ad.accounts_heads_id=ac.accounts_heads_id)

where jp.journal_date >= fromDate and jp.journal_date <= toDate

group by jp.account_head_id

order by ad.accounts_heads_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_menu_by_role` (IN `role_id` INT(11), IN `comp_id` INT(11))  if(comp_id !='') then

SELECT m.* FROM mxp_user_role_menu rm inner JOIN mxp_menu m ON(m.menu_id=rm.menu_id) WHERE rm.role_id=role_id AND rm.company_id=comp_id;

else

SELECT m.* FROM mxp_user_role_menu rm inner JOIN mxp_menu m ON(m.menu_id=rm.menu_id) WHERE rm.role_id=role_id;

end if$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2018_01_11_075242_create_languages_table', 1),
(3, '2018_01_12_081050_create_role_table', 1),
(4, '2018_01_12_084141_create_menu_table', 1),
(5, '2018_01_12_122539_add_column_to_mxp_role', 2),
(6, '2018_01_13_100521_create_mxp_users_table', 2),
(7, '2018_01_15_064427_create_mxp_translation_keys', 3),
(8, '2018_01_15_064518_create_mxp_translations', 3),
(9, '2018_01_15_073009_create_mxp_user_role_menu', 4),
(10, '2018_01_15_081551_update_language_table', 5),
(11, '2018_01_15_130417_create_mxp_trans_keys_table', 6),
(12, '2018_01_15_081806_create_mxp_users_table', 7),
(13, '2018_01_15_095153_add_type_column_after_last_name_of_mxp_users', 7),
(14, '2018_01_16_055331_create_mxp_translation_keys_table', 8),
(15, '2018_01_16_060235_create_mxp_translation_keys_table', 9),
(16, '2018_01_16_064618_update_mxp_translation_keys_table', 10),
(17, '2018_01_22_104053_update_mxp_users_table', 11),
(18, '2018_01_26_060729_add_companyId_to_roles_and_role_menus', 11),
(19, '2018_01_25_130557_create_companies_table', 12),
(20, '2018_01_26_054823_drop_company_column_from_mxp_users_table', 12),
(21, '2018_01_26_071103_add_column_to_mxp_user_table', 13),
(22, '2018_01_26_075012_create_store_pro_get_company_by_group_id', 14),
(23, '2018_01_27_110718_update_mxp_role_table', 15),
(24, '2018_01_27_130037_create_store_pro_get_roles_by_company_id', 16),
(25, '2018_01_30_081529_update_mxp_role', 17),
(26, '2018_01_30_093232_create_store_pro_get_all_companies_of_same_name_by_group_id', 17),
(27, '2018_01_30_105605_update_mxp_translations', 17),
(28, '2018_02_06_100944_create_mxp_taxvats_table', 18),
(29, '2018_02_06_103251_create_mxp_taxvat_cals_table', 18),
(30, '2018_02_12_120153_add_address_and_product_code', 19),
(31, '2018_02_13_112216_add_invoice_code_col_to_mxp_invoice_table', 20),
(32, '2018_02_14_104512_add_date_col_mxp_product_purchase', 20),
(33, '2018_02_27_105827_create_mxp_transports_table', 21),
(34, '2018_02_27_115134_add_column_taxvat_cal_table', 22),
(35, '2018_02_27_120919_create_mxp_sale_products_table', 22),
(36, '2018_03_27_070532_create_mxp_lc_purchases_table', 23),
(37, '2018_03_27_075418_add_columnt_to_lc_purchase_table', 24),
(38, '2018_03_27_082127_add_column_to_lc_purchase_table', 25),
(39, '2018_03_27_101811_create_store_procedure_get_all_lc_purchase', 25),
(40, '2018_03_30_125009_add_column_to_mxp_lc_purchase', 26),
(41, '2018_04_02_112616_create_mxp_journals_table', 27),
(42, '2018_04_04_053741_create_mxp_accounts_heads_table', 27),
(43, '2018_04_04_053822_create_mxp_accounts_sub_heads_table', 27),
(44, '2018_04_04_090505_create_get_all_accounts_sub_head_stroe_procedure', 28),
(45, '2018_04_05_063828_create_mxp_acc_classes_table', 29),
(46, '2018_04_05_093858_create_store_procedure_get_all_acc_class', 30),
(47, '2018_04_05_123858_create_mxp_acc_head_sub_classes_table', 31),
(48, '2018_04_05_125024_create_mxp_chart_of_acc_heads_table', 32),
(49, '2018_04_06_060320_create_store_pro_get_all_sub_class_name', 33),
(50, '2018_04_06_070031_create_store_pro_get_all_chart_of_accounts', 34),
(51, '2018_04_17_081158_create store procedure get_product_by_id', 35),
(52, '2018_04_17_125155_create_store_procedure_get_purchase_product_by_id', 35),
(53, '2018_04_19_073033_update_store_procedue_get_all_products', 36),
(54, '2018_04_19_081747_add_acc_head_id_columnb_lc_pruchase_table', 36),
(55, '2018_04_19_083027_update_store_procedue_get_lc_product_by_id', 36),
(56, '2018_04_19_102531_update_all_store_procedure_remove_user_id', 37),
(57, '2018_04_24_054716_create_store_procedure_get_all_stocks_by_productGroupId', 37),
(58, '2018_04_24_084541_update_mxpVatTaxCal_table_add_column', 38),
(59, '2018_04_24_094522_update_store_pro_get_all_purchase_product', 38),
(60, '2018_04_24_102557_update_all_store_pro_in_desc_order', 38),
(61, '2018_04_24_110808_update_store_pro_type_in_mxp_taxvat_cals', 39),
(62, '2018_04_24_120245_add_col_mxp_journal_table', 39),
(63, '2018_04_25_112147_add_col_to_mxp_product_purchase', 40),
(64, '2018_04_26_093238_add_randomGrpId_col_to_purchase', 41),
(65, '2018_04_26_105818_add_col_to_product_purchase', 41),
(66, '2018_05_05_085736_change_col_prop_mxp_journal_posting', 42),
(67, '2018_05_14_090051_create_stor_procedure_get_ledgers_by_chart_of_acc', 43),
(68, '2018_05_14_092850_create_stor_procedure_get_ledger_details_journal_by_id', 43),
(69, '2018_05_14_115321_create_store_procedure_get_ledgers_details_client_id', 44),
(70, '2018_05_15_104715_update_get_ledgers_by_chart_of_acc_store_procedure', 44),
(71, '2018_05_24_074457_add_col_mxp_stock', 45),
(72, '2018_07_11_091501_update_get_all_purchase_product_procedure', 46),
(73, '2018_07_11_125710_update_get_all_product_of_stock_pro', 47),
(74, '2018_07_12_060904_update_get_purchase_productBy_id_pro', 48),
(75, '2018_07_12_074644_update_get_ledger_By_client_id_pro', 49),
(76, '2018_07_12_114844_create_stro_pro_get_all_lc_pro_in_stock', 50),
(77, '2018_07_12_115545_update_get_all_stocks_procedure', 50),
(78, '2018_07_13_063730_update_get_all_sale_product_pro', 51),
(79, '2018_07_13_114328_update_get_lc_purchase_for_excel_by_date_procedure', 52),
(80, '2018_07_13_124712_update_get_ledger_details_journal_by_id_procedure', 53);

-- --------------------------------------------------------

--
-- Table structure for table `mxp_accounts_heads`
--

CREATE TABLE `mxp_accounts_heads` (
  `accounts_heads_id` int(11) UNSIGNED NOT NULL,
  `head_name_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_accounts_heads`
--

INSERT INTO `mxp_accounts_heads` (`accounts_heads_id`, `head_name_type`, `account_code`, `company_id`, `group_id`, `user_id`, `is_deleted`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Assets', '1000', 10, 1, 1, 0, 1, '2018-04-25 03:37:20', '2018-04-25 03:37:20'),
(2, 'Liabilities', '2000', 10, 1, 1, 0, 1, '2018-04-25 05:19:06', '2018-04-25 05:19:06'),
(3, 'Revenue', '3000', 10, 1, 1, 0, 1, '2018-04-25 05:45:00', '2018-05-24 22:40:05'),
(4, 'Owners Equity', '4000', 10, 1, 1, 0, 1, '2018-04-25 06:02:38', '2018-05-24 22:40:12'),
(5, 'Expenses', '5000', 10, 1, 1, 0, 1, '2018-04-25 06:07:13', '2018-05-24 22:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_accounts_sub_heads`
--

CREATE TABLE `mxp_accounts_sub_heads` (
  `accounts_sub_heads_id` int(10) UNSIGNED NOT NULL,
  `accounts_heads_id` int(11) NOT NULL,
  `sub_head` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_accounts_sub_heads`
--

INSERT INTO `mxp_accounts_sub_heads` (`accounts_sub_heads_id`, `accounts_heads_id`, `sub_head`, `company_id`, `group_id`, `user_id`, `is_deleted`, `is_active`, `created_at`, `updated_at`) VALUES
(51, 1, 'Current Assets', 10, 1, 1, 0, 1, '2018-05-24 22:40:55', '2018-05-24 23:09:08'),
(52, 1, 'Non Current Assets', 10, 1, 1, 0, 1, '2018-05-24 22:41:08', '2018-05-24 23:09:03'),
(53, 2, 'Current Liabilities', 10, 1, 1, 0, 1, '2018-05-24 23:09:54', '2018-05-24 23:09:54'),
(54, 2, 'Long Term Liabilities', 10, 1, 1, 0, 1, '2018-05-24 23:10:08', '2018-05-24 23:10:08'),
(55, 5, 'Fixed Expenses', 10, 1, 1, 0, 1, '2018-05-24 23:10:34', '2018-05-24 23:10:34'),
(56, 5, 'Odinary Expenses', 10, 1, 1, 0, 1, '2018-05-24 23:10:50', '2018-05-24 23:10:50'),
(57, 5, 'Variable expenses', 10, 1, 1, 0, 1, '2018-05-24 23:11:06', '2018-05-24 23:11:06'),
(58, 3, 'Ordinary Revenue', 10, 1, 1, 0, 1, '2018-05-24 23:11:52', '2018-05-24 23:11:52'),
(59, 4, 'Shareholders\' Equity', 10, 1, 1, 0, 1, '2018-05-24 23:14:39', '2018-05-24 23:14:39'),
(60, 5, 'Direct Expenses', 10, 1, 1, 0, 1, '2018-05-24 23:19:09', '2018-05-24 23:19:09');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_acc_classes`
--

CREATE TABLE `mxp_acc_classes` (
  `mxp_acc_classes_id` int(10) UNSIGNED NOT NULL,
  `head_class_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accounts_heads_id` int(10) UNSIGNED NOT NULL,
  `accounts_sub_heads_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_acc_classes`
--

INSERT INTO `mxp_acc_classes` (`mxp_acc_classes_id`, `head_class_name`, `accounts_heads_id`, `accounts_sub_heads_id`, `company_id`, `group_id`, `user_id`, `is_deleted`, `is_active`, `created_at`, `updated_at`) VALUES
(77, 'Cash & cash equivalents', 1, 51, 10, 1, 1, 0, 1, '2018-05-24 22:41:38', '2018-05-24 23:12:56'),
(78, 'Bank', 1, 51, 10, 1, 1, 0, 1, '2018-05-24 22:41:48', '2018-05-24 22:41:48'),
(79, 'Inventory', 1, 51, 10, 1, 1, 0, 1, '2018-05-24 22:42:20', '2018-05-24 22:42:20'),
(80, 'Cost of Revenue', 5, 60, 10, 1, 1, 0, 1, '2018-05-24 23:19:37', '2018-05-24 23:19:37'),
(81, 'Bank', 2, 53, 10, 1, 1, 0, 1, '2018-05-24 23:23:49', '2018-05-24 23:23:49'),
(82, 'Sales Revenue', 3, 58, 10, 1, 1, 0, 1, '2018-05-24 23:26:28', '2018-05-24 23:26:28'),
(83, 'Party-CA', 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:27:57', '2018-05-24 23:27:57'),
(84, 'LC', 2, 53, 10, 1, 1, 0, 1, '2018-06-05 02:29:55', '2018-06-05 02:29:55'),
(85, 'L/C-Loan', 2, 53, 10, 1, 1, 0, 1, '2018-06-05 02:30:18', '2018-06-05 02:30:18'),
(86, 'Company', 2, 53, 10, 1, 1, 0, 1, '2018-07-13 04:11:33', '2018-07-13 04:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_acc_head_sub_classes`
--

CREATE TABLE `mxp_acc_head_sub_classes` (
  `mxp_acc_head_sub_classes_id` int(10) UNSIGNED NOT NULL,
  `head_sub_class_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mxp_acc_classes_id` int(10) UNSIGNED NOT NULL,
  `accounts_heads_id` int(10) UNSIGNED NOT NULL,
  `accounts_sub_heads_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_acc_head_sub_classes`
--

INSERT INTO `mxp_acc_head_sub_classes` (`mxp_acc_head_sub_classes_id`, `head_sub_class_name`, `mxp_acc_classes_id`, `accounts_heads_id`, `accounts_sub_heads_id`, `company_id`, `group_id`, `user_id`, `is_deleted`, `is_active`, `created_at`, `updated_at`) VALUES
(82, 'Cash', 77, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:16:41', '2018-05-24 23:16:41'),
(83, 'Cost of Goods Sold', 80, 5, 60, 10, 1, 1, 0, 1, '2018-05-24 23:20:05', '2018-05-24 23:20:05'),
(84, 'Fixed Deposits', 78, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:20:49', '2018-05-24 23:20:49'),
(85, 'Purchase account', 78, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:21:41', '2018-05-24 23:21:41'),
(86, 'Lc opening Bank Account', 78, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:23:12', '2018-05-24 23:23:12'),
(87, 'Sales', 82, 3, 58, 10, 1, 1, 0, 1, '2018-05-24 23:26:45', '2018-05-24 23:26:45'),
(88, 'Ordinary Loan Account', 81, 2, 53, 10, 1, 1, 0, 1, '2018-05-24 23:28:49', '2018-05-24 23:28:49'),
(89, 'LC-purpose Loan Account', 81, 2, 53, 10, 1, 1, 0, 1, '2018-05-24 23:29:43', '2018-05-24 23:29:43'),
(90, 'Purchase Client', 83, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:42:52', '2018-05-24 23:42:52'),
(91, 'Bank', 84, 2, 53, 10, 1, 1, 0, 1, '2018-06-05 02:30:52', '2018-06-05 02:30:52'),
(92, 'Bank', 85, 2, 53, 10, 1, 1, 0, 1, '2018-06-05 02:31:07', '2018-06-05 02:31:07'),
(93, 'Inventory/stock', 79, 1, 51, 10, 1, 1, 0, 1, '2018-06-07 01:28:05', '2018-06-07 01:28:05'),
(94, 'Sales-party', 83, 1, 51, 10, 1, 1, 0, 1, '2018-06-07 23:26:24', '2018-06-07 23:26:24'),
(95, 'Purchase Party', 83, 1, 51, 10, 1, 1, 0, 1, '2018-07-07 04:56:40', '2018-07-07 04:56:40'),
(96, 'Loan', 86, 2, 53, 10, 1, 1, 0, 1, '2018-07-13 04:11:52', '2018-07-13 04:11:52'),
(97, 'Sale', 77, 1, 51, 10, 1, 1, 0, 1, '2018-07-13 04:40:12', '2018-07-13 04:40:12');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_chart_of_acc_heads`
--

CREATE TABLE `mxp_chart_of_acc_heads` (
  `chart_o_acc_head_id` int(10) UNSIGNED NOT NULL,
  `acc_final_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mxp_acc_head_sub_classes_id` int(10) UNSIGNED NOT NULL,
  `mxp_acc_classes_id` int(10) UNSIGNED NOT NULL,
  `accounts_heads_id` int(10) UNSIGNED NOT NULL,
  `accounts_sub_heads_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_chart_of_acc_heads`
--

INSERT INTO `mxp_chart_of_acc_heads` (`chart_o_acc_head_id`, `acc_final_name`, `mxp_acc_head_sub_classes_id`, `mxp_acc_classes_id`, `accounts_heads_id`, `accounts_sub_heads_id`, `company_id`, `group_id`, `user_id`, `is_deleted`, `is_active`, `created_at`, `updated_at`) VALUES
(87, 'Cash/Cash In Hand', 82, 77, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:30:36', '2018-05-24 23:30:36'),
(88, 'Brac Bank-AC-010101015289', 86, 78, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:31:30', '2018-05-24 23:31:30'),
(89, 'Beximco-Dhaka-Br', 90, 83, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:43:40', '2018-05-24 23:44:04'),
(90, 'Square', 90, 83, 1, 51, 10, 1, 1, 0, 1, '2018-05-24 23:48:43', '2018-05-24 23:48:43'),
(91, 'Dutch-Bangla(ac-001010110111)', 91, 84, 2, 53, 10, 1, 1, 0, 1, '2018-06-05 02:32:02', '2018-06-05 02:32:02'),
(92, 'Dutch-Bangla(AC:220222020120)', 92, 85, 2, 53, 10, 1, 1, 0, 1, '2018-06-05 02:32:33', '2018-06-05 02:32:33'),
(93, 'Inventory/stock', 93, 79, 1, 51, 10, 1, 1, 0, 1, '2018-06-07 01:28:23', '2018-06-07 01:28:23'),
(94, 'Incepta Pharmaceuticals Ltd', 94, 83, 1, 51, 10, 1, 53, 0, 1, '2018-06-07 23:26:50', '2018-08-04 01:06:05'),
(95, 'Kashem dry-sel-02', 94, 83, 1, 51, 10, 1, 1, 0, 1, '2018-06-07 23:27:20', '2018-06-07 23:27:20'),
(96, 'Pran-group(001)', 95, 83, 1, 51, 10, 1, 1, 0, 1, '2018-07-07 04:57:26', '2018-07-07 04:57:26'),
(97, 'Cost of goods sold', 93, 79, 1, 51, 10, 1, 1, 0, 1, '2018-07-12 23:20:26', '2018-07-12 23:20:26'),
(98, 'Company Loan', 96, 86, 2, 53, 10, 1, 1, 0, 1, '2018-07-13 04:12:11', '2018-07-13 04:12:11'),
(99, 'Chart of Sale', 97, 77, 1, 51, 10, 1, 1, 0, 1, '2018-07-13 04:40:36', '2018-07-13 04:40:36'),
(100, 'Lc purchase party-1', 95, 83, 1, 51, 10, 1, 1, 0, 1, '2018-07-30 06:37:38', '2018-07-30 06:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_companies`
--

CREATE TABLE `mxp_companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_companies`
--

INSERT INTO `mxp_companies` (`id`, `group_id`, `name`, `description`, `address`, `phone`, `is_active`, `created_at`, `updated_at`) VALUES
(10, 1, 'Chemax International', 'Mirpur 11', 'Majibur Rahman', '01673197093', 1, '2018-01-29 06:39:19', '2018-07-16 23:24:42'),
(11, 2, 'Company-B', 'dsddsd', 'sddsd', '0167319709377', 1, '2018-01-29 06:39:31', '2018-01-29 06:39:31'),
(13, 38, 'sumit power-23-A', 'fhfhdhf', '445fdfdf', '01674898148', 1, '2018-01-31 02:57:49', '2018-01-31 02:57:49'),
(14, 38, 'sumit power-23-B', 'fhfhdhf', '445fdfdf', '01674898148', 1, '2018-01-31 02:57:58', '2018-01-31 02:57:58'),
(15, 42, 'New Company', 'Descrip', 'dhaka', '1234567890', 1, '2018-02-09 02:06:45', '2018-02-09 02:06:45'),
(16, 42, 'New Company 2', 'description', 'Bangladesh', '1234567', 1, '2018-02-09 02:09:04', '2018-02-09 02:09:04');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_invoice`
--

CREATE TABLE `mxp_invoice` (
  `id` int(11) NOT NULL,
  `invoice_code` varchar(255) NOT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `com_group_id` int(11) DEFAULT NULL,
  `type` varchar(120) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mxp_invoice`
--

INSERT INTO `mxp_invoice` (`id`, `invoice_code`, `order_no`, `client_id`, `company_id`, `com_group_id`, `type`, `user_id`, `is_deleted`, `created_at`, `updated_at`) VALUES
(164, 'BEX180861', NULL, 96, 10, 1, 'purchase', 53, 0, '2018-08-18 07:37:24', '2018-08-18 07:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_journal_posting`
--

CREATE TABLE `mxp_journal_posting` (
  `journal_posting_id` int(10) UNSIGNED NOT NULL,
  `transaction_type_id` int(11) DEFAULT NULL,
  `account_head_id` int(11) DEFAULT NULL,
  `sales_man_id` int(11) DEFAULT NULL,
  `particular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_amount_debit` double(12,2) NOT NULL,
  `transaction_amount_credit` double(12,2) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `journal_date` date NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cf_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posting_group_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ledger_client_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_journal_posting`
--

INSERT INTO `mxp_journal_posting` (`journal_posting_id`, `transaction_type_id`, `account_head_id`, `sales_man_id`, `particular`, `description`, `transaction_amount_debit`, `transaction_amount_credit`, `client_id`, `product_id`, `bank_id`, `branch_id`, `journal_date`, `reference_id`, `reference_no`, `cf_code`, `posting_group_id`, `company_id`, `group_id`, `user_id`, `is_deleted`, `is_active`, `created_at`, `updated_at`, `ledger_client_id`) VALUES
(793, 4, 91, NULL, 'Dutch-Bangla(ac-001010110111)(L/C Bank)', 'L/C Purchase', 880.00, 0.00, 96, 7, 0, 0, '2018-08-18', 1, '1', '123', 534554504, 10, 1, 0, 0, 1, '2018-08-18 07:37:24', '2018-08-18 07:37:24', 0),
(794, 4, 87, NULL, 'Cash in hand ', 'L/C Purchase', 0.00, 880.00, 96, 7, 0, 0, '2018-08-18', 1, '1', '123', 534554504, 10, 1, 0, 0, 1, '2018-08-18 07:37:24', '2018-08-18 07:37:24', 0),
(795, 4, 93, NULL, 'lc/polyglykol 6000(Drum(1)kg(100))', 'L/C Purchase', 88111.01, 0.00, 96, 7, 0, 0, '2018-08-18', 1, '1', '123', 534554504, 10, 1, 0, 0, 1, '2018-08-18 07:37:24', '2018-08-18 07:37:24', 96),
(796, 4, 91, NULL, 'Dutch-Bangla(ac-001010110111)(L/C Bank)', 'L/C Purchase', 0.00, 88111.01, 96, 7, 0, 0, '2018-08-18', 1, '1', '123', 534554504, 10, 1, 0, 0, 1, '2018-08-18 07:37:24', '2018-08-18 07:37:24', 96),
(797, 4, 92, NULL, 'Dutch-Bangla(AC:220222020120)', 'L/C Purchase', 0.00, 87120.00, 96, 7, 0, 0, '2018-08-18', 1, '1', '123', 534554504, 10, 1, 0, 0, 1, '2018-08-18 07:37:24', '2018-08-18 07:37:24', 0),
(798, 4, 91, NULL, 'Dutch-Bangla(ac-001010110111)(L/C Bank)', 'L/C Purchase', 87120.00, 0.00, 96, 7, 0, 0, '2018-08-18', 1, '1', '123', 534554504, 10, 1, 0, 0, 1, '2018-08-18 07:37:24', '2018-08-18 07:37:24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mxp_languages`
--

CREATE TABLE `mxp_languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `lan_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lan_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_languages`
--

INSERT INTO `mxp_languages` (`id`, `lan_name`, `lan_code`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'English', 'en', '2018-03-06 00:10:25', '2018-03-06 00:10:25', 1),
(2, 'বাংলা', 'bn', '2018-03-06 00:10:57', '2018-03-06 00:10:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mxp_lc_purchases`
--

CREATE TABLE `mxp_lc_purchases` (
  `lc_purchase_id` int(10) UNSIGNED NOT NULL,
  `lc_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lc_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_swift_charge` int(11) DEFAULT NULL,
  `vat` float DEFAULT NULL,
  `bank_commission` decimal(11,0) DEFAULT NULL,
  `apk_form_charge` float DEFAULT NULL,
  `total_bank_charges` float DEFAULT NULL,
  `lc_margin` float DEFAULT NULL,
  `lc_amount_usd` float DEFAULT NULL,
  `lc_amount_taka` float DEFAULT NULL,
  `due_payment` float DEFAULT NULL,
  `others_cost` float DEFAULT NULL,
  `total_amount_with_other_cost` float DEFAULT NULL,
  `total_amount_without_other_cost` float DEFAULT NULL,
  `total_paid` float DEFAULT NULL COMMENT 'Taka',
  `dollar_rate` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `com_group_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quantity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonus` float DEFAULT NULL,
  `stock_status` tinyint(4) DEFAULT NULL,
  `purchase_date` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jouranl_posting_rand_grp_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) DEFAULT NULL,
  `lc_status` tinyint(4) DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_orign` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lc_margin_percent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_head_id` int(11) NOT NULL,
  `lc_loan_account_head_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mxp_lc_purchases`
--

INSERT INTO `mxp_lc_purchases` (`lc_purchase_id`, `lc_no`, `lc_type`, `bank_swift_charge`, `vat`, `bank_commission`, `apk_form_charge`, `total_bank_charges`, `lc_margin`, `lc_amount_usd`, `lc_amount_taka`, `due_payment`, `others_cost`, `total_amount_with_other_cost`, `total_amount_without_other_cost`, `total_paid`, `dollar_rate`, `created_at`, `updated_at`, `product_id`, `invoice_id`, `client_id`, `com_group_id`, `company_id`, `user_id`, `quantity`, `price`, `bonus`, `stock_status`, `purchase_date`, `jouranl_posting_rand_grp_id`, `is_deleted`, `lc_status`, `bank_name`, `country_orign`, `lc_margin_percent`, `account_head_id`, `lc_loan_account_head_id`) VALUES
(22, '122', 'Payment at sight', 100, 1, '1', 10, 111.01, 880, 1100, 88000, 87120, 0, 88111, 88111, 991.01, 80, '2018-08-18 07:37:24', '2018-08-18 07:37:24', 7, 164, 96, 1, 10, 53, '220', NULL, NULL, 0, '2018-08-18', 534554504, 0, 0, NULL, 'BD', '1', 91, 92);

-- --------------------------------------------------------

--
-- Table structure for table `mxp_menu`
--

CREATE TABLE `mxp_menu` (
  `menu_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT '0',
  `is_active` int(11) NOT NULL,
  `order_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_menu`
--

INSERT INTO `mxp_menu` (`menu_id`, `name`, `route_name`, `description`, `parent_id`, `is_active`, `order_id`, `created_at`, `updated_at`) VALUES
(3, 'LANGUAGE', 'language-chooser_view', 'Change Language', 0, 1, 0, NULL, NULL),
(4, 'DASHBOARD', 'dashboard_view', 'Super admin Dashboard', 0, 1, 1, NULL, NULL),
(5, 'SETTINGS', '', 'Settings', 0, 1, 2, NULL, NULL),
(6, 'ROLE', '', 'Role Management ', 0, 0, 2, NULL, NULL),
(7, 'ADD ROLE ACTION', 'add_role_action', 'Add new Role', 0, 1, 0, NULL, NULL),
(8, 'Role List', 'role_list_view', 'Role List and manage option', 6, 1, 2, NULL, NULL),
(9, 'ROLE UPDATE FORM', 'role_update_view', 'Show role update Form', 0, 1, 2, NULL, NULL),
(10, 'ROLE DELETE ACTION', 'role_delete_action', 'Delete role', 0, 1, 0, NULL, NULL),
(11, 'UPDATE ROLE ACTION', 'role_update_action', 'Update Role', 0, 1, 0, NULL, NULL),
(12, 'Role Permission ', 'role_permission_view', 'Set Route Access to Role', 6, 1, 3, NULL, NULL),
(13, 'PERMISSION ROLE ACTION', 'role_permission_action', 'Set Route Access to Role', 0, 1, 0, NULL, NULL),
(16, 'ROLE PERMISSION FORM', 'role_permission_update_view', '0', 0, 1, 0, NULL, NULL),
(18, 'Create User', 'create_user_view', 'User Create Form', 5, 1, 1, NULL, NULL),
(19, 'CREATE USER ACTION', 'create_user_action', '', 0, 1, 0, NULL, NULL),
(20, 'User List', 'user_list_view', '', 5, 1, 2, NULL, NULL),
(21, 'USER UPDATE FORM', 'company_user_update_view', '', 0, 1, 0, NULL, NULL),
(22, 'UPDATE USER ACTION', 'company_user_update_action', '', 0, 1, 0, NULL, NULL),
(23, 'DELETE USER ACTION', 'company_user_delete_action', '', 0, 1, 0, NULL, NULL),
(24, 'Manage Langulage', 'manage_language', 'language add and view', 3, 1, 0, NULL, NULL),
(25, 'ADD LANGUAGE ACTION', 'create_locale_action', 'add language', 0, 1, 0, NULL, NULL),
(26, 'UPDATE LOCALE ACTION', 'update_locale_action', 'update language', 0, 1, 0, NULL, NULL),
(27, 'Manage Translation', 'manage_translation', 'manage transaltion', 3, 1, 2, NULL, NULL),
(28, 'CREATE TRANSLATION ACTION', 'create_translation_action', 'create translation', 0, 1, 0, NULL, NULL),
(29, 'UPDATE TRANSLATION ACTION', 'update_translation_action', 'update translation', 0, 1, 0, NULL, NULL),
(30, 'POST UPDATE TRANSLATION ACTION', 'update_translation_key_action', 'post update translaion', 0, 1, 0, NULL, NULL),
(31, 'DELETE TRANSLATION ACTION', 'delete_translation_action', 'delete translation', 0, 1, 0, NULL, NULL),
(32, 'Upload Language File', 'update_language', 'upload language file', 3, 1, 3, NULL, NULL),
(33, 'USER', '', 'User Management', 0, 1, 1, NULL, NULL),
(34, 'Add New Role', 'add_role_view', 'New role adding form', 6, 1, 1, NULL, NULL),
(35, 'Open Company Acc', 'create_company_acc_view', 'Company Account Opening Form', 5, 1, 3, NULL, NULL),
(36, 'OPEN COMPANY ACCOUNT', 'create_company_acc_action', 'Company Acc opening Action', 5, 1, 2, NULL, NULL),
(37, 'Company List', 'company_list_view', 'Company List View', 5, 1, 4, NULL, NULL),
(38, 'PRODUCT', '', 'Product management', 0, 1, 0, NULL, NULL),
(39, 'Unit', 'unit_list_view', 'Product List view form', 38, 1, 1, NULL, NULL),
(40, 'Add Unit Form', 'add_unit_view', 'Create Product view', 0, 1, 1, NULL, NULL),
(41, 'Add Unit Action', 'add_unit_action', 'Add Product Action', 0, 1, 1, NULL, NULL),
(42, 'Product Group', 'group_product_list_view', 'Product group List', 38, 1, 1, NULL, NULL),
(43, ' Add Product Group Form', 'add_product_group_view', 'Add product group', 0, 1, 1, NULL, NULL),
(44, 'Add Product Group Action', 'add_product_group_action', 'Add product group action', 0, 1, 1, NULL, NULL),
(52, 'Product Entry', 'entry_product_list_view', 'This is product entry list view', 38, 1, 3, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(53, 'Add Product Entry Form', 'add_product_entry_view', 'This is product entry view form', 0, 1, 0, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(54, 'Add Product Action', 'add_product_action', 'this is product entry action', 0, 1, 0, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(55, 'Product Packing', 'packet_list_view', '', 38, 1, 3, NULL, NULL),
(56, 'Add Packet', 'add_packet_view', '', 0, 1, 0, NULL, NULL),
(57, 'ADD PACKET ACTION', 'add_packet_action', '', 0, 1, 0, NULL, NULL),
(58, 'DELETE PACKET', 'delete_packet_action', '', 0, 1, 0, NULL, NULL),
(59, 'Uudate Packet', 'update_packet_view', '', 0, 1, 0, NULL, NULL),
(60, 'UPDATE PACKET', 'update_packet_action', '', 0, 1, 0, NULL, NULL),
(61, 'Update Unit', 'edit_unit_view', '', 0, 1, 0, NULL, NULL),
(62, 'UPDATE UNIT ACTION', 'edit_unit_action', '', 0, 1, 0, NULL, NULL),
(63, 'DELETE UNIT ACTION', 'delete_unit', '', 0, 1, 0, NULL, NULL),
(64, 'Update Product Group', 'edit_productGroup_view', '', 0, 1, 0, NULL, NULL),
(65, 'UPDATE PRODUCT ACTION', 'edit_productGroup_action', '', 0, 1, 0, NULL, NULL),
(66, 'DELETE PRODUCT ACTION', 'delet_productGroup', '', 0, 1, 0, NULL, NULL),
(67, 'Add Client', 'client_com_add_view', '', 0, 1, 0, NULL, NULL),
(68, 'CLIENT ADD', 'client_com_add_action', '', 0, 1, 0, NULL, NULL),
(69, 'Client Update', 'client_com_update_view', '', 0, 1, 0, NULL, NULL),
(70, 'CLIENT UPDATE ACTION', 'client_com_update_action', '', 0, 1, 0, NULL, NULL),
(71, 'CLIENT DELETE ACTION', 'client_com_delete_action', '', 0, 1, 0, NULL, NULL),
(72, 'Client List', 'client_com_list_view', 'Show Client List', 5, 0, 5, NULL, NULL),
(73, 'Local Purchase', 'product_purchase_view', 'Product Purchase Tabulation Sheet View', 38, 1, 5, NULL, NULL),
(74, 'PRODUCT ENTRY DELETE', 'delete_product_entry_action', 'Product entry delete', 0, 1, 0, NULL, NULL),
(75, 'Product entry update form', 'edit_product_entry_view', 'Product entry update form', 0, 1, 0, NULL, NULL),
(76, 'PRODUCT ENTRY UPDATE ACTION', 'edit_product_action', 'Product entry update action', 0, 1, 0, NULL, NULL),
(77, 'Store', 'store_list_view', 'Store entry delete', 83, 1, 1, NULL, NULL),
(78, 'Store Add View', 'add_store_view', 'Store entry update form', 0, 1, 0, NULL, NULL),
(79, 'STORE ADD ACTION', 'add_store_action', 'Store entry update action', 0, 1, 0, NULL, NULL),
(80, 'Store Edit View', 'edit_store_view', 'Store entry delete', 0, 1, 0, NULL, NULL),
(81, 'STORE EDIT ACTION', 'edit_store_action', 'Store entry update form', 0, 1, 0, NULL, NULL),
(82, 'STORE DELETE ACTION', 'delete_store_action', 'Store entry update action', 0, 1, 0, NULL, NULL),
(83, 'STOCK MANAGEMENT', '', 'Stock Management', 0, 1, 0, NULL, NULL),
(84, 'Stock', 'stock_view', 'Stocks', 83, 1, 1, NULL, NULL),
(88, 'Vat Tax List', 'list_vat_tax_view', '', 38, 1, 6, NULL, NULL),
(89, 'Add Vat Tax', 'add_vat_tax_view', '', 0, 1, 0, NULL, NULL),
(90, 'ADD VAT TAX ACTIOIN', 'add_vat_tax_action', '', 0, 1, 0, NULL, NULL),
(91, 'DELETE VAT TAX', 'delete_vat_tax_action', '', 0, 1, 0, NULL, NULL),
(92, 'Purchase List', 'product_purchase_list_view', '', 38, 1, 5, NULL, NULL),
(93, 'SAVE STOCK ACTIOIN', 'save_stock_action', '', 0, 1, 0, NULL, NULL),
(94, 'Update Stocks Form', 'update_stocks_view', '', 0, 1, 0, NULL, NULL),
(95, 'UPDATE STOCKS ACTION', 'update_stock_action', '', 38, 1, 5, NULL, NULL),
(96, 'Sale List', 'product_sale_list_view', '', 38, 1, 8, NULL, NULL),
(97, 'Save Sale', 'product_sale_view', '', 38, 1, 9, NULL, NULL),
(98, '', 'product_sale_add_action', '', 38, 1, 10, NULL, NULL),
(99, 'Inventory Report', 'inventory_report_view', '', 38, 1, 11, NULL, NULL),
(100, 'LC Purchase', 'lc_purchase_view', '', 38, 1, 12, '2018-03-20 18:00:00', '2018-03-20 18:00:00'),
(101, 'L/C Purchase list', 'lc_purchase_list_view', 'L/C Purchases', 38, 1, 13, NULL, NULL),
(102, '', 'lc_purchase_table_action', 'lc_purchase_table data insertion', 38, 1, 0, NULL, NULL),
(103, 'Journal', '', '', 0, 1, 7, NULL, NULL),
(104, 'Journal', 'journal_posting_view', '', 103, 1, 1, NULL, NULL),
(105, 'Lc Purchase products export to excel', 'lc_p_export_action', 'Lc Purchase products export to excel', 0, 1, 0, NULL, NULL),
(106, 'Accounts head', '', 'Accounts Head', 0, 1, 0, NULL, NULL),
(107, 'Head name/type', 'accounts_head_name_view', 'Head name/type', 106, 1, 0, NULL, NULL),
(108, 'Accounts sub head', 'accounts_sub_head_name_view', 'Accounts sub Head', 106, 1, 1, NULL, NULL),
(109, 'Add Journal', 'journal_posting_form_view', 'Journal Posting Form View', 0, 1, 1, NULL, NULL),
(110, 'Journal Posting Form Action', 'journal_posting_form_action', 'Journal Posting Form Data upload', 0, 1, 0, NULL, NULL),
(111, 'Add Accounts name', 'add_accounts_head_view', 'Add Accounts name View', 0, 1, 1, NULL, NULL),
(112, 'Add Accounts Name Action', 'add_account_head_action', 'Add Accounts Name Action View', 0, 1, 1, NULL, NULL),
(113, 'Edit Accounts Name View', 'edit_accounts_head_view', 'Edit Accounts Name View', 0, 1, 1, NULL, NULL),
(114, 'Edit Accounts Name Action', 'edit_account_head_action', 'Edit Accounts Name Action', 0, 1, 1, NULL, NULL),
(115, 'Delete Accounts Name Action', 'delete_accounts_head_action', 'Delete Accounts Name Action', 0, 1, 1, NULL, NULL),
(116, 'Add Sub Accounts Name View', 'add_sub_accounts_head_view', 'Add Sub Accounts Name View', 0, 1, 1, NULL, NULL),
(117, 'Add Sub Accounts Name Action', 'add_sub_account_head_action', 'Add Sub Accounts Name Action', 0, 1, 1, NULL, NULL),
(118, 'Edit Sub Accounts Name View', 'edit_sub_accounts_head_view', 'Edit Sub Accounts Name View', 0, 1, 1, NULL, NULL),
(119, 'Edit Sub Accounts Name Action', 'edit_sub_account_head_action', 'Edit Sub Accounts Name Action', 0, 1, 1, NULL, NULL),
(120, 'Delete Sub Accounts Name Action', 'delete_sub_accounts_head_action', 'Delete Sub Accounts Name Action', 0, 1, 0, NULL, NULL),
(121, 'Account Class', 'acc_class_index', 'Account Class View', 106, 1, 2, NULL, NULL),
(122, 'Account Head Class Create View', 'acc_class_create_view', 'Account Head Class Create View', 0, 1, 0, NULL, NULL),
(123, 'Account Head Class Create Action', 'acc_class_create_action', 'Account Account Head Class Create Action', 0, 1, 0, NULL, NULL),
(124, 'Account Head Class Edit View', 'acc_class_update_view', 'Account Head Class Edit View', 0, 1, 0, NULL, NULL),
(125, 'Account Head Class Edit Action', 'acc_class_update_action', 'Account Head Class Edit Action', 0, 1, 0, NULL, NULL),
(126, 'Account Head Class Delete Action', 'acc_class_delete_action', 'Account Head Class Delete Action', 0, 1, 0, NULL, NULL),
(127, 'Account Sub Class List', 'acc_sub_class_index', 'Account Sub Class List', 106, 1, 3, NULL, NULL),
(128, 'Account Sub Class Create View', 'acc_sub_class_create_view', 'Account Sub Class Create View', 0, 1, 0, NULL, NULL),
(129, 'Account Sub Class Create Action', 'acc_sub_class_create_action', 'Account Sub Class Create Action', 0, 1, 0, NULL, NULL),
(130, 'Account Sub Class Update View', 'acc_sub_class_update_view', 'Account Sub Class Update View', 0, 1, 0, NULL, NULL),
(131, 'Account Sub Class Update Action', 'acc_sub_class_update_action', 'Account Sub Class Update Action', 0, 1, 0, NULL, NULL),
(132, 'Account Sub Class Delete Action', 'acc_sub_class_delete_action', 'Account Sub Class Delete Action', 0, 1, 0, NULL, NULL),
(133, 'Chart of Account List', 'chart_of_acc_index', 'Chart of Account List', 106, 1, 4, NULL, NULL),
(134, 'Chart of Account Create View', 'chart_of_acc_create_view', 'Chart of Account Create View', 0, 1, 0, NULL, NULL),
(135, 'Chart of Account Create Action', 'chart_of_acc_create_action', 'Chart of Account Create Action', 0, 1, 0, NULL, NULL),
(136, 'Chart of Account Update View', 'chart_of_acc_update_view', 'Chart of Account Update View', 0, 1, 0, NULL, NULL),
(137, 'Chart of Account Update Action', 'chart_of_acc_update_action', 'Chart of Account Update Action', 0, 1, 0, NULL, NULL),
(138, 'Chart of Account Delete Action', 'chart_of_acc_delete_action', 'Chart of Account Delete Action', 0, 1, 0, NULL, NULL),
(139, 'LEDGER', '', 'Accounting ledger view', 0, 1, 0, NULL, NULL),
(140, 'Party ledger', 'ledger_party_index', 'Party Ladger form', 139, 1, 1, NULL, NULL),
(141, 'General ledger', 'ledger_general_index', 'General Ladger form', 139, 1, 2, NULL, NULL),
(142, 'PARTY LEDGER SEARCH Action', 'ledger_party_search_action', 'PARTY LEDGER SEARCH Action', 0, 1, 0, NULL, NULL),
(143, 'GENERAL LEDGER SEARCH Action', 'ledger_general_search_action', 'GENERAL LEDGER SEARCH Action', 0, 1, 0, NULL, NULL),
(162, 'Trial Balance', '', 'trail balance', 0, 1, 0, NULL, NULL),
(163, 'Trial Balance', 'trail_balance_view', 'trail_balance_sub_menu', 162, 1, 1, NULL, NULL),
(164, 'Chalan', '', 'chalan', 0, 1, 0, NULL, NULL),
(165, 'Chalan', 'chalan_view', 'chalan_sub_menu', 164, 1, 1, NULL, NULL),
(166, 'role permission action list', 'get_role_permission_view', '', 0, 1, 4, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(167, 'get product name by group', 'get_product_group', '', 0, 1, 4, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(168, 'lc purchase search list', 'lc_product_purchase_search_list', '', 0, 1, 0, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(169, 'lc update view', 'lc_product_purchase_table_update', '', 0, 1, 0, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(170, 'lc summery view', 'lc_product_purchase_summary', '', 0, 1, 0, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(171, 'lc update action', 'lc_product_purchase_update_action', '', 0, 1, 0, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(172, 'check amount', 'check_amount', '', 0, 1, 0, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(173, 'challan search', 'chalan_generate_action', '', 0, 1, 0, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(174, 'sale list search', 'product_sale_list_search', '', 0, 1, 0, '2018-08-02 18:00:00', '2018-08-02 18:00:00'),
(175, 'invoice sales', 'sales_invoice', 'sales invoice', 0, 1, 0, '2018-08-07 18:00:00', '2018-08-07 18:00:00'),
(176, 'sale add form', 'product_sale_form', 'sales add form', 0, 1, 0, '2018-08-07 18:00:00', '2018-08-07 18:00:00'),
(177, 'get product sale', 'get_product_group_sale', '', 0, 1, 0, '2018-08-08 18:00:00', '2018-08-08 18:00:00'),
(178, 'get product sale lc', 'get_product_group_sale_lc', '', 0, 1, 0, '2018-08-08 18:00:00', '2018-08-08 18:00:00'),
(179, 'get_local_purchase_action_add', 'get_from_value', 'local purchase row add', 0, 1, 0, '2018-08-12 18:00:00', '2018-08-12 18:00:00'),
(180, 'Sales Return', '', 'sales return view', 0, 1, 0, '2018-08-16 18:00:00', '2018-08-16 18:00:00'),
(181, 'Sales Return', 'sales_return', 'sales return view', 180, 1, 1, '2018-08-16 18:00:00', '2018-08-16 18:00:00'),
(182, 'return sales action', 'sales_return_get', 'sales return get', 0, 1, 0, '2018-08-16 18:00:00', '2018-08-16 18:00:00'),
(183, 'sales return store', 'sales_return_store', 'store return', 0, 1, 0, '2018-08-17 18:00:00', '2018-08-17 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_packet`
--

CREATE TABLE `mxp_packet` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_quantity` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `com_group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mxp_packet`
--

INSERT INTO `mxp_packet` (`id`, `name`, `quantity`, `unit_quantity`, `unit_id`, `company_id`, `com_group_id`, `user_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(2, 'box', 24, 12, 1, 0, 1, 1, 1, 0, '2018-07-16 01:34:43', '2018-07-16 01:34:43'),
(3, '220kg packet', 220, 220, 1, 0, 1, 1, 1, 0, '2018-07-17 12:07:37', '2018-07-17 12:07:37'),
(4, 'Drum', 1, 100, 2, 10, 1, 53, 1, 0, '2018-08-03 06:01:49', '2018-08-03 06:01:49'),
(5, 'Drum', 1, 200, 2, 10, 1, 53, 1, 0, '2018-08-03 06:02:10', '2018-08-03 06:02:10'),
(6, 'Drum', 1, 300, 2, 10, 1, 53, 1, 0, '2018-08-03 06:02:21', '2018-08-03 06:02:21'),
(7, 'Drum', 1, 500, 2, 10, 1, 53, 1, 0, '2018-08-03 06:02:35', '2018-08-03 06:02:35'),
(8, 'Drum', 1, 50, 2, 10, 1, 53, 1, 0, '2018-08-03 06:02:47', '2018-08-03 06:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_product`
--

CREATE TABLE `mxp_product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `packing_id` int(11) NOT NULL,
  `product_group_id` int(11) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `com_group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `group_id` varchar(250) NOT NULL,
  `comb_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mxp_product`
--

INSERT INTO `mxp_product` (`id`, `name`, `product_code`, `packing_id`, `product_group_id`, `company_id`, `com_group_id`, `user_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`, `group_id`, `comb_id`) VALUES
(2, 'Acid', '2_24857_acid123', 2, 2, 0, 1, 1, 1, 1, '2018-07-16 01:36:37', '2018-07-17 12:07:59', '24857', '2'),
(3, 'Acid', '', 3, 2, 0, 1, 1, 1, 0, '2018-07-17 12:07:59', '2018-07-17 12:07:59', '24857', '3'),
(4, 'polyglykol 6000', '3_532952429_local-002', 3, 2, 0, 1, 1, 1, 0, '2018-07-30 06:02:09', '2018-07-30 06:02:09', '532952429', '3'),
(5, 'polyglykol 500', '3_532912850_local-003', 3, 2, 0, 1, 1, 1, 0, '2018-07-30 07:32:30', '2018-07-30 07:32:30', '532912850', '3'),
(6, 'polyglykol 900', '3_532912842_local-009', 3, 2, 0, 1, 1, 1, 0, '2018-07-30 07:46:22', '2018-07-30 07:46:22', '532912842', '3'),
(7, 'polyglykol 6000', '4_533298137_pk-6000', 4, 3, 10, 1, 53, 1, 0, '2018-08-03 06:03:57', '2018-08-03 06:03:57', '533298137', '4'),
(8, 'polyglykol 8000', '7_533344913_pk-8000', 7, 3, 10, 1, 53, 1, 0, '2018-08-04 07:15:33', '2018-08-04 07:15:33', '533344913', '7');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_product_group`
--

CREATE TABLE `mxp_product_group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `com_group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mxp_product_group`
--

INSERT INTO `mxp_product_group` (`id`, `name`, `company_id`, `com_group_id`, `user_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Pharmaciticalcbcv', 0, 1, 1, 1, 1, '2018-07-16 07:24:04', '2018-07-16 01:24:04'),
(2, 'Chemical', 0, 1, 1, 1, 0, '2018-07-16 01:35:16', '2018-07-16 01:35:16'),
(3, 'Chemical', 10, 1, 53, 1, 0, '2018-08-03 06:00:19', '2018-08-03 06:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_product_purchase`
--

CREATE TABLE `mxp_product_purchase` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `com_group_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `bonus` int(10) NOT NULL DEFAULT '0',
  `due_amount` int(11) NOT NULL,
  `stock_status` tinyint(4) NOT NULL,
  `purchase_date` date NOT NULL,
  `jouranl_posting_rand_grp_id` int(11) NOT NULL,
  `account_head_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mxp_role`
--

CREATE TABLE `mxp_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int(11) NOT NULL,
  `cm_group_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_role`
--

INSERT INTO `mxp_role` (`id`, `name`, `company_id`, `cm_group_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 0, '', 1, '2018-01-14 20:58:10', '2018-01-25 04:51:10'),
(21, 'sales mnager for company-B', 11, '27276', 1, '2018-01-29 06:40:16', '2018-01-29 06:40:16'),
(24, 'Sals Manager_aa', 12, '85702', 1, '2018-01-31 02:45:42', '2018-01-31 02:45:42'),
(25, 'sumit-role-a', 13, '85687', 1, '2018-01-31 02:58:27', '2018-01-31 02:58:27'),
(26, 'sumit-role-b', 14, '85698', 1, '2018-01-31 02:58:38', '2018-01-31 02:58:38'),
(27, 'CEO', 12, '28451', 1, '2018-07-16 02:44:31', '2018-07-16 02:44:31'),
(28, 'chemax super admin', 17, '531804047', 1, '2018-07-16 23:15:27', '2018-07-16 23:15:27'),
(29, 'Chemax Admin', 10, '1', 1, '2018-07-16 23:25:57', '2018-07-16 23:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_sale_products`
--

CREATE TABLE `mxp_sale_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `com_group_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lot_id` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bonus` int(11) NOT NULL,
  `commission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vat` float DEFAULT NULL,
  `total_amount_w_vat` float DEFAULT NULL,
  `due_ammount` float NOT NULL DEFAULT '0',
  `sale_date` date NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mxp_stock`
--

CREATE TABLE `mxp_stock` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `lot_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `com_group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `available_quantity` varchar(255) NOT NULL,
  `avg_price_unit` double(11,2) NOT NULL,
  `sale_price` float NOT NULL DEFAULT '0',
  `sale_quantity` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mxp_store`
--

CREATE TABLE `mxp_store` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `com_group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinytext NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mxp_store`
--

INSERT INTO `mxp_store` (`id`, `name`, `location`, `company_id`, `com_group_id`, `user_id`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'store-1', 'Savar', 0, 1, 1, '1', 1, '2018-07-16 08:35:51', '2018-07-16 02:35:51'),
(2, 'store-1', 'Savar', 0, 1, 1, '1', 0, '2018-07-16 02:36:12', '2018-07-16 02:36:12'),
(3, 'mystore-1', '37,mirpur,dhaka', 10, 1, 53, '1', 0, '2018-08-03 07:38:13', '2018-08-03 07:38:13');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_taxvats`
--

CREATE TABLE `mxp_taxvats` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `calculation_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'null',
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_taxvats`
--

INSERT INTO `mxp_taxvats` (`id`, `name`, `company_id`, `group_id`, `user_id`, `is_deleted`, `calculation_type`, `status`, `created_at`, `updated_at`) VALUES
(2, 'VAT', 0, 1, 1, 0, 'null', 1, '2018-07-16 01:18:39', '2018-07-16 01:18:39'),
(3, 'Tax', 0, 1, 1, 0, 'null', 1, '2018-07-16 01:18:57', '2018-07-16 01:18:57');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_taxvat_cals`
--

CREATE TABLE `mxp_taxvat_cals` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `vat_tax_id` int(11) NOT NULL,
  `sale_purchase_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `price_per_unit` int(11) NOT NULL,
  `total_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Total Price for total quantity',
  `calculate_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount_with_vat` int(11) NOT NULL,
  `percent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mxp_transaction_type`
--

CREATE TABLE `mxp_transaction_type` (
  `transaction_type_id` int(11) NOT NULL,
  `transaction_type_name` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mxp_transaction_type`
--

INSERT INTO `mxp_transaction_type` (`transaction_type_id`, `transaction_type_name`, `is_active`, `created_at`) VALUES
(1, 'JOURNAL', 1, '2018-05-05 00:00:00'),
(2, 'CASHDEPOSIT', 1, '2018-05-05 00:00:00'),
(3, 'SALESORDER', 1, '2018-05-05 00:00:00'),
(4, 'PURCHASEORDER', 1, '2018-05-05 00:00:00'),
(5, 'BANKDEPOSIT', 1, '2018-05-05 00:00:00'),
(6, 'BANKPAYMENT', 1, '2018-05-05 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_translations`
--

CREATE TABLE `mxp_translations` (
  `translation_id` int(10) UNSIGNED NOT NULL,
  `translation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `translation_key_id` int(11) DEFAULT NULL,
  `lan_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `same_trans_key_id` int(11) NOT NULL,
  `is_active` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_translations`
--

INSERT INTO `mxp_translations` (`translation_id`, `translation`, `translation_key_id`, `lan_code`, `same_trans_key_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Accounting System', 1, 'en', 0, 1, '2018-03-05 18:12:49', '2018-07-16 00:55:54'),
(2, 'অ্যাকাউন্টিং সিস্টেম', 1, 'bn', 0, 1, '2018-03-05 18:12:49', '2018-07-16 00:55:54'),
(3, 'Log In', 2, 'en', 0, 1, '2018-03-05 20:38:51', '2018-03-05 20:39:11'),
(4, 'লগ ইন', 2, 'bn', 0, 1, '2018-03-05 20:38:51', '2018-03-05 20:39:11'),
(5, 'Registration', 3, 'en', 0, 1, '2018-03-05 20:39:27', '2018-03-05 20:41:56'),
(6, 'নিবন্ধন করুন', 3, 'bn', 0, 1, '2018-03-05 20:39:27', '2018-03-05 20:41:56'),
(7, 'Whoops!', 4, 'en', 0, 1, '2018-03-05 20:54:56', '2018-03-05 21:04:24'),
(8, 'উপস!', 4, 'bn', 0, 1, '2018-03-05 20:54:56', '2018-03-05 21:04:24'),
(9, 'There were some problems with your input.', 5, 'en', 0, 1, '2018-03-05 20:56:52', '2018-03-05 21:03:46'),
(10, 'আপনার ইনপুট সঙ্গে কিছু সমস্যা ছিল।', 5, 'bn', 0, 1, '2018-03-05 20:56:52', '2018-03-05 21:03:46'),
(11, 'Or you are not active yet.', 6, 'en', 0, 1, '2018-03-05 20:57:04', '2018-03-05 21:03:01'),
(12, 'অথবা আপনি এখনো সক্রিয় নন', 6, 'bn', 0, 1, '2018-03-05 20:57:04', '2018-03-05 21:03:01'),
(13, 'E-Mail Address', 7, 'en', 0, 1, '2018-03-05 20:57:14', '2018-03-05 20:59:25'),
(14, 'ই-মেইল ঠিকানা', 7, 'bn', 0, 1, '2018-03-05 20:57:14', '2018-03-05 20:59:25'),
(15, 'Password', 8, 'en', 0, 1, '2018-03-05 20:57:22', '2018-03-05 21:00:01'),
(16, 'পাসওয়ার্ড', 8, 'bn', 0, 1, '2018-03-05 20:57:22', '2018-03-05 21:00:01'),
(17, 'Remember me?', 9, 'en', 0, 1, '2018-03-05 20:57:31', '2018-03-05 21:02:15'),
(18, 'আমাকে মনে রাখুন?', 9, 'bn', 0, 1, '2018-03-05 20:57:31', '2018-03-05 21:02:15'),
(19, 'Forgot Your Password?', 10, 'en', 0, 1, '2018-03-05 20:57:39', '2018-03-05 21:00:39'),
(20, 'আপনি কি পাসওয়ার্ড ভুলে গেছেন?', 10, 'bn', 0, 1, '2018-03-05 20:57:39', '2018-03-05 21:00:39'),
(21, 'Dashboard', 11, 'en', 0, 1, '2018-03-05 23:23:51', '2018-03-05 23:32:59'),
(22, 'ড্যাশবোর্ড', 11, 'bn', 0, 1, '2018-03-05 23:23:51', '2018-03-05 23:32:59'),
(23, 'Language List', 12, 'en', 0, 1, '2018-03-05 23:34:35', '2018-03-05 23:35:06'),
(24, 'ভাষা তালিকা', 12, 'bn', 0, 1, '2018-03-05 23:34:35', '2018-03-05 23:35:06'),
(25, 'Serial no.', 13, 'en', 0, 1, '2018-03-05 23:36:43', '2018-03-05 23:37:54'),
(26, 'ক্রমিক নং', 13, 'bn', 0, 1, '2018-03-05 23:36:44', '2018-03-05 23:37:54'),
(27, 'Language Title', 14, 'en', 0, 1, '2018-03-05 23:38:13', '2018-03-05 23:38:37'),
(28, 'ভাষা শিরোনাম', 14, 'bn', 0, 1, '2018-03-05 23:38:13', '2018-03-05 23:38:37'),
(29, 'Language Code', 15, 'en', 0, 1, '2018-03-05 23:38:47', '2018-03-05 23:39:11'),
(30, 'ভাষা কোড', 15, 'bn', 0, 1, '2018-03-05 23:38:47', '2018-03-05 23:39:11'),
(31, 'Status', 16, 'en', 0, 1, '2018-03-05 23:39:23', '2018-03-05 23:40:25'),
(32, 'সাময়িক অবস্থা', 16, 'bn', 0, 1, '2018-03-05 23:39:23', '2018-03-05 23:40:25'),
(33, 'Action', 17, 'en', 0, 1, '2018-03-05 23:40:40', '2018-03-05 23:42:00'),
(34, 'ক্রিয়াকলাপ', 17, 'bn', 0, 1, '2018-03-05 23:40:40', '2018-03-05 23:42:00'),
(35, 'Active', 18, 'en', 0, 1, '2018-03-05 23:43:00', '2018-03-05 23:43:27'),
(36, 'সক্রিয়', 18, 'bn', 0, 1, '2018-03-05 23:43:00', '2018-03-05 23:43:27'),
(37, 'Inactive', 19, 'en', 0, 1, '2018-03-05 23:43:47', '2018-03-05 23:44:13'),
(38, 'নিষ্ক্রিয়', 19, 'bn', 0, 1, '2018-03-05 23:43:47', '2018-03-05 23:44:13'),
(39, 'Add Locale', 20, 'en', 0, 1, '2018-03-05 23:58:03', '2018-03-05 23:59:51'),
(40, 'স্থান যোগ করুন', 20, 'bn', 0, 1, '2018-03-05 23:58:03', '2018-03-05 23:59:52'),
(41, 'edit', 21, 'en', 0, 1, '2018-03-06 00:00:03', '2018-03-06 00:01:53'),
(42, 'পরিবর্তন করুন', 21, 'bn', 0, 1, '2018-03-06 00:00:03', '2018-03-06 00:01:53'),
(43, 'Add new Language', 22, 'en', 0, 1, '2018-03-06 00:14:26', '2018-03-06 00:15:12'),
(44, 'নতুন ভাষা যোগ করুন', 22, 'bn', 0, 1, '2018-03-06 00:14:26', '2018-03-06 00:15:12'),
(45, 'Add Language', 23, 'en', 0, 1, '2018-03-06 00:15:45', '2018-03-06 00:16:16'),
(46, 'ভাষা যোগ করুন', 23, 'bn', 0, 1, '2018-03-06 00:15:45', '2018-03-06 00:16:16'),
(47, 'Enter Language Title', 24, 'en', 0, 1, '2018-03-06 00:16:49', '2018-03-06 00:17:21'),
(48, 'ভাষা শিরোনাম লিখুন', 24, 'bn', 0, 1, '2018-03-06 00:16:49', '2018-03-06 00:17:21'),
(49, 'Enter Language Code', 25, 'en', 0, 1, '2018-03-06 00:17:31', '2018-03-06 00:17:54'),
(50, 'ভাষা কোড লিখুন', 25, 'bn', 0, 1, '2018-03-06 00:17:31', '2018-03-06 00:17:54'),
(51, 'Save', 26, 'en', 0, 1, '2018-03-06 00:18:57', '2018-03-06 00:19:17'),
(52, 'সংরক্ষণ করুন', 26, 'bn', 0, 1, '2018-03-06 00:18:57', '2018-03-06 00:19:17'),
(53, 'Update Locale', 27, 'en', 0, 1, '2018-03-06 00:23:12', '2018-03-06 00:28:13'),
(54, 'লোকেল আপডেট করুন', 27, 'bn', 0, 1, '2018-03-06 00:23:12', '2018-03-06 00:28:13'),
(55, 'Update Language Title', 28, 'en', 0, 1, '2018-03-06 00:28:35', '2018-03-06 00:29:18'),
(56, 'ভাষা শিরোনাম আপডেট করুন', 28, 'bn', 0, 1, '2018-03-06 00:28:36', '2018-03-06 00:29:18'),
(57, 'Update Language Code', 29, 'en', 0, 1, '2018-03-06 00:29:32', '2018-03-06 00:29:55'),
(58, 'ভাষা কোড আপডেট করুন', 29, 'bn', 0, 1, '2018-03-06 00:29:32', '2018-03-06 00:29:55'),
(59, 'Update', 30, 'en', 0, 1, '2018-03-06 00:30:07', '2018-03-06 00:30:52'),
(60, 'পরিবর্তন করুন', 30, 'bn', 0, 1, '2018-03-06 00:30:07', '2018-03-06 00:30:52'),
(61, 'Update Language', 31, 'en', 0, 1, '2018-03-06 00:32:05', '2018-03-06 00:32:45'),
(62, 'ভাষা আপডেট করুন', 31, 'bn', 0, 1, '2018-03-06 00:32:05', '2018-03-06 00:32:45'),
(63, 'Comfirm! you want to upload translation file..', 32, 'en', 0, 1, '2018-03-06 00:34:41', '2018-03-06 00:36:01'),
(64, 'নিশ্চিত করুন! আপনি অনুবাদ ফাইল আপলোড করতে চান ..', 32, 'bn', 0, 1, '2018-03-06 00:34:41', '2018-03-06 00:36:01'),
(65, 'Upload', 33, 'en', 0, 1, '2018-03-06 00:36:42', '2018-03-06 00:37:14'),
(66, 'আপলোড', 33, 'bn', 0, 1, '2018-03-06 00:36:42', '2018-03-06 00:37:14'),
(67, 'Translation List', 34, 'en', 0, 1, '2018-03-06 00:39:26', '2018-03-06 00:49:15'),
(68, 'অনুবাদ তালিকা', 34, 'bn', 0, 1, '2018-03-06 00:39:26', '2018-03-06 00:49:15'),
(69, 'Add new key', 35, 'en', 0, 1, '2018-03-06 00:49:29', '2018-03-06 00:51:01'),
(70, 'নতুন টীকা যোগ করুন', 35, 'bn', 0, 1, '2018-03-06 00:49:29', '2018-03-06 00:51:01'),
(71, 'Search the translation key....', 36, 'en', 0, 1, '2018-03-06 00:51:16', '2018-03-06 00:52:27'),
(72, 'অনুবাদ টীকা অনুসন্ধান করুন ....', 36, 'bn', 0, 1, '2018-03-06 00:51:16', '2018-03-06 00:52:27'),
(73, 'Translation key', 37, 'en', 0, 1, '2018-03-06 00:52:45', '2018-03-06 00:54:17'),
(74, 'অনুবাদ টীকা', 37, 'bn', 0, 1, '2018-03-06 00:52:45', '2018-03-06 00:54:17'),
(75, 'Translation', 38, 'en', 0, 1, '2018-03-06 00:54:31', '2018-03-06 00:55:09'),
(76, 'অনুবাদ', 38, 'bn', 0, 1, '2018-03-06 00:54:31', '2018-03-06 00:55:09'),
(77, 'Language', 39, 'en', 0, 1, '2018-03-06 00:55:21', '2018-03-06 00:55:50'),
(78, 'ভাষা', 39, 'bn', 0, 1, '2018-03-06 00:55:21', '2018-03-06 00:55:50'),
(79, 'Delete', 40, 'en', 0, 1, '2018-03-06 00:56:29', '2018-03-06 00:56:51'),
(80, 'বাদ দিন', 40, 'bn', 0, 1, '2018-03-06 00:56:29', '2018-03-06 00:56:51'),
(81, 'Add new translation key', 41, 'en', 0, 1, '2018-03-06 01:07:29', '2018-03-06 01:08:09'),
(82, 'নতুন অনুবাদ টীকা যোগ করুন', 41, 'bn', 0, 1, '2018-03-06 01:07:29', '2018-03-06 01:08:09'),
(83, 'Enter Translation key', 42, 'en', 0, 1, '2018-03-06 01:08:20', '2018-03-06 01:09:01'),
(84, 'অনুবাদ টীকা প্রবেশ করান', 42, 'bn', 0, 1, '2018-03-06 01:08:20', '2018-03-06 01:09:01'),
(85, 'Update Translation', 43, 'en', 0, 1, '2018-03-06 01:18:54', '2018-03-06 01:19:29'),
(86, 'অনুবাদ আপডেট করুন', 43, 'bn', 0, 1, '2018-03-06 01:18:54', '2018-03-06 01:19:29'),
(87, 'Update Translation key', 44, 'en', 0, 1, '2018-03-06 01:19:50', '2018-03-06 01:20:39'),
(88, 'অনুবাদ টীকা আপডেট করুন', 44, 'bn', 0, 1, '2018-03-06 01:19:50', '2018-03-06 01:20:39'),
(89, 'LANGUAGE', 45, 'en', 0, 1, '2018-03-06 19:21:58', '2018-03-06 19:27:49'),
(90, 'ভাষা', 45, 'bn', 0, 1, '2018-03-06 19:21:58', '2018-03-06 19:27:49'),
(91, 'Manage Language', 46, 'en', 0, 1, '2018-03-06 19:23:15', '2018-03-06 19:24:25'),
(92, 'ভাষা পরিচালনা করুন', 46, 'bn', 0, 1, '2018-03-06 19:23:15', '2018-03-06 19:24:25'),
(93, 'Manage Translation', 47, 'en', 0, 1, '2018-03-06 19:24:37', '2018-03-06 19:25:16'),
(94, 'অনুবাদ পরিচালনা করুন', 47, 'bn', 0, 1, '2018-03-06 19:24:37', '2018-03-06 19:25:17'),
(95, 'Upload Language File', 48, 'en', 0, 1, '2018-03-06 19:25:41', '2018-03-06 19:26:18'),
(96, 'ভাষা ফাইল আপলোড করুন', 48, 'bn', 0, 1, '2018-03-06 19:25:41', '2018-03-06 19:26:18'),
(97, 'ROLE', 49, 'en', 0, 1, '2018-03-06 19:26:59', '2018-03-06 19:27:26'),
(98, 'ভূমিকা', 49, 'bn', 0, 1, '2018-03-06 19:26:59', '2018-03-06 19:27:26'),
(99, 'Add New Role', 50, 'en', 0, 1, '2018-03-06 19:28:03', '2018-03-06 19:29:56'),
(100, 'নতুন ভূমিকা যোগ করুন', 50, 'bn', 0, 1, '2018-03-06 19:28:03', '2018-03-06 19:29:56'),
(101, 'Role List', 51, 'en', 0, 1, '2018-03-06 19:30:11', '2018-03-06 19:30:35'),
(102, 'ভূমিকা তালিকা', 51, 'bn', 0, 1, '2018-03-06 19:30:11', '2018-03-06 19:30:36'),
(103, 'Role Permission', 52, 'en', 0, 1, '2018-03-06 19:30:45', '2018-03-06 19:31:10'),
(104, 'ভূমিকা অনুমতি', 52, 'bn', 0, 1, '2018-03-06 19:30:45', '2018-03-06 19:31:10'),
(105, 'SETTINGS', 53, 'en', 0, 1, '2018-03-06 19:31:22', '2018-03-06 19:31:55'),
(106, 'সেটিংস', 53, 'bn', 0, 1, '2018-03-06 19:31:22', '2018-03-06 19:31:55'),
(107, 'Open Company Account', 54, 'en', 0, 1, '2018-03-06 19:32:15', '2018-03-06 19:34:08'),
(108, 'কোম্পানি অ্যাকাউন্ট খোলা', 54, 'bn', 0, 1, '2018-03-06 19:32:15', '2018-03-06 19:34:08'),
(109, 'Company List', 55, 'en', 0, 1, '2018-03-06 19:34:19', '2018-03-06 19:34:45'),
(110, 'কোম্পানি তালিকা', 55, 'bn', 0, 1, '2018-03-06 19:34:19', '2018-03-06 19:34:45'),
(111, 'Create User', 56, 'en', 0, 1, '2018-03-06 19:34:56', '2018-03-06 19:36:05'),
(112, 'নতুন ব্যবহারকারী সংযোজন', 56, 'bn', 0, 1, '2018-03-06 19:34:56', '2018-03-06 19:36:05'),
(113, 'Create User', 57, 'en', 0, 1, '2018-03-06 19:36:15', '2018-03-06 19:38:03'),
(114, 'নতুন ব্যবহারকারী সংযোজন', 57, 'bn', 0, 1, '2018-03-06 19:36:15', '2018-03-06 19:38:03'),
(115, 'User List', 58, 'en', 0, 1, '2018-03-06 19:39:56', '2018-03-06 19:40:22'),
(116, 'ব্যবহারকারীর তালিকা', 58, 'bn', 0, 1, '2018-03-06 19:39:56', '2018-03-06 19:40:22'),
(117, 'Client List', 59, 'en', 0, 1, '2018-03-06 19:40:33', '2018-03-06 19:41:36'),
(118, 'গ্রাহকের তালিকা', 59, 'bn', 0, 1, '2018-03-06 19:40:33', '2018-03-06 19:41:36'),
(119, 'PRODUCT', 60, 'en', 0, 1, '2018-03-06 19:41:56', '2018-07-16 07:17:00'),
(120, 'পণ্য', 60, 'bn', 0, 1, '2018-03-06 19:41:56', '2018-07-16 07:17:00'),
(121, 'Product\'s Unit', 61, 'en', 0, 1, '2018-03-06 19:42:32', '2018-03-06 19:48:13'),
(122, 'পণ্যের একক', 61, 'bn', 0, 1, '2018-03-06 19:42:32', '2018-03-06 19:48:13'),
(123, 'Product Group', 62, 'en', 0, 1, '2018-03-06 19:48:24', '2018-03-06 19:48:54'),
(124, 'পণ্যের গ্রুপ', 62, 'bn', 0, 1, '2018-03-06 19:48:25', '2018-03-06 19:48:54'),
(125, 'Product Entry', 63, 'en', 0, 1, '2018-03-06 19:49:03', '2018-03-06 19:50:00'),
(126, 'পণ্য সংযোজন', 63, 'bn', 0, 1, '2018-03-06 19:49:03', '2018-03-06 19:50:00'),
(127, 'Product Packing', 64, 'en', 0, 1, '2018-03-06 19:50:09', '2018-03-06 19:50:39'),
(128, 'পণ্য প্যাকেজিং', 64, 'bn', 0, 1, '2018-03-06 19:50:09', '2018-03-06 19:50:39'),
(129, 'Purchase', 65, 'en', 0, 1, '2018-03-06 19:50:54', '2018-03-06 19:51:38'),
(130, 'ক্রয়', 65, 'bn', 0, 1, '2018-03-06 19:50:54', '2018-03-06 19:51:38'),
(131, 'Purchase List', 66, 'en', 0, 1, '2018-03-06 19:51:47', '2018-03-06 19:52:14'),
(132, 'ক্রয় তালিকা', 66, 'bn', 0, 1, '2018-03-06 19:51:48', '2018-03-06 19:52:14'),
(133, 'Update Stock', 67, 'en', 0, 1, '2018-03-06 19:52:27', '2018-03-06 19:53:39'),
(134, 'আপডেট স্টক', 67, 'bn', 0, 1, '2018-03-06 19:52:27', '2018-03-06 19:53:40'),
(135, 'Vat Tax List', 68, 'en', 0, 1, '2018-03-06 19:53:48', '2018-03-06 19:54:15'),
(136, 'ভ্যাট ট্যাক্স তালিকা', 68, 'bn', 0, 1, '2018-03-06 19:53:48', '2018-03-06 19:54:15'),
(137, 'Sale List', 69, 'en', 0, 1, '2018-03-06 19:54:25', '2018-03-06 19:54:55'),
(138, 'বিক্রয় তালিকা', 69, 'bn', 0, 1, '2018-03-06 19:54:25', '2018-03-06 19:54:55'),
(139, 'Save Sale', 70, 'en', 0, 1, '2018-03-06 19:55:15', '2018-03-06 19:56:07'),
(140, 'বিক্রয় যোগ করুন', 70, 'bn', 0, 1, '2018-03-06 19:55:15', '2018-03-06 19:56:07'),
(141, 'Inventory Report', 71, 'en', 0, 1, '2018-03-06 19:56:45', '2018-03-06 19:57:12'),
(142, 'পরিসংখ্যা প্রতিবেদন', 71, 'bn', 0, 1, '2018-03-06 19:56:45', '2018-03-06 19:57:12'),
(143, 'STOCK MANAGEMENT', 72, 'en', 0, 1, '2018-03-06 19:57:21', '2018-03-06 19:57:51'),
(144, 'স্টক ব্যবস্থাপনা', 72, 'bn', 0, 1, '2018-03-06 19:57:21', '2018-03-06 19:57:51'),
(145, 'Store', 73, 'en', 0, 1, '2018-03-06 19:58:01', '2018-03-06 19:58:42'),
(146, 'ভাণ্ডার', 73, 'bn', 0, 1, '2018-03-06 19:58:01', '2018-03-06 19:58:42'),
(147, 'Stock', 74, 'en', 0, 1, '2018-03-06 19:58:53', '2018-03-06 19:59:16'),
(148, 'স্টক', 74, 'bn', 0, 1, '2018-03-06 19:58:53', '2018-03-06 19:59:17'),
(151, 'Company/Client Name', 76, 'en', 0, 1, '2018-03-06 20:57:06', '2018-03-06 20:57:55'),
(152, 'কোম্পানী / গ্রাহকের নাম', 76, 'bn', 0, 1, '2018-03-06 20:57:06', '2018-03-06 20:57:55'),
(153, 'Role Name', 77, 'en', 0, 1, '2018-03-06 21:05:38', '2018-03-06 21:06:30'),
(154, 'ভূমিকার নাম', 77, 'bn', 0, 1, '2018-03-06 21:05:38', '2018-03-06 21:06:30'),
(155, 'Select Company/Client', 78, 'en', 0, 1, '2018-03-06 21:06:59', '2018-03-06 21:07:40'),
(156, 'কোম্পানী / ক্লায়েন্ট নির্বাচন করুন', 78, 'bn', 0, 1, '2018-03-06 21:06:59', '2018-03-06 21:07:40'),
(157, 'Select Role', 79, 'en', 0, 1, '2018-03-06 21:08:51', '2018-03-06 21:09:15'),
(158, 'ভূমিকা নির্বাচন করুন', 79, 'bn', 0, 1, '2018-03-06 21:08:51', '2018-03-06 21:09:15'),
(159, 'Select All', 80, 'en', 0, 1, '2018-03-06 21:11:57', '2018-03-06 21:12:22'),
(160, 'সব নির্বাচন করুন', 80, 'bn', 0, 1, '2018-03-06 21:11:57', '2018-03-06 21:12:23'),
(161, 'Unselect all', 81, 'en', 0, 1, '2018-03-06 21:12:36', '2018-03-06 21:12:57'),
(162, 'সরিয়ে ফেলুন সব', 81, 'bn', 0, 1, '2018-03-06 21:12:36', '2018-03-06 21:12:57'),
(163, 'SET', 82, 'en', 0, 1, '2018-03-06 21:14:03', '2018-03-06 21:14:34'),
(164, 'নিযুক্ত করা', 82, 'bn', 0, 1, '2018-03-06 21:14:03', '2018-03-06 21:14:34'),
(165, 'Assign Role', 83, 'en', 0, 1, '2018-03-06 21:15:41', '2018-03-06 21:16:07'),
(166, 'ভূমিকা অর্পণ', 83, 'bn', 0, 1, '2018-03-06 21:15:41', '2018-03-06 21:16:07'),
(167, 'Role Permission List', 84, 'en', 0, 1, '2018-03-06 21:19:23', '2018-03-06 21:19:45'),
(168, 'ভূমিকা অনুমতি তালিকা', 84, 'bn', 0, 1, '2018-03-06 21:19:23', '2018-03-06 21:19:45'),
(169, 'Permitted Route List', 85, 'en', 0, 1, '2018-03-06 21:19:57', '2018-03-06 21:20:30'),
(170, 'অনুমোদিত রুট তালিকা', 85, 'bn', 0, 1, '2018-03-06 21:19:57', '2018-03-06 21:20:30'),
(171, 'Update Role', 86, 'en', 0, 1, '2018-03-06 21:36:58', '2018-03-06 21:37:20'),
(172, 'আপডেট ভূমিকা', 86, 'bn', 0, 1, '2018-03-06 21:36:58', '2018-03-06 21:37:20'),
(173, 'Add Stock', 87, 'en', 0, 1, '2018-03-06 22:00:58', '2018-03-06 22:01:24'),
(174, 'স্টক যোগ করুন', 87, 'bn', 0, 1, '2018-03-06 22:00:58', '2018-03-06 22:01:24'),
(175, 'Product/Particular Name', 88, 'en', 0, 1, '2018-03-06 22:01:41', '2018-03-06 22:02:19'),
(176, 'পণ্য / বিশেষ নাম', 88, 'bn', 0, 1, '2018-03-06 22:01:41', '2018-03-06 22:02:19'),
(177, 'Product/Particular Group', 89, 'en', 0, 1, '2018-03-06 22:02:40', '2018-03-06 22:03:10'),
(178, 'পণ্য / বিশেষ গ্রুপ', 89, 'bn', 0, 1, '2018-03-06 22:02:40', '2018-03-06 22:03:10'),
(179, 'Quantity', 90, 'en', 0, 1, '2018-03-06 22:03:38', '2018-03-06 22:04:05'),
(180, 'পরিমাণ', 90, 'bn', 0, 1, '2018-03-06 22:03:38', '2018-03-06 22:04:05'),
(181, 'Select Location', 91, 'en', 0, 1, '2018-03-06 22:04:43', '2018-03-06 22:05:00'),
(182, 'স্থান নির্বাচন করুন', 91, 'bn', 0, 1, '2018-03-06 22:04:43', '2018-03-06 22:05:01'),
(187, 'Add new Store', 94, 'en', 0, 1, '2018-03-06 22:21:41', '2018-03-06 22:22:04'),
(188, 'নতুন স্টোর যোগ করুন', 94, 'bn', 0, 1, '2018-03-06 22:21:41', '2018-03-06 22:22:04'),
(189, 'Add store', 95, 'en', 0, 1, '2018-03-06 22:22:14', '2018-03-06 22:22:58'),
(190, 'স্টোর যোগ করুন', 95, 'bn', 0, 1, '2018-03-06 22:22:14', '2018-03-06 22:22:58'),
(191, 'Enter Store Name', 96, 'en', 0, 1, '2018-03-06 22:23:21', '2018-03-06 22:23:42'),
(192, 'স্টোর নাম লিখুন', 96, 'bn', 0, 1, '2018-03-06 22:23:21', '2018-03-06 22:23:42'),
(193, 'Enter Store Location', 97, 'en', 0, 1, '2018-03-06 22:23:51', '2018-03-06 22:24:16'),
(194, 'দোকানের অবস্থান লিখুন', 97, 'bn', 0, 1, '2018-03-06 22:23:51', '2018-03-06 22:24:16'),
(195, 'Update Store', 98, 'en', 0, 1, '2018-03-06 22:27:47', '2018-03-06 22:28:16'),
(196, 'আপডেট স্টোর', 98, 'bn', 0, 1, '2018-03-06 22:27:47', '2018-03-06 22:28:16'),
(199, 'Store List', 100, 'en', 0, 1, '2018-03-06 22:34:46', '2018-03-06 22:36:17'),
(200, 'স্টোর তালিকা', 100, 'bn', 0, 1, '2018-03-06 22:34:46', '2018-03-06 22:36:17'),
(201, 'Store Name', 101, 'en', 0, 1, '2018-03-06 22:36:32', '2018-03-06 22:37:16'),
(202, 'স্টোরের নাম', 101, 'bn', 0, 1, '2018-03-06 22:36:32', '2018-03-06 22:37:16'),
(203, 'Store Location', 102, 'en', 0, 1, '2018-03-06 22:37:36', '2018-03-06 22:38:13'),
(204, 'স্টোরের অবস্থান', 102, 'bn', 0, 1, '2018-03-06 22:37:36', '2018-03-06 22:38:13'),
(205, 'List of Responsible people', 103, 'en', 0, 1, '2018-03-06 22:45:51', '2018-03-06 22:46:15'),
(206, 'দায়ী ব্যক্তিদের তালিকা', 103, 'bn', 0, 1, '2018-03-06 22:45:51', '2018-03-06 22:46:15'),
(207, 'Company/Client Phone Number', 104, 'en', 0, 1, '2018-03-07 21:50:23', '2018-03-07 21:51:13'),
(208, 'কোম্পানী / ক্লায়েন্ট ফোন নম্বর', 104, 'bn', 0, 1, '2018-03-07 21:50:23', '2018-03-07 21:51:13'),
(209, 'Company/Client Address', 105, 'en', 0, 1, '2018-03-07 21:51:29', '2018-03-07 21:51:58'),
(210, 'কোম্পানী / ক্লায়েন্ট ঠিকানা', 105, 'bn', 0, 1, '2018-03-07 21:51:29', '2018-03-07 21:51:58'),
(211, 'Company/Client Description', 106, 'en', 0, 1, '2018-03-07 21:52:22', '2018-03-07 21:52:55'),
(212, 'কোম্পানি / ক্লায়েন্ট বর্ণনা', 106, 'bn', 0, 1, '2018-03-07 21:52:22', '2018-03-07 21:52:55'),
(213, 'Employee Name', 107, 'en', 0, 1, '2018-03-07 23:00:58', '2018-03-07 23:02:22'),
(214, 'কর্মকর্তার নাম', 107, 'bn', 0, 1, '2018-03-07 23:00:58', '2018-03-07 23:02:22'),
(215, 'Personal Phone Number', 108, 'en', 0, 1, '2018-03-07 23:02:33', '2018-03-07 23:03:02'),
(216, 'ব্যক্তিগত ফোন নম্বর', 108, 'bn', 0, 1, '2018-03-07 23:02:33', '2018-03-07 23:03:02'),
(217, 'Employee Address', 109, 'en', 0, 1, '2018-03-07 23:03:16', '2018-03-07 23:03:38'),
(218, 'কর্মচারী ঠিকানা', 109, 'bn', 0, 1, '2018-03-07 23:03:16', '2018-03-07 23:03:38'),
(219, 'Password Confirmation', 110, 'en', 0, 1, '2018-03-07 23:03:52', '2018-03-07 23:04:14'),
(220, 'পাসওয়ার্ড নিশ্চিতকরণ', 110, 'bn', 0, 1, '2018-03-07 23:03:52', '2018-03-07 23:04:14'),
(221, 'Search', 111, 'en', 0, 1, '2018-03-07 23:11:42', '2018-03-07 23:11:59'),
(222, 'অনুসন্ধান', 111, 'bn', 0, 1, '2018-03-07 23:11:43', '2018-03-07 23:11:59'),
(223, 'Company', 112, 'en', 0, 1, '2018-03-07 23:21:05', '2018-03-07 23:21:36'),
(224, 'কোম্পানি', 112, 'bn', 0, 1, '2018-03-07 23:21:05', '2018-03-07 23:21:36'),
(225, 'Add Client/Company', 113, 'en', 0, 1, '2018-03-07 23:52:58', '2018-03-07 23:53:35'),
(226, 'ক্লায়েন্ট / কোম্পানি যোগ করুন', 113, 'bn', 0, 1, '2018-03-07 23:52:58', '2018-03-07 23:53:35'),
(227, 'Update Company/Client', 114, 'en', 0, 1, '2018-03-08 17:19:08', '2018-03-08 17:27:08'),
(228, 'আপডেট কোম্পানী / ক্লায়েন্ট', 114, 'bn', 0, 1, '2018-03-08 17:19:08', '2018-03-08 17:27:08'),
(229, 'Add Packet', 115, 'en', 0, 1, '2018-03-09 17:02:11', '2018-03-09 17:02:56'),
(230, 'প্যাকেট যোগ করুন', 115, 'bn', 0, 1, '2018-03-09 17:02:11', '2018-03-09 17:02:56'),
(231, 'Select Unit', 116, 'en', 0, 1, '2018-03-09 17:04:20', '2018-03-09 17:04:45'),
(232, 'ইউনিট নির্বাচন করুন', 116, 'bn', 0, 1, '2018-03-09 17:04:20', '2018-03-09 17:04:45'),
(233, 'Packet Name', 117, 'en', 0, 1, '2018-03-09 17:06:17', '2018-03-09 17:06:34'),
(234, 'প্যাকেট নাম', 117, 'bn', 0, 1, '2018-03-09 17:06:17', '2018-03-09 17:06:34'),
(235, 'Unit Quantity', 118, 'en', 0, 1, '2018-03-09 17:07:27', '2018-03-09 17:07:48'),
(236, 'একক পরিমাণ', 118, 'bn', 0, 1, '2018-03-09 17:07:27', '2018-03-09 17:07:48'),
(237, 'Update Packet', 119, 'en', 0, 1, '2018-03-09 17:13:42', '2018-03-09 17:14:04'),
(238, 'আপডেট প্যাকেট', 119, 'bn', 0, 1, '2018-03-09 17:13:42', '2018-03-09 17:14:04'),
(239, 'Unit', 120, 'en', 0, 1, '2018-03-09 17:18:32', '2018-03-09 17:18:51'),
(240, 'একক', 120, 'bn', 0, 1, '2018-03-09 17:18:32', '2018-03-09 17:18:51'),
(241, 'Packet List', 121, 'en', 0, 1, '2018-03-09 17:24:19', '2018-03-09 17:24:43'),
(242, 'প্যাকেট তালিকা', 121, 'bn', 0, 1, '2018-03-09 17:24:19', '2018-03-09 17:24:43'),
(243, 'Add new Product', 122, 'en', 0, 1, '2018-03-09 17:52:50', '2018-03-09 17:53:11'),
(244, 'নতুন পণ্য যোগ করুন', 122, 'bn', 0, 1, '2018-03-09 17:52:50', '2018-03-09 17:53:11'),
(245, 'Add Product', 123, 'en', 0, 1, '2018-03-09 17:53:19', '2018-03-09 17:53:41'),
(246, 'পণ্য যোগ করুন', 123, 'bn', 0, 1, '2018-03-09 17:53:19', '2018-03-09 17:53:41'),
(247, 'Packet details', 124, 'en', 0, 1, '2018-03-09 17:56:43', '2018-03-09 17:56:59'),
(248, 'প্যাকেট বিস্তারিত', 124, 'bn', 0, 1, '2018-03-09 17:56:43', '2018-03-09 17:56:59'),
(249, 'Product Code', 125, 'en', 0, 1, '2018-03-09 18:02:50', '2018-03-09 18:03:28'),
(250, 'পণ্য কোড', 125, 'bn', 0, 1, '2018-03-09 18:02:50', '2018-03-09 18:03:28'),
(251, 'Update Product', 126, 'en', 0, 1, '2018-03-09 18:09:32', '2018-03-09 18:10:24'),
(252, 'আপডেট পণ্য', 126, 'bn', 0, 1, '2018-03-09 18:09:33', '2018-03-09 18:10:24'),
(253, 'Edit product', 127, 'en', 0, 1, '2018-03-09 18:10:38', '2018-03-09 18:11:58'),
(254, 'পণ্য সংস্করণ', 127, 'bn', 0, 1, '2018-03-09 18:10:38', '2018-03-09 18:11:58'),
(255, 'Product Group Name', 128, 'en', 0, 1, '2018-03-09 18:26:17', '2018-07-16 06:47:07'),
(256, 'পণ্য গ্রুপ নাম', 128, 'bn', 0, 1, '2018-03-09 18:26:17', '2018-07-16 06:47:07'),
(257, 'Add product group', 129, 'en', 0, 1, '2018-03-09 18:26:52', '2018-03-09 18:27:11'),
(258, 'পণ্য গ্রুপ যোগ করুন', 129, 'bn', 0, 1, '2018-03-09 18:26:52', '2018-03-09 18:27:11'),
(259, 'Add new product group', 130, 'en', 0, 1, '2018-03-09 18:27:22', '2018-03-09 18:27:45'),
(260, 'নতুন পণ্য গ্রুপ যোগ করুন', 130, 'bn', 0, 1, '2018-03-09 18:27:22', '2018-03-09 18:27:45'),
(261, 'Update Product Group', 131, 'en', 0, 1, '2018-03-09 18:34:53', '2018-03-09 18:35:12'),
(262, 'আপডেট পণ্য গ্রুপ', 131, 'bn', 0, 1, '2018-03-09 18:34:53', '2018-03-09 18:35:12'),
(263, 'Edit product group', 132, 'en', 0, 1, '2018-03-09 18:35:57', '2018-03-09 18:36:25'),
(264, 'পণ্য গ্রুপ সম্পাদনা করুন', 132, 'bn', 0, 1, '2018-03-09 18:35:57', '2018-03-09 18:36:25'),
(265, 'Product Group List', 133, 'en', 0, 1, '2018-03-09 18:39:48', '2018-03-09 18:40:05'),
(266, 'পণ্য গ্রুপ তালিকা', 133, 'bn', 0, 1, '2018-03-09 18:39:48', '2018-03-09 18:40:05'),
(267, 'Unit name', 134, 'en', 0, 1, '2018-03-09 19:00:04', '2018-03-09 19:00:25'),
(268, 'ইউনিটের নাম', 134, 'bn', 0, 1, '2018-03-09 19:00:04', '2018-03-09 19:00:25'),
(269, 'Add unit', 135, 'en', 0, 1, '2018-03-09 19:00:51', '2018-03-09 19:01:55'),
(270, 'ইউনিট যোগ করুন', 135, 'bn', 0, 1, '2018-03-09 19:00:51', '2018-03-09 19:01:55'),
(271, 'Add new Unit', 136, 'en', 0, 1, '2018-03-09 19:02:17', '2018-03-09 19:02:40'),
(272, 'নতুন ইউনিট যোগ করুন', 136, 'bn', 0, 1, '2018-03-09 19:02:17', '2018-03-09 19:02:40'),
(273, 'Update Unit', 137, 'en', 0, 1, '2018-03-09 19:04:46', '2018-03-09 19:05:07'),
(274, 'আপডেট ইউনিট', 137, 'bn', 0, 1, '2018-03-09 19:04:46', '2018-03-09 19:05:07'),
(275, 'Edit Unit', 138, 'en', 0, 1, '2018-03-09 19:05:18', '2018-03-09 19:05:36'),
(276, 'ইউনিট সম্পাদনা করুন', 138, 'bn', 0, 1, '2018-03-09 19:05:18', '2018-03-09 19:05:37'),
(277, 'Name', 139, 'en', 0, 1, '2018-03-09 19:09:56', '2018-03-09 19:10:13'),
(278, 'নাম', 139, 'bn', 0, 1, '2018-03-09 19:09:56', '2018-03-09 19:10:13'),
(279, 'Add Vat Tax', 140, 'en', 0, 1, '2018-03-09 19:11:03', '2018-03-09 19:11:22'),
(280, 'ভ্যাট ট্যাক্স যোগ করুন', 140, 'bn', 0, 1, '2018-03-09 19:11:03', '2018-03-09 19:11:22'),
(281, 'Select Product', 141, 'en', 0, 1, '2018-03-09 19:13:30', '2018-03-09 19:20:25'),
(282, 'পণ্য নির্বাচন করুন', 141, 'bn', 0, 1, '2018-03-09 19:13:30', '2018-03-09 19:20:25'),
(283, 'Report', 142, 'en', 0, 1, '2018-03-09 19:18:16', '2018-03-09 19:18:36'),
(284, 'প্রতিবেদন', 142, 'bn', 0, 1, '2018-03-09 19:18:16', '2018-03-09 19:18:36'),
(285, 'Available Quantity', 143, 'en', 0, 1, '2018-03-09 19:24:36', '2018-03-09 19:25:10'),
(286, 'উপলব্ধ পরিমাণ', 143, 'bn', 0, 1, '2018-03-09 19:24:36', '2018-03-09 19:25:10'),
(287, 'Sale Quantity', 144, 'en', 0, 1, '2018-03-09 19:25:47', '2018-03-09 19:26:05'),
(288, 'বিক্রয় পরিমাণ', 144, 'bn', 0, 1, '2018-03-09 19:25:47', '2018-03-09 19:26:05'),
(289, 'Total Quantity', 145, 'en', 0, 1, '2018-03-09 19:26:25', '2018-03-09 19:26:44'),
(290, 'মোট পরিমাণ', 145, 'bn', 0, 1, '2018-03-09 19:26:25', '2018-03-09 19:26:44'),
(291, 'Select Invoice', 146, 'en', 0, 1, '2018-03-09 19:44:45', '2018-03-09 19:45:42'),
(292, 'চালান নির্বাচন করুন', 146, 'bn', 0, 1, '2018-03-09 19:44:45', '2018-03-09 19:45:42'),
(293, 'Search date....', 147, 'en', 0, 1, '2018-03-09 19:45:57', '2018-03-09 19:46:17'),
(294, 'তারিখ অনুসন্ধান করুন ....', 147, 'bn', 0, 1, '2018-03-09 19:45:57', '2018-03-09 19:46:17'),
(295, 'Date', 148, 'en', 0, 1, '2018-03-09 19:47:32', '2018-03-09 19:47:48'),
(296, 'তারিখ', 148, 'bn', 0, 1, '2018-03-09 19:47:32', '2018-03-09 19:47:48'),
(297, 'Challan No', 149, 'en', 0, 1, '2018-03-09 19:48:38', '2018-03-09 19:49:45'),
(298, 'চালান নং', 149, 'bn', 0, 1, '2018-03-09 19:48:38', '2018-03-09 19:49:45'),
(299, 'Quantity/Kg', 150, 'en', 0, 1, '2018-03-09 19:50:42', '2018-03-09 19:50:58'),
(300, 'পরিমাণ / কেজি', 150, 'bn', 0, 1, '2018-03-09 19:50:42', '2018-03-09 19:50:58'),
(301, 'Unit Price/Kg', 151, 'en', 0, 1, '2018-03-09 19:51:26', '2018-03-09 19:51:44'),
(302, 'ইউনিট মূল্য / কেজি', 151, 'bn', 0, 1, '2018-03-09 19:51:26', '2018-03-09 19:51:45'),
(303, 'Total Up to Date Amount', 152, 'en', 0, 1, '2018-03-09 19:52:14', '2018-03-09 19:54:53'),
(304, 'সর্বমোট পরিমাণ', 152, 'bn', 0, 1, '2018-03-09 19:52:14', '2018-03-09 19:54:53'),
(305, 'User List', 153, 'en', 0, 1, '2018-03-11 17:00:41', '2018-03-11 17:01:04'),
(306, 'ব্যবহারকারীর তালিকা', 153, 'bn', 0, 1, '2018-03-11 17:00:41', '2018-03-11 17:01:04'),
(307, 'Local purchase', 154, 'en', 0, 1, '2018-03-21 01:37:13', '2018-07-16 06:39:15'),
(308, 'স্থানীয় ক্রয়', 154, 'bn', 0, 1, '2018-03-21 01:37:13', '2018-07-16 06:39:15'),
(309, 'L/C Purchase', 155, 'en', 0, 1, '2018-03-21 01:54:39', '2018-07-16 06:41:10'),
(310, 'এল / সি ক্রয়', 155, 'bn', 0, 1, '2018-03-21 01:54:39', '2018-07-16 06:41:10'),
(311, 'ACCOUNTS HEAD', 156, 'en', 0, 1, '2018-04-03 23:20:02', '2018-04-03 23:20:42'),
(312, 'অ্যাকাউন্টের প্রধান', 156, 'bn', 0, 1, '2018-04-03 23:20:02', '2018-04-03 23:20:42'),
(313, 'Head name/type', 157, 'en', 0, 1, '2018-04-03 23:21:10', '2018-04-03 23:22:21'),
(314, 'প্রধান নাম / টাইপ', 157, 'bn', 0, 1, '2018-04-03 23:21:10', '2018-04-03 23:22:21'),
(315, 'Sub head name/type', 158, 'en', 0, 1, '2018-04-03 23:22:39', '2018-04-03 23:23:17'),
(316, 'উপ প্রধান নাম / টাইপ', 158, 'bn', 0, 1, '2018-04-03 23:22:39', '2018-04-03 23:23:17'),
(317, 'Account Class', 159, 'en', 0, 1, '2018-04-05 01:48:07', '2018-04-05 01:48:43'),
(318, 'অ্যাকাউন্ট ক্লাস', 159, 'bn', 0, 1, '2018-04-05 01:48:07', '2018-04-05 01:48:43'),
(319, 'Account Sub Class', 160, 'en', 0, 1, '2018-04-05 05:42:59', '2018-04-05 05:43:38'),
(320, 'অ্যাকাউন্ট সাব ক্লাস', 160, 'bn', 0, 1, '2018-04-05 05:42:59', '2018-04-05 05:43:38'),
(321, 'Chart of Accounts', 161, 'en', 0, 1, '2018-04-05 05:43:59', '2018-04-05 05:44:38'),
(322, 'অ্যাকাউন্ট চার্ট', 161, 'bn', 0, 1, '2018-04-05 05:43:59', '2018-04-05 05:44:38'),
(323, 'Accounts Head', 162, 'en', 0, 1, '2018-04-10 01:44:36', '2018-04-10 01:44:56'),
(324, NULL, 162, 'bn', 0, 1, '2018-04-10 01:44:36', '2018-04-10 01:44:56'),
(325, 'Accounts Journal', 163, 'en', 0, 1, '2018-04-20 03:28:16', '2018-04-20 03:28:39'),
(326, NULL, 163, 'bn', 0, 1, '2018-04-20 03:28:16', '2018-04-20 03:28:39'),
(327, 'Credit/Withdraw', 164, 'en', 0, 1, '2018-04-25 02:01:30', '2018-04-25 02:02:29'),
(328, NULL, 164, 'bn', 0, 1, '2018-04-25 02:01:31', '2018-04-25 02:02:29'),
(329, 'Debit/Deposit', 165, 'en', 0, 1, '2018-04-25 02:01:43', '2018-04-25 02:01:58'),
(330, NULL, 165, 'bn', 0, 1, '2018-04-25 02:01:43', '2018-04-25 02:01:58'),
(331, 'JOURNAL', 166, 'en', 0, 1, '2018-05-22 00:18:40', '2018-07-16 06:34:55'),
(332, 'দৈনন্দিন জমাখরচের খাতা', 166, 'bn', 0, 1, '2018-05-22 00:18:40', '2018-07-16 06:34:55'),
(333, 'Trial Balance', 167, 'en', 0, 1, '2018-05-22 00:19:45', '2018-05-22 00:20:20'),
(334, NULL, 167, 'bn', 0, 1, '2018-05-22 00:19:45', '2018-05-22 00:20:20'),
(335, 'LEDGER', 168, 'en', 0, 1, '2018-05-22 00:26:46', '2018-07-16 06:36:38'),
(336, 'খতিয়ান', 168, 'bn', 0, 1, '2018-05-22 00:26:46', '2018-07-16 06:36:38'),
(337, 'Party Ledger', 169, 'en', 0, 1, '2018-05-22 00:27:17', '2018-07-16 06:43:08'),
(338, 'পার্টি লেজার', 169, 'bn', 0, 1, '2018-05-22 00:27:17', '2018-07-16 06:43:08'),
(339, 'General Ledger', 170, 'en', 0, 1, '2018-05-22 00:27:51', '2018-07-16 06:43:29'),
(340, 'সাধারণ লেজার', 170, 'bn', 0, 1, '2018-05-22 00:27:51', '2018-07-16 06:43:29'),
(341, 'Add Journal Posting', 171, 'en', 0, 1, '2018-05-22 00:34:39', '2018-05-22 00:34:55'),
(342, NULL, 171, 'bn', 0, 1, '2018-05-22 00:34:39', '2018-05-22 00:34:55'),
(343, 'Date', 172, 'en', 0, 1, '2018-05-22 00:36:21', '2018-07-16 06:51:59'),
(344, 'তারিখ', 172, 'bn', 0, 1, '2018-05-22 00:36:21', '2018-07-16 06:51:59'),
(345, 'Particulars', 173, 'en', 0, 1, '2018-05-22 00:36:44', '2018-05-22 00:37:00'),
(346, NULL, 173, 'bn', 0, 1, '2018-05-22 00:36:44', '2018-05-22 00:37:00'),
(347, 'Details', 174, 'en', 0, 1, '2018-05-22 00:38:27', '2018-05-22 00:38:39'),
(348, NULL, 174, 'bn', 0, 1, '2018-05-22 00:38:27', '2018-05-22 00:38:39'),
(349, 'Account Head List', 175, 'en', 0, 1, '2018-05-22 00:39:27', '2018-05-22 00:39:44'),
(350, NULL, 175, 'bn', 0, 1, '2018-05-22 00:39:27', '2018-05-22 00:39:44'),
(351, 'Account Sub_Head List', 176, 'en', 0, 1, '2018-05-22 00:40:40', '2018-05-22 00:41:03'),
(352, NULL, 176, 'bn', 0, 1, '2018-05-22 00:40:40', '2018-05-22 00:41:03'),
(353, 'Account Class List', 177, 'en', 0, 1, '2018-05-22 00:45:01', '2018-05-22 00:45:16'),
(354, NULL, 177, 'bn', 0, 1, '2018-05-22 00:45:01', '2018-05-22 00:45:16'),
(355, 'Account Sub-Class List', 178, 'en', 0, 1, '2018-05-22 00:45:47', '2018-05-22 00:46:07'),
(356, NULL, 178, 'bn', 0, 1, '2018-05-22 00:45:47', '2018-05-22 00:46:07'),
(357, 'Chart of Account List', 179, 'en', 0, 1, '2018-05-22 00:46:30', '2018-07-16 07:19:53'),
(358, 'অ্যাকাউন্ট তালিকা চার্ট', 179, 'bn', 0, 1, '2018-05-22 00:46:30', '2018-07-16 07:19:53'),
(359, 'Search', 180, 'en', 0, 1, '2018-05-22 00:47:32', '2018-05-22 00:47:42'),
(360, NULL, 180, 'bn', 0, 1, '2018-05-22 00:47:32', '2018-05-22 00:47:42'),
(361, 'Sales Order', 181, 'en', 0, 1, '2018-05-22 01:01:52', '2018-07-16 06:40:25'),
(362, 'বিক্রয় আদেশ', 181, 'bn', 0, 1, '2018-05-22 01:01:52', '2018-07-16 06:40:26'),
(363, 'L/C purchase List', 182, 'en', 0, 1, '2018-05-22 01:02:45', '2018-07-16 06:42:04'),
(364, 'এল / সি ক্রয় তালিকা', 182, 'bn', 0, 1, '2018-05-22 01:02:45', '2018-07-16 06:42:04'),
(365, 'TRIAL BALANCE', 183, 'en', 0, 1, '2018-06-06 00:27:17', '2018-07-16 06:37:22'),
(366, 'ট্রায়াল ব্যালান্স', 183, 'bn', 0, 1, '2018-06-06 00:27:17', '2018-07-16 06:37:22'),
(367, 'Unit list', 184, 'en', 0, 1, '2018-07-16 06:46:04', '2018-07-16 06:46:26'),
(368, 'ইউনিট তালিকা', 184, 'bn', 0, 1, '2018-07-16 06:46:04', '2018-07-16 06:46:27'),
(369, 'Client', 185, 'en', 0, 1, '2018-07-16 06:49:14', '2018-07-16 06:51:09'),
(370, 'খরিদ্দার', 185, 'bn', 0, 1, '2018-07-16 06:49:14', '2018-07-16 06:51:09'),
(371, 'Select Client', 186, 'en', 0, 1, '2018-07-16 06:53:19', '2018-07-16 06:54:02'),
(372, 'ক্লায়েন্ট নির্বাচন করুন', 186, 'bn', 0, 1, '2018-07-16 06:53:19', '2018-07-16 06:54:02'),
(373, 'Select Group', 187, 'en', 0, 1, '2018-07-16 06:55:14', '2018-07-16 06:55:34'),
(374, 'গ্রুপ নির্বাচন করুন', 187, 'bn', 0, 1, '2018-07-16 06:55:14', '2018-07-16 06:55:35'),
(375, 'Select Account', 188, 'en', 0, 1, '2018-07-16 06:57:42', '2018-07-16 06:58:02'),
(376, 'নির্বাচন অ্যাকাউন্ট', 188, 'bn', 0, 1, '2018-07-16 06:57:43', '2018-07-16 06:58:02'),
(377, 'Vat Tax', 189, 'en', 0, 1, '2018-07-16 07:00:32', '2018-07-16 07:01:39'),
(378, 'মুল্য সংযোজন কর', 189, 'bn', 0, 1, '2018-07-16 07:00:32', '2018-07-16 07:01:39'),
(379, 'Total Price', 190, 'en', 0, 1, '2018-07-16 07:03:32', '2018-07-16 07:04:29'),
(380, 'মোট দাম', 190, 'bn', 0, 1, '2018-07-16 07:03:32', '2018-07-16 07:04:29'),
(381, 'Bonus', 191, 'en', 0, 1, '2018-07-16 07:05:00', '2018-07-16 07:05:13'),
(382, 'বোনাস', 191, 'bn', 0, 1, '2018-07-16 07:05:00', '2018-07-16 07:05:13'),
(383, 'Paid amount', 192, 'en', 0, 1, '2018-07-16 07:05:55', '2018-07-16 07:06:10'),
(384, 'দেওয়া পরিমাণ', 192, 'bn', 0, 1, '2018-07-16 07:05:55', '2018-07-16 07:06:10'),
(385, 'Amount to pay', 193, 'en', 0, 1, '2018-07-16 07:06:51', '2018-07-16 07:07:10'),
(386, 'পরিশোধ করার পরিমাণ', 193, 'bn', 0, 1, '2018-07-16 07:06:51', '2018-07-16 07:07:10'),
(387, 'Add row', 194, 'en', 0, 1, '2018-07-16 07:07:57', '2018-07-16 07:08:14'),
(388, 'সারি যোগ করুন', 194, 'bn', 0, 1, '2018-07-16 07:07:57', '2018-07-16 07:08:14'),
(389, 'Amount', 195, 'en', 0, 1, '2018-07-16 07:13:38', '2018-07-16 07:13:52'),
(390, 'পরিমাণ', 195, 'bn', 0, 1, '2018-07-16 07:13:38', '2018-07-16 07:13:52'),
(391, 'Final Amount', 196, 'en', 0, 1, '2018-07-16 07:14:20', '2018-07-16 07:14:32'),
(392, 'চূড়ান্ত পরিমাণ', 196, 'bn', 0, 1, '2018-07-16 07:14:20', '2018-07-16 07:14:33'),
(393, 'price', 197, 'en', 0, 1, '2018-08-13 00:08:42', '2018-08-13 00:08:53'),
(394, NULL, 197, 'bn', 0, 1, '2018-08-13 00:08:42', '2018-08-13 00:08:53'),
(395, 'print', 198, 'en', 0, 1, '2018-08-13 02:15:48', '2018-08-13 02:15:57'),
(396, NULL, 198, 'bn', 0, 1, '2018-08-13 02:15:48', '2018-08-13 02:15:57'),
(397, 'Challan', 199, 'en', 0, 1, '2018-08-13 03:48:56', '2018-08-13 03:49:12'),
(398, 'Challan', 199, 'bn', 0, 1, '2018-08-13 03:48:56', '2018-08-13 03:49:12'),
(399, 'Sales Return', 200, 'en', 0, 1, '2018-08-17 06:56:27', '2018-08-17 06:56:39'),
(400, NULL, 200, 'bn', 0, 1, '2018-08-17 06:56:27', '2018-08-17 06:56:39');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_translation_keys`
--

CREATE TABLE `mxp_translation_keys` (
  `translation_key_id` int(10) UNSIGNED NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `translation_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_translation_keys`
--

INSERT INTO `mxp_translation_keys` (`translation_key_id`, `is_active`, `created_at`, `updated_at`, `translation_key`) VALUES
(1, 1, '2018-03-05 18:12:49', '2018-03-05 18:12:49', 'company_name'),
(2, 1, '2018-03-05 20:38:51', '2018-03-05 20:38:51', 'login_label'),
(3, 1, '2018-03-05 20:39:27', '2018-03-05 20:39:27', 'register_label'),
(4, 1, '2018-03-05 20:54:56', '2018-03-05 20:54:56', 'validationerror_woops'),
(5, 1, '2018-03-05 20:56:52', '2018-03-05 20:56:52', 'validationerror_there_were_some_problems_with_your_input'),
(6, 1, '2018-03-05 20:57:04', '2018-03-05 20:57:04', 'validationerror_or_you_are_not_active_yet'),
(7, 1, '2018-03-05 20:57:14', '2018-03-05 20:57:14', 'enter_email_address'),
(8, 1, '2018-03-05 20:57:22', '2018-03-05 20:57:22', 'enter_password'),
(9, 1, '2018-03-05 20:57:31', '2018-03-05 20:57:31', 'login_rememberme_label'),
(10, 1, '2018-03-05 20:57:39', '2018-03-05 20:57:39', 'forgot_your_password'),
(11, 1, '2018-03-05 23:23:50', '2018-03-05 23:23:50', 'dashboard_label'),
(12, 1, '2018-03-05 23:34:35', '2018-03-05 23:34:35', 'language_list_label'),
(13, 1, '2018-03-05 23:36:43', '2018-03-05 23:36:43', 'serial_no_label'),
(14, 1, '2018-03-05 23:38:13', '2018-03-05 23:38:13', 'language_title_label'),
(15, 1, '2018-03-05 23:38:47', '2018-03-05 23:38:47', 'language_code_label'),
(16, 1, '2018-03-05 23:39:23', '2018-03-05 23:39:23', 'status_label'),
(17, 1, '2018-03-05 23:40:40', '2018-03-05 23:40:40', 'action_label'),
(18, 1, '2018-03-05 23:43:00', '2018-03-05 23:43:00', 'action_active_label'),
(19, 1, '2018-03-05 23:43:47', '2018-03-05 23:43:47', 'action_inactive_label'),
(20, 1, '2018-03-05 23:58:03', '2018-03-05 23:58:03', 'add_locale_button'),
(21, 1, '2018-03-06 00:00:03', '2018-03-06 00:00:03', 'edit_button'),
(22, 1, '2018-03-06 00:14:26', '2018-03-06 00:14:26', 'add_new_language_label'),
(23, 1, '2018-03-06 00:15:45', '2018-03-06 00:15:45', 'add_language_label'),
(24, 1, '2018-03-06 00:16:49', '2018-03-06 00:16:49', 'enter_language_title'),
(25, 1, '2018-03-06 00:17:31', '2018-03-06 00:17:31', 'enter_language_code'),
(26, 1, '2018-03-06 00:18:57', '2018-03-06 00:18:57', 'save_button'),
(27, 1, '2018-03-06 00:23:12', '2018-03-06 00:23:12', 'update_locale_label'),
(28, 1, '2018-03-06 00:28:35', '2018-03-06 00:28:35', 'update_language_title'),
(29, 1, '2018-03-06 00:29:32', '2018-03-06 00:29:32', 'update_language_code'),
(30, 1, '2018-03-06 00:30:07', '2018-03-06 00:30:07', 'update_button'),
(31, 1, '2018-03-06 00:32:05', '2018-03-06 00:32:05', 'update_language_label'),
(32, 1, '2018-03-06 00:34:41', '2018-03-06 00:34:41', 'mxp_upload_file_rechecking_label'),
(33, 1, '2018-03-06 00:36:42', '2018-03-06 00:36:42', 'upload_button'),
(34, 1, '2018-03-06 00:39:26', '2018-03-06 00:39:26', 'translation_list_label'),
(35, 1, '2018-03-06 00:49:29', '2018-03-06 00:49:29', 'add_new_key_label'),
(36, 1, '2018-03-06 00:51:16', '2018-03-06 00:51:16', 'search_the_translation_key_placeholder'),
(37, 1, '2018-03-06 00:52:45', '2018-03-06 00:52:45', 'translation_key_label'),
(38, 1, '2018-03-06 00:54:31', '2018-03-06 00:54:31', 'translation_label'),
(39, 1, '2018-03-06 00:55:21', '2018-03-06 00:55:21', 'language_label'),
(40, 1, '2018-03-06 00:56:29', '2018-03-06 00:56:29', 'delete_button'),
(41, 1, '2018-03-06 01:07:29', '2018-03-06 01:07:29', 'add_new_translation_key_label'),
(42, 1, '2018-03-06 01:08:20', '2018-03-06 01:08:20', 'enter_translation_key'),
(43, 1, '2018-03-06 01:18:54', '2018-03-06 01:18:54', 'update_translation_label'),
(44, 1, '2018-03-06 01:19:50', '2018-03-06 01:19:50', 'update_translation_key_label'),
(45, 1, '2018-03-06 19:21:58', '2018-03-06 19:21:58', 'mxp_menu_language'),
(46, 1, '2018-03-06 19:23:15', '2018-03-06 19:23:15', 'mxp_menu_manage_langulage'),
(47, 1, '2018-03-06 19:24:37', '2018-03-06 19:24:37', 'mxp_menu_manage_translation'),
(48, 1, '2018-03-06 19:25:41', '2018-03-06 19:25:41', 'mxp_menu_upload_language_file'),
(49, 1, '2018-03-06 19:26:59', '2018-03-06 19:26:59', 'mxp_menu_role'),
(50, 1, '2018-03-06 19:28:03', '2018-03-06 19:28:03', 'mxp_menu_add_new_role'),
(51, 1, '2018-03-06 19:30:11', '2018-03-06 19:30:11', 'mxp_menu_role_list'),
(52, 1, '2018-03-06 19:30:45', '2018-03-06 19:30:45', 'mxp_menu_role_permission_'),
(53, 1, '2018-03-06 19:31:22', '2018-03-06 19:31:22', 'mxp_menu_settings'),
(54, 1, '2018-03-06 19:32:15', '2018-03-06 19:32:15', 'mxp_menu_open_company_acc'),
(55, 1, '2018-03-06 19:34:19', '2018-03-06 19:34:19', 'mxp_menu_company_list'),
(56, 1, '2018-03-06 19:34:56', '2018-03-06 19:34:56', 'mxp_menu_open_company_account'),
(57, 1, '2018-03-06 19:36:15', '2018-03-06 19:36:15', 'mxp_menu_create_user'),
(58, 1, '2018-03-06 19:39:56', '2018-03-06 19:39:56', 'mxp_menu_user_list'),
(59, 1, '2018-03-06 19:40:33', '2018-03-06 19:40:33', 'mxp_menu_client_list'),
(60, 1, '2018-03-06 19:41:56', '2018-03-06 19:41:56', 'mxp_menu_product'),
(61, 1, '2018-03-06 19:42:32', '2018-03-06 19:42:32', 'mxp_menu_unit'),
(62, 1, '2018-03-06 19:48:24', '2018-03-06 19:48:24', 'mxp_menu_product_group'),
(63, 1, '2018-03-06 19:49:03', '2018-03-06 19:49:03', 'mxp_menu_product_entry'),
(64, 1, '2018-03-06 19:50:09', '2018-03-06 19:50:09', 'mxp_menu_product_packing'),
(65, 1, '2018-03-06 19:50:54', '2018-03-06 19:50:54', 'mxp_menu_purchase'),
(66, 1, '2018-03-06 19:51:47', '2018-03-06 19:51:47', 'mxp_menu_purchase_list'),
(67, 1, '2018-03-06 19:52:27', '2018-03-06 19:52:27', 'mxp_menu_update_stocks_action'),
(68, 1, '2018-03-06 19:53:48', '2018-03-06 19:53:48', 'mxp_menu_vat_tax_list'),
(69, 1, '2018-03-06 19:54:25', '2018-03-06 19:54:25', 'mxp_menu_sale_list'),
(70, 1, '2018-03-06 19:55:15', '2018-03-06 19:55:15', 'mxp_menu_save_sale_'),
(71, 1, '2018-03-06 19:56:45', '2018-03-06 19:56:45', 'mxp_menu_inventory_report'),
(72, 1, '2018-03-06 19:57:21', '2018-03-06 19:57:21', 'mxp_menu_stock_management'),
(73, 1, '2018-03-06 19:58:01', '2018-03-06 19:58:01', 'mxp_menu_store'),
(74, 1, '2018-03-06 19:58:53', '2018-03-06 19:58:53', 'mxp_menu_stock'),
(76, 1, '2018-03-06 20:57:06', '2018-03-06 20:57:06', 'company_name_label'),
(77, 1, '2018-03-06 21:05:38', '2018-03-06 21:05:38', 'role_name_placeholder'),
(78, 1, '2018-03-06 21:06:59', '2018-03-06 21:06:59', 'select_company_option_label'),
(79, 1, '2018-03-06 21:08:51', '2018-03-06 21:08:51', 'select_role_option_label'),
(80, 1, '2018-03-06 21:11:57', '2018-03-06 21:11:57', 'select_all_label'),
(81, 1, '2018-03-06 21:12:36', '2018-03-06 21:12:36', 'unselect_all_label'),
(82, 1, '2018-03-06 21:14:03', '2018-03-06 21:14:03', 'set_button'),
(83, 1, '2018-03-06 21:15:41', '2018-03-06 21:15:41', 'heading_role_assign_label'),
(84, 1, '2018-03-06 21:19:23', '2018-03-06 21:19:23', 'heading_role_permission_list_label'),
(85, 1, '2018-03-06 21:19:57', '2018-03-06 21:19:57', 'option_permitted_route_list_label'),
(86, 1, '2018-03-06 21:36:58', '2018-03-06 21:36:58', 'heading_update_role_label'),
(87, 1, '2018-03-06 22:00:58', '2018-03-06 22:00:58', 'heading_add_stock_label'),
(88, 1, '2018-03-06 22:01:41', '2018-03-06 22:01:41', 'product_name_label'),
(89, 1, '2018-03-06 22:02:40', '2018-03-06 22:02:40', 'product_group_label'),
(90, 1, '2018-03-06 22:03:37', '2018-03-06 22:03:37', 'quantity_label'),
(91, 1, '2018-03-06 22:04:43', '2018-03-06 22:04:43', 'option_select_location_label'),
(94, 1, '2018-03-06 22:21:41', '2018-03-06 22:21:41', 'heading_add_new_stock_label'),
(95, 1, '2018-03-06 22:22:14', '2018-03-06 22:22:14', 'add_stock_label'),
(96, 1, '2018-03-06 22:23:21', '2018-03-06 22:23:21', 'enter_store_name_label'),
(97, 1, '2018-03-06 22:23:51', '2018-03-06 22:23:51', 'enter_store_location_label'),
(98, 1, '2018-03-06 22:27:47', '2018-03-06 22:27:47', 'heading_update_store_label'),
(100, 1, '2018-03-06 22:34:46', '2018-03-06 22:34:46', 'heading_store_list_label'),
(101, 1, '2018-03-06 22:36:32', '2018-03-06 22:36:32', 'store_name_label'),
(102, 1, '2018-03-06 22:37:36', '2018-03-06 22:37:36', 'store_location_label'),
(103, 1, '2018-03-06 22:45:51', '2018-03-06 22:45:51', 'list_of_responsible_person_label'),
(104, 1, '2018-03-07 21:50:23', '2018-03-07 21:50:23', 'company_phone_number_label'),
(105, 1, '2018-03-07 21:51:29', '2018-03-07 21:51:29', 'company_address_label'),
(106, 1, '2018-03-07 21:52:22', '2018-03-07 21:52:22', 'company_description_label'),
(107, 1, '2018-03-07 23:00:57', '2018-03-07 23:00:57', 'employee_name_label'),
(108, 1, '2018-03-07 23:02:33', '2018-03-07 23:02:33', 'personal_phone_number_label'),
(109, 1, '2018-03-07 23:03:16', '2018-03-07 23:03:16', 'employee_address_label'),
(110, 1, '2018-03-07 23:03:52', '2018-03-07 23:03:52', 'password_confirmation_label'),
(111, 1, '2018-03-07 23:11:42', '2018-03-07 23:11:42', 'search_placeholder'),
(112, 1, '2018-03-07 23:21:05', '2018-03-07 23:21:05', 'company_label'),
(113, 1, '2018-03-07 23:52:58', '2018-03-07 23:52:58', 'add_company_label'),
(114, 1, '2018-03-08 17:19:08', '2018-03-08 17:19:08', 'update_company_button'),
(115, 1, '2018-03-09 17:02:10', '2018-03-09 17:02:10', 'add_packet_button'),
(116, 1, '2018-03-09 17:04:20', '2018-03-09 17:04:20', 'option_select_unit_label'),
(117, 1, '2018-03-09 17:06:17', '2018-03-09 17:06:17', 'packet_name_label'),
(118, 1, '2018-03-09 17:07:27', '2018-03-09 17:07:27', 'unit_quantity_label'),
(119, 1, '2018-03-09 17:13:41', '2018-03-09 17:13:41', 'update_packet_button'),
(120, 1, '2018-03-09 17:18:32', '2018-03-09 17:18:32', 'unit_label'),
(121, 1, '2018-03-09 17:24:19', '2018-03-09 17:24:19', 'heading_packet_list'),
(122, 1, '2018-03-09 17:52:50', '2018-03-09 17:52:50', 'heading_add_new_packet_label'),
(123, 1, '2018-03-09 17:53:19', '2018-03-09 17:53:19', 'add_packet_label'),
(124, 1, '2018-03-09 17:56:43', '2018-03-09 17:56:43', 'packet_details_label'),
(125, 1, '2018-03-09 18:02:50', '2018-03-09 18:02:50', 'product_code_label'),
(126, 1, '2018-03-09 18:09:32', '2018-03-09 18:09:32', 'heading_update_product_label'),
(127, 1, '2018-03-09 18:10:38', '2018-03-09 18:10:38', 'edit_product_label'),
(128, 1, '2018-03-09 18:26:17', '2018-03-09 18:26:17', 'product_group_name_label'),
(129, 1, '2018-03-09 18:26:52', '2018-03-09 18:26:52', 'add_product_group_label'),
(130, 1, '2018-03-09 18:27:22', '2018-03-09 18:27:22', 'add_new_product_group_label'),
(131, 1, '2018-03-09 18:34:53', '2018-03-09 18:34:53', 'edit_new_product_group_label'),
(132, 1, '2018-03-09 18:35:57', '2018-03-09 18:35:57', 'edit_product_group_label'),
(133, 1, '2018-03-09 18:39:48', '2018-03-09 18:39:48', 'heading_product_group_list_label'),
(134, 1, '2018-03-09 19:00:04', '2018-03-09 19:00:04', 'unit_name_label'),
(135, 1, '2018-03-09 19:00:51', '2018-03-09 19:00:51', 'add_unit_label'),
(136, 1, '2018-03-09 19:02:17', '2018-03-09 19:02:17', 'add_new_unit_label'),
(137, 1, '2018-03-09 19:04:46', '2018-03-09 19:04:46', 'update_unit_label'),
(138, 1, '2018-03-09 19:05:17', '2018-03-09 19:05:17', 'edit_unit_label'),
(139, 1, '2018-03-09 19:09:55', '2018-03-09 19:09:55', 'name_label'),
(140, 1, '2018-03-09 19:11:03', '2018-03-09 19:11:03', 'add_vat_tax_label'),
(141, 1, '2018-03-09 19:13:30', '2018-03-09 19:13:30', 'option_select_product_label'),
(142, 1, '2018-03-09 19:18:16', '2018-03-09 19:18:16', 'heading_report_label'),
(143, 1, '2018-03-09 19:24:36', '2018-03-09 19:24:36', 'available_quantity_label'),
(144, 1, '2018-03-09 19:25:47', '2018-03-09 19:25:47', 'sale_quantity_label'),
(145, 1, '2018-03-09 19:26:25', '2018-03-09 19:26:25', 'total_quantity_label'),
(146, 1, '2018-03-09 19:44:45', '2018-03-09 19:44:45', 'option_select_invoice_label'),
(147, 1, '2018-03-09 19:45:57', '2018-03-09 19:45:57', 'search_date_placeholder'),
(148, 1, '2018-03-09 19:47:32', '2018-03-09 19:47:32', 'date_label'),
(149, 1, '2018-03-09 19:48:38', '2018-03-09 19:48:38', 'invoice_no_label'),
(150, 1, '2018-03-09 19:50:42', '2018-03-09 19:50:42', 'quantity_per_kg_label'),
(151, 1, '2018-03-09 19:51:26', '2018-03-09 19:51:26', 'unit_price_per_kg_label'),
(152, 1, '2018-03-09 19:52:14', '2018-03-09 19:52:14', 'total_uptodate_quantity_label'),
(153, 1, '2018-03-11 17:00:41', '2018-03-11 17:00:41', 'heading_user_list_label'),
(154, 1, '2018-03-21 01:37:13', '2018-03-21 01:37:13', 'mxp_menu_local_purchase'),
(155, 1, '2018-03-21 01:54:39', '2018-03-21 01:54:39', 'mxp_menu_lc_purchase'),
(156, 1, '2018-04-03 23:20:02', '2018-04-03 23:20:02', 'mxp_menu_accounts_head'),
(157, 1, '2018-04-03 23:21:10', '2018-04-03 23:21:10', 'mxp_menu_head_name/type'),
(158, 1, '2018-04-03 23:22:39', '2018-04-03 23:22:39', 'mxp_menu_accounts_sub_head'),
(159, 1, '2018-04-05 01:48:07', '2018-04-05 01:48:07', 'mxp_menu_account_class'),
(160, 1, '2018-04-05 05:42:59', '2018-04-05 05:42:59', 'mxp_menu_account_sub_class_list'),
(161, 1, '2018-04-05 05:43:59', '2018-04-05 05:43:59', 'mxp_menu_chart_of_account_list'),
(162, 1, '2018-04-10 01:44:36', '2018-04-10 01:44:36', 'mxp_heading_accounts_head_list_label'),
(163, 1, '2018-04-20 03:28:15', '2018-04-20 03:28:15', 'mxp_menu_accounts'),
(164, 1, '2018-04-25 02:01:30', '2018-04-25 02:01:30', 'mxp_credit'),
(165, 1, '2018-04-25 02:01:43', '2018-04-25 02:01:43', 'mxp_debit'),
(166, 1, '2018-05-22 00:18:40', '2018-05-22 00:18:40', 'mxp_menu_journal'),
(167, 1, '2018-05-22 00:19:45', '2018-05-22 00:19:45', 'mxp_menu_trail_balance'),
(168, 1, '2018-05-22 00:26:46', '2018-05-22 00:26:46', 'mxp_menu_ledger'),
(169, 1, '2018-05-22 00:27:17', '2018-05-22 00:27:17', 'mxp_menu_party_ledger'),
(170, 1, '2018-05-22 00:27:50', '2018-05-22 00:27:50', 'mxp_menu_general_ledger'),
(171, 1, '2018-05-22 00:34:39', '2018-05-22 00:34:39', 'add_journal_posting_label'),
(172, 1, '2018-05-22 00:36:21', '2018-05-22 00:36:21', 'mxp_date'),
(173, 1, '2018-05-22 00:36:44', '2018-05-22 00:36:44', 'mxp_particulars'),
(174, 1, '2018-05-22 00:38:27', '2018-05-22 00:38:27', 'view_details_button'),
(175, 1, '2018-05-22 00:39:27', '2018-05-22 00:39:27', 'heading_accounts_head_list_label'),
(176, 1, '2018-05-22 00:40:40', '2018-05-22 00:40:40', 'heading_sub_accounts_head_list_label'),
(177, 1, '2018-05-22 00:45:01', '2018-05-22 00:45:01', 'heading_acc_clas_list_label'),
(178, 1, '2018-05-22 00:45:46', '2018-05-22 00:45:46', 'heading_acc_sub_clas_list_label'),
(179, 1, '2018-05-22 00:46:30', '2018-05-22 00:46:30', 'heading_chart_of_acc_list_label'),
(180, 1, '2018-05-22 00:47:32', '2018-05-22 00:47:32', 'search_btn'),
(181, 1, '2018-05-22 01:01:52', '2018-05-22 01:01:52', 'mxp_menu_save_sale'),
(182, 1, '2018-05-22 01:02:45', '2018-05-22 01:02:45', 'mxp_menu_l/c_purchase_list'),
(183, 1, '2018-06-06 00:27:17', '2018-06-06 00:27:17', 'mxp_menu_trial_balance'),
(184, 1, '2018-07-16 06:46:03', '2018-07-16 06:46:03', 'heading_unit_list_label'),
(185, 1, '2018-07-16 06:49:14', '2018-07-16 06:49:14', 'client_label'),
(186, 1, '2018-07-16 06:53:19', '2018-07-16 06:53:19', 'select_client_option'),
(187, 1, '2018-07-16 06:55:13', '2018-07-16 06:55:13', 'mxp_select_product_group'),
(188, 1, '2018-07-16 06:57:42', '2018-07-16 06:57:42', 'select_account_option'),
(189, 1, '2018-07-16 07:00:32', '2018-07-16 07:00:32', 'vat_tax_label'),
(190, 1, '2018-07-16 07:03:32', '2018-07-16 07:03:32', 'total_price_label'),
(191, 1, '2018-07-16 07:05:00', '2018-07-16 07:05:00', 'bonus_label'),
(192, 1, '2018-07-16 07:05:55', '2018-07-16 07:05:55', 'paid_amount_label'),
(193, 1, '2018-07-16 07:06:51', '2018-07-16 07:06:51', 'amount_to_pay_label'),
(194, 1, '2018-07-16 07:07:57', '2018-07-16 07:07:57', 'add_row_button'),
(195, 1, '2018-07-16 07:13:38', '2018-07-16 07:13:38', 'amount_label'),
(196, 1, '2018-07-16 07:14:20', '2018-07-16 07:14:20', 'final_amount_label'),
(197, 1, '2018-08-13 00:08:42', '2018-08-13 00:08:42', 'price'),
(198, 1, '2018-08-13 02:15:48', '2018-08-13 02:15:48', 'mxp_print_btn'),
(199, 1, '2018-08-13 03:48:56', '2018-08-13 03:48:56', 'mxp_menu_chalan'),
(200, 1, '2018-08-17 06:56:27', '2018-08-17 06:56:27', 'mxp_menu_sales_return');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_transports`
--

CREATE TABLE `mxp_transports` (
  `id` int(10) UNSIGNED NOT NULL,
  `transport_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transport_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transport_date` date NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_transports`
--

INSERT INTO `mxp_transports` (`id`, `transport_name`, `transport_number`, `transport_date`, `invoice_id`, `product_id`, `company_id`, `group_id`, `user_id`, `is_deleted`, `created_at`, `updated_at`) VALUES
(104, 'bus', '1123', '2018-08-17', 155, 7, 10, 1, 53, 0, '2018-08-17 03:02:44', '2018-08-17 03:02:44'),
(105, 'truck', '5589', '2018-08-18', 157, 8, 10, 1, 53, 0, '2018-08-17 07:43:30', '2018-08-17 07:43:30'),
(106, 'truck', '009', '2018-08-17', 158, 7, 10, 1, 53, 0, '2018-08-17 07:46:39', '2018-08-17 07:46:39'),
(107, 'truck', '009', '2018-08-17', 159, 8, 10, 1, 53, 0, '2018-08-17 07:46:40', '2018-08-17 07:46:40'),
(108, 'bus', '1123', '2018-08-18', 160, 7, 10, 1, 53, 0, '2018-08-18 06:26:30', '2018-08-18 06:26:30'),
(109, 'bus', '1123', '2018-08-18', 161, 7, 10, 1, 53, 0, '2018-08-18 06:34:09', '2018-08-18 06:34:09'),
(110, 'bus', '1123', '2018-08-18', 162, 7, 10, 1, 53, 0, '2018-08-18 07:09:06', '2018-08-18 07:09:06'),
(111, 'bus', '1123', '2018-08-18', 163, 7, 10, 1, 53, 0, '2018-08-18 07:17:47', '2018-08-18 07:17:47');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_unit`
--

CREATE TABLE `mxp_unit` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `com_group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mxp_unit`
--

INSERT INTO `mxp_unit` (`id`, `name`, `company_id`, `com_group_id`, `user_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'kg', 0, 1, 1, 1, 0, '2018-07-16 07:25:22', '2018-07-16 01:20:51'),
(2, 'kg', 10, 1, 53, 1, 0, '2018-08-03 05:59:52', '2018-08-03 05:59:52');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_users`
--

CREATE TABLE `mxp_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` int(100) NOT NULL DEFAULT '0',
  `company_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `user_role_id` int(11) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_users`
--

INSERT INTO `mxp_users` (`user_id`, `first_name`, `middle_name`, `last_name`, `address`, `type`, `group_id`, `company_id`, `email`, `password`, `phone_no`, `remember_token`, `is_active`, `user_role_id`, `verified`, `verification_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'middle', 'last', NULL, 'super_admin', 0, 0, 'sajibg7@gmail.com', '$2y$10$BIvmvrQf1a5G3mrmHlrN9ulYV1fKtgUoJaK968BJ2foPBTkVjWn7S', '123456789', 'icryribYBdDveo8F39tQ427i2Ta5YGGpE7NdJVyE3RTZuLdBhpXfTBK6LpJQ', 1, 1, 0, '0', '2018-01-15 01:37:15', '2018-03-05 13:29:32'),
(24, 'Beximco user', 'moinul', 'sajibg', NULL, 'super_admin', 0, 0, 'sajibg7+1@gmail.com', '$2y$10$voCXiMsv.R.X.pl6F8DbnuFyIiwyhrpYB.na/FITZNz7ZIGyLVmfC', '01674898148', 'jkR84EzKMu5BVHM6paKj3asoctGTOccLcXPgz3RVo7UeBLjpbRDxnbvSMFRl', 1, 1, 0, '0', '2018-01-29 06:36:28', '2018-01-29 06:36:28'),
(26, 'company-a-user', NULL, NULL, NULL, 'company_user', 1, 11, 'sajibg7+3@gmail.com', '$2y$10$gxTBxp.V1v2TJphLkJWmLuqIwhdNu0WxUZSDgnkNyc0D/.YnhCkc2', '12143234235', 'TezCzw56wUjAeVkvits9Zkaj5ZVLjRNYAauQDTh0DmT6AdtSiXY5Qs8CcGPu', 1, 21, 0, '0', '2018-01-29 06:44:07', '2018-01-29 06:44:07'),
(27, 'Sumit Power user', 'moinul', 'sajibg', NULL, 'super_admin', 0, 0, 'sajibg7+4@gmail.com', '$2y$10$DYvlonHYz7onBx3U743LoeSQX166D4Y.EFxJDI33WfbUFuHvvUrZ.', '01674898148', 'kcraPAbsogfCaWXXzizdBCRSYOIqrplPy77x3qrT', 0, 1, 0, '0', '2018-01-30 00:16:13', '2018-01-30 00:16:13'),
(36, 'Sumit Power user-2', 'moinul', 'sajibg', NULL, 'super_admin', 0, 0, 'sajibg7+5@gmail.com', '$2y$10$9PUEtsR3rv82eJ7TFyG/wOEuTtbXUbcTJWZ0Wz1EBFRnNLqzHROje', '01674898148', 'kcraPAbsogfCaWXXzizdBCRSYOIqrplPy77x3qrT', 0, 1, 0, '0', '2018-01-30 00:32:37', '2018-01-30 00:32:37'),
(37, 'mxp_name', NULL, NULL, NULL, 'company_user', 1, 12, 'sajibg7+22@gmail.com', '$2y$10$78GIJiurKLaYeuPBToOsmuxOvNHOOrXmufM4FHOFvo.K73jXhYVp2', '2222222222', 'rSJefxmGV3RGxRmhWK2uAYbDXRA5Uad1esPuaG2SgefPJHIL7tFz7XUWylvl', 1, 24, 0, '0', '2018-01-31 02:53:59', '2018-01-31 02:53:59'),
(38, 'Sumit Power user-22', 'moinul', 'sajibg', NULL, 'super_admin', 0, 0, 'sajibg7+23@gmail.com', '$2y$10$0.jZXV4ihdxJKIqI3STDb.4QB3.fd2szjsQLUCeijhVXSyuzQw0gy', '01674898148', 'DORz0nqgyRNUEPWahczArNAlVYTil0mFXMniff6BAaVmMLjO2sywBn0BvHS5', 1, 1, 0, '0', '2018-01-31 02:56:31', '2018-01-31 02:56:31'),
(39, 'mxp_name', NULL, NULL, NULL, 'company_user', 38, 13, 'sajibg7+77@gmail.com', '$2y$10$O4ZTP39xhT2NtkYcAE1I1u3ZVfn/CA4PC5954PJVYP92yQ1e3oJSG', '2222222222', 'zJ9Fq0pgJp1Ffo1AljnyQS2IHKDKgD59zDokr5ufo7wzNjjNAG5zHgX2w9kw', 1, 25, 0, '0', '2018-01-31 03:00:36', '2018-01-31 03:00:36'),
(40, 'mxp_name', NULL, NULL, NULL, 'company_user', 38, 14, 'sajibg7+78@gmail.com', '$2y$10$/RIWK3dmNz5i0RO6p.b8h.fIgPVOukwUUVdydW4zuqjDYZgnuFT3y', '2222222222', 'CMIeb4F5GnV3Gvzeq6n7FvUwdCN8DM1NPoEwkVaHyLwYPSnc7U2P52xLfX1R', 1, 26, 0, '0', '2018-01-31 03:00:53', '2018-01-31 03:00:53'),
(42, 'New Admin', 'Middle', 'Last', NULL, 'super_admin', 0, 0, 'newadmin@mail.com', '$2y$10$x1yzwN3LXrb8fkXSCg9Roeu.EBlSQpJf1U.ouqzdOi1F5z2robRd2', '1234567890', 'I500mFPOncDcawx0KwHnzx35J0rH1TUOIT6m4omT', 1, 1, 0, '0', '2018-02-09 01:58:04', '2018-02-09 01:58:04'),
(43, 'New Client', NULL, NULL, NULL, 'client_com', 42, 16, 'newclient@mail.com', NULL, '1234567890', NULL, 1, NULL, 0, '0', '2018-02-09 02:09:35', '2018-02-09 02:09:35'),
(49, 'hasib', NULL, NULL, NULL, 'company_user', 1, 12, 'hasib@mail.com', '$2y$10$qTZqM0EP0O.d4w30VZSN1e3vPnAHvYUD12c/n93nJLO.bUFN4NkLG', '024584510', NULL, 1, 27, 0, '0', '2018-07-16 03:38:10', '2018-07-16 03:38:10'),
(50, 'test admin', 'admin', 'sajib', NULL, 'super_admin', 0, 0, 'sajibg7+12@gmail.com', '$2y$10$uGor7GWgihdix6aHATLE7O5oO8heGmyxpZVE.Z4XFHbfl8V24g352', '01674898148', 'FFlQ84GaPb7GhBCrJ6P8cSHLWCcp7EFP5dTGhIT8', 0, 1, 0, '0', '2018-07-16 23:12:26', '2018-07-16 23:12:26'),
(51, 'Muzibur rahman', NULL, NULL, NULL, 'company_user', 1, 17, 'sajibg7+98@gmail.com', '$2y$10$5WAM1Qi/UtAoUPbrUw994exWk6F6bBIoalELoKTbW84sF0Yj98swK', '1112457896', 'hTfG8iSnUJTBtIRA45BYbIjBx6tofuCBJMt6DRoaST7VDpfowHtxoXMkt4w1', 1, 28, 0, '0', '2018-07-16 23:16:58', '2018-07-16 23:16:58'),
(52, 'Super Admin', NULL, NULL, NULL, 'company_user', 1, 0, 'superadmin@mail.com', '$2y$10$O37o4nMqfRcAmj1g.h.nOeBiymVed9Q.5SO.wH.HuyRsYvGFVz0bO', '01754237812', 'XHyX3tgXYM2E90jrWR7iruZHvLYgoZsZeccCGFEN1ltCNux6pMmvB3eUv3eh', 1, 29, 0, '0', '2018-07-16 23:29:01', '2018-07-16 23:29:01'),
(53, 'Muzibur rahman', NULL, NULL, NULL, 'company_user', 1, 10, 'astralassociatesbd@gmail.com', '$2y$10$9OyEhSmnbNJWSboJcBbuVOa6590v3xD3fla.ex3QSPbdviUhyplj6', '01671871964', '9vhHLPZtxe5NgihsAbo1rzpkrGTzq2dgZnlOeJClM7bct8cxx5yugsQE3CN4', 1, 29, 0, '0', '2018-08-03 01:01:01', '2018-08-03 01:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `mxp_user_role_menu`
--

CREATE TABLE `mxp_user_role_menu` (
  `role_menu_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mxp_user_role_menu`
--

INSERT INTO `mxp_user_role_menu` (`role_menu_id`, `role_id`, `menu_id`, `company_id`, `is_active`, `created_at`, `updated_at`) VALUES
(185, 1, 25, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(186, 1, 7, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(187, 1, 34, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(188, 1, 28, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(189, 1, 19, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(190, 1, 37, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(191, 1, 18, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(192, 1, 4, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(193, 1, 31, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(194, 1, 23, 0, 1, '2018-01-26 18:24:42', '2018-01-26 18:24:42'),
(195, 1, 3, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(196, 1, 24, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(197, 1, 27, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(198, 1, 36, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(199, 1, 35, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(200, 1, 13, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(201, 1, 30, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(202, 1, 6, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(203, 1, 10, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(204, 1, 16, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(205, 1, 9, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(206, 1, 8, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(207, 1, 12, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(208, 1, 5, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(209, 1, 26, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(210, 1, 11, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(211, 1, 29, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(212, 1, 22, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(213, 1, 33, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(214, 1, 21, 0, 1, '2018-01-26 18:24:43', '2018-01-26 18:24:43'),
(313, 21, 4, 0, 1, '2018-01-28 18:42:45', '2018-01-28 18:42:45'),
(314, 21, 31, 0, 1, '2018-01-28 18:42:46', '2018-01-28 18:42:46'),
(315, 21, 3, 0, 1, '2018-01-28 18:42:46', '2018-01-28 18:42:46'),
(316, 21, 24, 0, 1, '2018-01-28 18:42:46', '2018-01-28 18:42:46'),
(317, 21, 27, 0, 1, '2018-01-28 18:42:46', '2018-01-28 18:42:46'),
(318, 21, 5, 0, 1, '2018-01-28 18:42:46', '2018-01-28 18:42:46'),
(319, 21, 32, 0, 1, '2018-01-28 18:42:46', '2018-01-28 18:42:46'),
(349, 26, 34, 14, 1, '2018-01-30 15:00:07', '2018-01-30 15:00:07'),
(350, 26, 13, 14, 1, '2018-01-30 15:00:07', '2018-01-30 15:00:07'),
(351, 26, 6, 14, 1, '2018-01-30 15:00:07', '2018-01-30 15:00:07'),
(352, 26, 10, 14, 1, '2018-01-30 15:00:07', '2018-01-30 15:00:07'),
(353, 26, 16, 14, 1, '2018-01-30 15:00:08', '2018-01-30 15:00:08'),
(354, 26, 9, 14, 1, '2018-01-30 15:00:08', '2018-01-30 15:00:08'),
(355, 26, 8, 14, 1, '2018-01-30 15:00:08', '2018-01-30 15:00:08'),
(356, 26, 12, 14, 1, '2018-01-30 15:00:08', '2018-01-30 15:00:08'),
(357, 26, 11, 14, 1, '2018-01-30 15:00:08', '2018-01-30 15:00:08'),
(358, 25, 19, 13, 1, '2018-01-30 16:23:24', '2018-01-30 16:23:24'),
(359, 25, 37, 13, 1, '2018-01-30 16:23:24', '2018-01-30 16:23:24'),
(360, 25, 18, 13, 1, '2018-01-30 16:23:24', '2018-01-30 16:23:24'),
(361, 25, 5, 13, 1, '2018-01-30 16:23:24', '2018-01-30 16:23:24'),
(362, 25, 22, 13, 1, '2018-01-30 16:23:24', '2018-01-30 16:23:24'),
(363, 25, 33, 13, 1, '2018-01-30 16:23:25', '2018-01-30 16:23:25'),
(364, 25, 21, 13, 1, '2018-01-30 16:23:25', '2018-01-30 16:23:25'),
(365, 25, 20, 13, 1, '2018-01-30 16:23:25', '2018-01-30 16:23:25'),
(366, 1, 32, 0, 1, NULL, NULL),
(367, 1, 20, 0, 1, '2018-01-30 16:23:25', '2018-01-30 16:23:25'),
(401, 1, 38, 0, 1, NULL, NULL),
(402, 1, 39, 0, 1, NULL, NULL),
(403, 1, 40, 0, 1, NULL, NULL),
(404, 1, 41, 0, 1, NULL, NULL),
(405, 1, 42, 0, 1, NULL, NULL),
(406, 1, 43, 0, 1, NULL, NULL),
(407, 1, 44, 0, 1, NULL, NULL),
(414, 1, 52, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(415, 1, 53, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(416, 1, 54, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(417, 1, 55, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(418, 1, 56, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(419, 1, 54, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(420, 1, 57, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(421, 1, 58, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(422, 1, 59, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(423, 1, 60, 0, 1, '2018-01-31 12:00:00', '2018-01-31 12:00:00'),
(424, 1, 61, 0, 1, NULL, NULL),
(425, 1, 62, 0, 1, NULL, NULL),
(426, 1, 63, 0, 1, NULL, NULL),
(427, 1, 64, 0, 1, NULL, NULL),
(428, 1, 65, 0, 1, NULL, NULL),
(429, 1, 66, 0, 1, NULL, NULL),
(430, 1, 67, 0, 1, NULL, NULL),
(431, 1, 68, 0, 1, NULL, NULL),
(432, 1, 69, 0, 1, NULL, NULL),
(433, 1, 70, 0, 1, NULL, NULL),
(434, 1, 71, 0, 1, NULL, NULL),
(435, 1, 72, 0, 1, NULL, NULL),
(482, 1, 73, 0, 1, NULL, NULL),
(486, 1, 77, 0, 1, NULL, NULL),
(487, 1, 78, 0, 1, NULL, NULL),
(488, 1, 79, 0, 1, NULL, NULL),
(489, 1, 80, 0, 1, NULL, NULL),
(490, 1, 81, 0, 1, NULL, NULL),
(491, 1, 82, 0, 1, NULL, NULL),
(492, 1, 83, 0, 1, NULL, NULL),
(493, 1, 88, 0, 1, NULL, NULL),
(494, 1, 89, 0, 1, NULL, NULL),
(495, 1, 90, 0, 1, NULL, NULL),
(496, 1, 91, 0, 1, NULL, NULL),
(497, 1, 92, 0, 1, NULL, NULL),
(498, 1, 84, 0, 1, NULL, NULL),
(499, 1, 93, 0, 1, NULL, NULL),
(500, 1, 94, 0, 1, NULL, NULL),
(501, 1, 95, 0, 1, NULL, NULL),
(502, 1, 96, 0, 1, NULL, NULL),
(503, 1, 97, 0, 1, NULL, NULL),
(504, 1, 98, 0, 1, NULL, NULL),
(505, 1, 99, 0, 1, NULL, NULL),
(506, 1, 100, 0, 1, NULL, NULL),
(507, 1, 101, 0, 1, NULL, NULL),
(508, 1, 102, 0, 1, NULL, NULL),
(509, 27, 102, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(510, 27, 98, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(511, 27, 43, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(512, 27, 25, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(513, 27, 57, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(514, 27, 90, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(515, 27, 67, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(516, 27, 56, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(517, 27, 54, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(518, 27, 53, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(519, 27, 44, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(520, 27, 41, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(521, 27, 40, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(522, 27, 89, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(523, 27, 68, 10, 1, '2018-04-02 06:40:56', '2018-04-02 06:40:56'),
(524, 27, 71, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(525, 27, 70, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(526, 27, 28, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(527, 27, 19, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(528, 27, 72, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(529, 27, 69, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(530, 27, 18, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(531, 27, 4, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(532, 27, 58, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(533, 27, 66, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(534, 27, 31, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(535, 27, 63, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(536, 27, 91, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(537, 27, 99, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(538, 27, 101, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(539, 27, 3, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(540, 27, 100, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(541, 27, 73, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(542, 27, 24, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(543, 27, 27, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(544, 27, 30, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(545, 27, 38, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(546, 27, 74, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(547, 27, 76, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(548, 27, 52, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(549, 27, 42, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(550, 27, 55, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(551, 27, 75, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(552, 27, 92, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(553, 27, 6, 10, 1, '2018-04-02 06:40:57', '2018-04-02 06:40:57'),
(554, 27, 8, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(555, 27, 93, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(556, 27, 5, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(557, 27, 83, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(558, 27, 79, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(559, 27, 82, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(560, 27, 81, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(561, 27, 96, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(562, 27, 97, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(563, 27, 84, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(564, 27, 77, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(565, 27, 78, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(566, 27, 80, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(567, 27, 26, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(568, 27, 60, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(569, 27, 65, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(570, 27, 95, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(571, 27, 29, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(572, 27, 62, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(573, 27, 33, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(574, 27, 39, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(575, 27, 64, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(576, 27, 94, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(577, 27, 61, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(578, 27, 32, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(579, 27, 20, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(580, 27, 59, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(581, 27, 88, 10, 1, '2018-04-02 06:40:58', '2018-04-02 06:40:58'),
(655, 24, 102, 12, 1, '2018-04-02 06:50:54', '2018-04-02 06:50:54'),
(656, 24, 98, 12, 1, '2018-04-02 06:50:54', '2018-04-02 06:50:54'),
(657, 24, 43, 12, 1, '2018-04-02 06:50:54', '2018-04-02 06:50:54'),
(658, 24, 25, 12, 1, '2018-04-02 06:50:54', '2018-04-02 06:50:54'),
(659, 24, 57, 12, 1, '2018-04-02 06:50:54', '2018-04-02 06:50:54'),
(660, 24, 90, 12, 1, '2018-04-02 06:50:54', '2018-04-02 06:50:54'),
(661, 24, 67, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(662, 24, 56, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(663, 24, 54, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(664, 24, 53, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(665, 24, 44, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(666, 24, 41, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(667, 24, 40, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(668, 24, 89, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(669, 24, 68, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(670, 24, 71, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(671, 24, 70, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(672, 24, 28, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(673, 24, 19, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(674, 24, 72, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(675, 24, 69, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(676, 24, 18, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(677, 24, 4, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(678, 24, 58, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(679, 24, 66, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(680, 24, 31, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(681, 24, 63, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(682, 24, 91, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(683, 24, 99, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(684, 24, 101, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(685, 24, 3, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(686, 24, 100, 12, 1, '2018-04-02 06:50:55', '2018-04-02 06:50:55'),
(687, 24, 73, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(688, 24, 24, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(689, 24, 27, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(690, 24, 30, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(691, 24, 38, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(692, 24, 74, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(693, 24, 76, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(694, 24, 52, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(695, 24, 42, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(696, 24, 55, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(697, 24, 75, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(698, 24, 92, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(699, 24, 6, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(700, 24, 8, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(701, 24, 93, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(702, 24, 5, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(703, 24, 83, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(704, 24, 79, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(705, 24, 82, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(706, 24, 81, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(707, 24, 96, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(708, 24, 97, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(709, 24, 84, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(710, 24, 77, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(711, 24, 78, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(712, 24, 80, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(713, 24, 26, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(714, 24, 60, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(715, 24, 65, 12, 1, '2018-04-02 06:50:56', '2018-04-02 06:50:56'),
(716, 24, 95, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(717, 24, 29, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(718, 24, 62, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(719, 24, 33, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(720, 24, 39, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(721, 24, 64, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(722, 24, 94, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(723, 24, 61, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(724, 24, 32, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(725, 24, 20, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(726, 24, 59, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(727, 24, 88, 12, 1, '2018-04-02 06:50:57', '2018-04-02 06:50:57'),
(801, 1, 103, 0, 1, NULL, NULL),
(802, 1, 104, 0, 1, NULL, NULL),
(805, 1, 105, 0, 1, NULL, NULL),
(806, 1, 105, 0, 1, NULL, NULL),
(807, 1, 105, 0, 1, NULL, NULL),
(808, 1, 105, 0, 1, NULL, NULL),
(809, 1, 105, 0, 1, NULL, NULL),
(810, 1, 105, 0, 1, NULL, NULL),
(811, 1, 106, 0, 1, NULL, NULL),
(812, 1, 107, 0, 1, NULL, NULL),
(813, 1, 108, 0, 1, NULL, NULL),
(814, 1, 109, 0, 1, NULL, NULL),
(815, 1, 110, 0, 1, NULL, NULL),
(816, 1, 111, 0, 1, NULL, NULL),
(817, 1, 112, 0, 1, NULL, NULL),
(818, 1, 113, 0, 1, NULL, NULL),
(819, 1, 114, 0, 1, NULL, NULL),
(820, 1, 115, 0, 1, NULL, NULL),
(821, 1, 116, 0, 1, NULL, NULL),
(822, 1, 116, 0, 1, NULL, NULL),
(823, 1, 118, 0, 1, NULL, NULL),
(824, 1, 119, 0, 1, NULL, NULL),
(825, 1, 120, 0, 1, NULL, NULL),
(826, 1, 121, 0, 1, NULL, NULL),
(827, 1, 122, 0, 1, NULL, NULL),
(828, 1, 123, 0, 1, NULL, NULL),
(829, 1, 124, 0, 1, NULL, NULL),
(830, 1, 125, 0, 1, NULL, NULL),
(831, 1, 126, 0, 1, NULL, NULL),
(832, 1, 127, 0, 1, NULL, NULL),
(833, 1, 128, 0, 1, NULL, NULL),
(834, 1, 129, 0, 1, NULL, NULL),
(835, 1, 130, 0, 1, NULL, NULL),
(836, 1, 131, 0, 1, NULL, NULL),
(837, 1, 132, 0, 1, NULL, NULL),
(838, 1, 133, 0, 1, NULL, NULL),
(839, 1, 134, 0, 1, NULL, NULL),
(840, 1, 135, 0, 1, NULL, NULL),
(841, 1, 136, 0, 1, NULL, NULL),
(842, 1, 137, 0, 1, NULL, NULL),
(843, 1, 138, 0, 1, NULL, NULL),
(844, 1, 139, 0, 1, NULL, NULL),
(845, 1, 140, 0, 1, NULL, NULL),
(846, 1, 141, 0, 1, NULL, NULL),
(847, 1, 142, 0, 1, NULL, NULL),
(848, 1, 143, 0, 1, NULL, NULL),
(867, 1, 162, 0, 1, NULL, NULL),
(868, 1, 163, 0, 1, NULL, NULL),
(1065, 28, 98, 17, 1, '2018-07-16 23:15:44', '2018-07-16 23:15:44'),
(1066, 28, 102, 17, 1, '2018-07-16 23:15:44', '2018-07-16 23:15:44'),
(1067, 28, 43, 17, 1, '2018-07-16 23:15:44', '2018-07-16 23:15:44'),
(1068, 28, 25, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1069, 28, 57, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1070, 28, 90, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1071, 28, 121, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1072, 28, 123, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1073, 28, 122, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1074, 28, 126, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1075, 28, 125, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1076, 28, 124, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1077, 28, 129, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1078, 28, 128, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1079, 28, 132, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1080, 28, 127, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1081, 28, 131, 17, 1, '2018-07-16 23:15:45', '2018-07-16 23:15:45'),
(1082, 28, 130, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1083, 28, 106, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1084, 28, 108, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1085, 28, 112, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1086, 28, 111, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1087, 28, 67, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1088, 28, 109, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1089, 28, 56, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1090, 28, 54, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1091, 28, 53, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1092, 28, 44, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1093, 28, 117, 17, 1, '2018-07-16 23:15:46', '2018-07-16 23:15:46'),
(1094, 28, 116, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1095, 28, 41, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1096, 28, 40, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1097, 28, 89, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1098, 28, 68, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1099, 28, 71, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1100, 28, 70, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1101, 28, 28, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1102, 28, 19, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1103, 28, 135, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1104, 28, 134, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1105, 28, 138, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1106, 28, 133, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1107, 28, 137, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1108, 28, 136, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1109, 28, 72, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1110, 28, 69, 17, 1, '2018-07-16 23:15:47', '2018-07-16 23:15:47'),
(1111, 28, 18, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1112, 28, 4, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1113, 28, 58, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1114, 28, 66, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1115, 28, 31, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1116, 28, 63, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1117, 28, 91, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1118, 28, 115, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1119, 28, 120, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1120, 28, 114, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1121, 28, 113, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1122, 28, 119, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1123, 28, 118, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1124, 28, 143, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1125, 28, 141, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1126, 28, 107, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1127, 28, 99, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1128, 28, 103, 17, 1, '2018-07-16 23:15:48', '2018-07-16 23:15:48'),
(1129, 28, 104, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1130, 28, 110, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1131, 28, 101, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1132, 28, 3, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1133, 28, 100, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1134, 28, 139, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1135, 28, 105, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1136, 28, 73, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1137, 28, 24, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1138, 28, 27, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1139, 28, 142, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1140, 28, 30, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1141, 28, 38, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1142, 28, 74, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1143, 28, 76, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1144, 28, 140, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1145, 28, 52, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1146, 28, 42, 17, 1, '2018-07-16 23:15:49', '2018-07-16 23:15:49'),
(1147, 28, 55, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1148, 28, 75, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1149, 28, 92, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1150, 28, 6, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1151, 28, 8, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1152, 28, 93, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1153, 28, 5, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1154, 28, 83, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1155, 28, 79, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1156, 28, 82, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1157, 28, 81, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1158, 28, 96, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1159, 28, 97, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1160, 28, 84, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1161, 28, 77, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1162, 28, 78, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1163, 28, 80, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1164, 28, 163, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1165, 28, 162, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1166, 28, 26, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1167, 28, 60, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1168, 28, 65, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1169, 28, 95, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1170, 28, 29, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1171, 28, 62, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1172, 28, 33, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1173, 28, 39, 17, 1, '2018-07-16 23:15:50', '2018-07-16 23:15:50'),
(1174, 28, 64, 17, 1, '2018-07-16 23:15:51', '2018-07-16 23:15:51'),
(1175, 28, 94, 17, 1, '2018-07-16 23:15:51', '2018-07-16 23:15:51'),
(1176, 28, 61, 17, 1, '2018-07-16 23:15:51', '2018-07-16 23:15:51'),
(1177, 28, 32, 17, 1, '2018-07-16 23:15:51', '2018-07-16 23:15:51'),
(1178, 28, 20, 17, 1, '2018-07-16 23:15:51', '2018-07-16 23:15:51'),
(1179, 28, 59, 17, 1, '2018-07-16 23:15:51', '2018-07-16 23:15:51'),
(1180, 28, 88, 17, 1, '2018-07-16 23:15:51', '2018-07-16 23:15:51'),
(1297, 1, 165, 0, 1, '2018-07-25 18:00:00', '2018-07-25 18:00:00'),
(1298, 1, 164, 0, 1, '2018-07-25 18:00:00', '2018-07-25 18:00:00'),
(8086, 29, 102, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8087, 29, 98, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8088, 29, 43, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8089, 29, 57, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8090, 29, 90, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8091, 29, 121, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8092, 29, 123, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8093, 29, 122, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8094, 29, 126, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8095, 29, 125, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8096, 29, 124, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8097, 29, 129, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8098, 29, 128, 10, 1, '2018-08-18 02:06:34', '2018-08-18 02:06:34'),
(8099, 29, 132, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8100, 29, 127, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8101, 29, 131, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8102, 29, 130, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8103, 29, 106, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8104, 29, 108, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8105, 29, 112, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8106, 29, 111, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8107, 29, 67, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8108, 29, 109, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8109, 29, 56, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8110, 29, 54, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8111, 29, 53, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8112, 29, 44, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8113, 29, 117, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8114, 29, 116, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8115, 29, 41, 10, 1, '2018-08-18 02:06:35', '2018-08-18 02:06:35'),
(8116, 29, 40, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8117, 29, 89, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8118, 29, 68, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8119, 29, 71, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8120, 29, 70, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8121, 29, 28, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8122, 29, 19, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8123, 29, 165, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8124, 29, 164, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8125, 29, 135, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8126, 29, 134, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8127, 29, 138, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8128, 29, 133, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8129, 29, 137, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8130, 29, 136, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8131, 29, 72, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8132, 29, 69, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8133, 29, 18, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8134, 29, 4, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8135, 29, 58, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8136, 29, 66, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8137, 29, 31, 10, 1, '2018-08-18 02:06:36', '2018-08-18 02:06:36'),
(8138, 29, 63, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8139, 29, 91, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8140, 29, 115, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8141, 29, 120, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8142, 29, 114, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8143, 29, 113, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8144, 29, 119, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8145, 29, 118, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8146, 29, 143, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8147, 29, 141, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8148, 29, 107, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8149, 29, 99, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8150, 29, 103, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8151, 29, 104, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8152, 29, 110, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8153, 29, 101, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8154, 29, 100, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8155, 29, 139, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8156, 29, 105, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8157, 29, 24, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8158, 29, 27, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8159, 29, 142, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8160, 29, 30, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8161, 29, 38, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8162, 29, 74, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8163, 29, 76, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8164, 29, 140, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8165, 29, 52, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8166, 29, 42, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8167, 29, 55, 10, 1, '2018-08-18 02:06:37', '2018-08-18 02:06:37'),
(8168, 29, 75, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8169, 29, 6, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8170, 29, 8, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8171, 29, 93, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8172, 29, 5, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8173, 29, 83, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8174, 29, 79, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8175, 29, 82, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8176, 29, 81, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8177, 29, 96, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8178, 29, 181, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8179, 29, 180, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8180, 29, 97, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8181, 29, 84, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8182, 29, 77, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8183, 29, 78, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8184, 29, 80, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8185, 29, 162, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8186, 29, 163, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8187, 29, 26, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8188, 29, 60, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8189, 29, 65, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8190, 29, 95, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8191, 29, 29, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8192, 29, 62, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8193, 29, 33, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8194, 29, 39, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8195, 29, 64, 10, 1, '2018-08-18 02:06:38', '2018-08-18 02:06:38'),
(8196, 29, 94, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8197, 29, 61, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8198, 29, 20, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8199, 29, 59, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8200, 29, 88, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8201, 29, 173, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8202, 29, 172, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8203, 29, 167, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8204, 29, 177, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8205, 29, 178, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8206, 29, 175, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8207, 29, 168, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8208, 29, 170, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8209, 29, 171, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8210, 29, 169, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8211, 29, 182, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8212, 29, 166, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8213, 29, 176, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8214, 29, 174, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39'),
(8215, 29, 183, 10, 1, '2018-08-18 02:06:39', '2018-08-18 02:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_accounts_heads`
--
ALTER TABLE `mxp_accounts_heads`
  ADD PRIMARY KEY (`accounts_heads_id`);

--
-- Indexes for table `mxp_accounts_sub_heads`
--
ALTER TABLE `mxp_accounts_sub_heads`
  ADD PRIMARY KEY (`accounts_sub_heads_id`);

--
-- Indexes for table `mxp_acc_classes`
--
ALTER TABLE `mxp_acc_classes`
  ADD PRIMARY KEY (`mxp_acc_classes_id`),
  ADD KEY `mxp_acc_classes_accounts_heads_id_foreign` (`accounts_heads_id`),
  ADD KEY `mxp_acc_classes_accounts_sub_heads_id_foreign` (`accounts_sub_heads_id`);

--
-- Indexes for table `mxp_acc_head_sub_classes`
--
ALTER TABLE `mxp_acc_head_sub_classes`
  ADD PRIMARY KEY (`mxp_acc_head_sub_classes_id`),
  ADD KEY `mxp_acc_head_sub_classes_mxp_acc_classes_id_foreign` (`mxp_acc_classes_id`),
  ADD KEY `mxp_acc_head_sub_classes_accounts_heads_id_foreign` (`accounts_heads_id`),
  ADD KEY `mxp_acc_head_sub_classes_accounts_sub_heads_id_foreign` (`accounts_sub_heads_id`);

--
-- Indexes for table `mxp_chart_of_acc_heads`
--
ALTER TABLE `mxp_chart_of_acc_heads`
  ADD PRIMARY KEY (`chart_o_acc_head_id`),
  ADD KEY `mxp_chart_of_acc_heads_mxp_acc_head_sub_classes_id_foreign` (`mxp_acc_head_sub_classes_id`),
  ADD KEY `mxp_chart_of_acc_heads_mxp_acc_classes_id_foreign` (`mxp_acc_classes_id`),
  ADD KEY `mxp_chart_of_acc_heads_accounts_heads_id_foreign` (`accounts_heads_id`),
  ADD KEY `mxp_chart_of_acc_heads_accounts_sub_heads_id_foreign` (`accounts_sub_heads_id`);

--
-- Indexes for table `mxp_companies`
--
ALTER TABLE `mxp_companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_invoice`
--
ALTER TABLE `mxp_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_journal_posting`
--
ALTER TABLE `mxp_journal_posting`
  ADD PRIMARY KEY (`journal_posting_id`),
  ADD KEY `transaction_type_id` (`transaction_type_id`),
  ADD KEY `transaction_type_id_2` (`transaction_type_id`),
  ADD KEY `transaction_type_id_3` (`transaction_type_id`),
  ADD KEY `transaction_type_id_4` (`transaction_type_id`,`account_head_id`,`sales_man_id`,`reference_id`,`company_id`,`group_id`,`user_id`);

--
-- Indexes for table `mxp_languages`
--
ALTER TABLE `mxp_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_lc_purchases`
--
ALTER TABLE `mxp_lc_purchases`
  ADD PRIMARY KEY (`lc_purchase_id`);

--
-- Indexes for table `mxp_menu`
--
ALTER TABLE `mxp_menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `mxp_packet`
--
ALTER TABLE `mxp_packet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_product`
--
ALTER TABLE `mxp_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_product_group`
--
ALTER TABLE `mxp_product_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_product_purchase`
--
ALTER TABLE `mxp_product_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_role`
--
ALTER TABLE `mxp_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_sale_products`
--
ALTER TABLE `mxp_sale_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_stock`
--
ALTER TABLE `mxp_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_store`
--
ALTER TABLE `mxp_store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_taxvats`
--
ALTER TABLE `mxp_taxvats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_taxvat_cals`
--
ALTER TABLE `mxp_taxvat_cals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_transaction_type`
--
ALTER TABLE `mxp_transaction_type`
  ADD PRIMARY KEY (`transaction_type_id`);

--
-- Indexes for table `mxp_translations`
--
ALTER TABLE `mxp_translations`
  ADD PRIMARY KEY (`translation_id`);

--
-- Indexes for table `mxp_translation_keys`
--
ALTER TABLE `mxp_translation_keys`
  ADD PRIMARY KEY (`translation_key_id`);

--
-- Indexes for table `mxp_transports`
--
ALTER TABLE `mxp_transports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_unit`
--
ALTER TABLE `mxp_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mxp_users`
--
ALTER TABLE `mxp_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `mxp_user_role_menu`
--
ALTER TABLE `mxp_user_role_menu`
  ADD PRIMARY KEY (`role_menu_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
--
-- AUTO_INCREMENT for table `mxp_accounts_heads`
--
ALTER TABLE `mxp_accounts_heads`
  MODIFY `accounts_heads_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `mxp_accounts_sub_heads`
--
ALTER TABLE `mxp_accounts_sub_heads`
  MODIFY `accounts_sub_heads_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `mxp_acc_classes`
--
ALTER TABLE `mxp_acc_classes`
  MODIFY `mxp_acc_classes_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT for table `mxp_acc_head_sub_classes`
--
ALTER TABLE `mxp_acc_head_sub_classes`
  MODIFY `mxp_acc_head_sub_classes_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
--
-- AUTO_INCREMENT for table `mxp_chart_of_acc_heads`
--
ALTER TABLE `mxp_chart_of_acc_heads`
  MODIFY `chart_o_acc_head_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT for table `mxp_companies`
--
ALTER TABLE `mxp_companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `mxp_invoice`
--
ALTER TABLE `mxp_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;
--
-- AUTO_INCREMENT for table `mxp_journal_posting`
--
ALTER TABLE `mxp_journal_posting`
  MODIFY `journal_posting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=799;
--
-- AUTO_INCREMENT for table `mxp_languages`
--
ALTER TABLE `mxp_languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `mxp_lc_purchases`
--
ALTER TABLE `mxp_lc_purchases`
  MODIFY `lc_purchase_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `mxp_menu`
--
ALTER TABLE `mxp_menu`
  MODIFY `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;
--
-- AUTO_INCREMENT for table `mxp_packet`
--
ALTER TABLE `mxp_packet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `mxp_product`
--
ALTER TABLE `mxp_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `mxp_product_group`
--
ALTER TABLE `mxp_product_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `mxp_product_purchase`
--
ALTER TABLE `mxp_product_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mxp_role`
--
ALTER TABLE `mxp_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `mxp_sale_products`
--
ALTER TABLE `mxp_sale_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `mxp_stock`
--
ALTER TABLE `mxp_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `mxp_store`
--
ALTER TABLE `mxp_store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `mxp_taxvats`
--
ALTER TABLE `mxp_taxvats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `mxp_taxvat_cals`
--
ALTER TABLE `mxp_taxvat_cals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;
--
-- AUTO_INCREMENT for table `mxp_transaction_type`
--
ALTER TABLE `mxp_transaction_type`
  MODIFY `transaction_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `mxp_translations`
--
ALTER TABLE `mxp_translations`
  MODIFY `translation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=401;
--
-- AUTO_INCREMENT for table `mxp_translation_keys`
--
ALTER TABLE `mxp_translation_keys`
  MODIFY `translation_key_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
--
-- AUTO_INCREMENT for table `mxp_transports`
--
ALTER TABLE `mxp_transports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `mxp_unit`
--
ALTER TABLE `mxp_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `mxp_users`
--
ALTER TABLE `mxp_users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `mxp_user_role_menu`
--
ALTER TABLE `mxp_user_role_menu`
  MODIFY `role_menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8216;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `mxp_acc_classes`
--
ALTER TABLE `mxp_acc_classes`
  ADD CONSTRAINT `mxp_acc_classes_accounts_heads_id_foreign` FOREIGN KEY (`accounts_heads_id`) REFERENCES `mxp_accounts_heads` (`accounts_heads_id`),
  ADD CONSTRAINT `mxp_acc_classes_accounts_sub_heads_id_foreign` FOREIGN KEY (`accounts_sub_heads_id`) REFERENCES `mxp_accounts_sub_heads` (`accounts_sub_heads_id`);

--
-- Constraints for table `mxp_acc_head_sub_classes`
--
ALTER TABLE `mxp_acc_head_sub_classes`
  ADD CONSTRAINT `mxp_acc_head_sub_classes_accounts_heads_id_foreign` FOREIGN KEY (`accounts_heads_id`) REFERENCES `mxp_accounts_heads` (`accounts_heads_id`),
  ADD CONSTRAINT `mxp_acc_head_sub_classes_accounts_sub_heads_id_foreign` FOREIGN KEY (`accounts_sub_heads_id`) REFERENCES `mxp_accounts_sub_heads` (`accounts_sub_heads_id`),
  ADD CONSTRAINT `mxp_acc_head_sub_classes_mxp_acc_classes_id_foreign` FOREIGN KEY (`mxp_acc_classes_id`) REFERENCES `mxp_acc_classes` (`mxp_acc_classes_id`);

--
-- Constraints for table `mxp_chart_of_acc_heads`
--
ALTER TABLE `mxp_chart_of_acc_heads`
  ADD CONSTRAINT `mxp_chart_of_acc_heads_accounts_heads_id_foreign` FOREIGN KEY (`accounts_heads_id`) REFERENCES `mxp_accounts_heads` (`accounts_heads_id`),
  ADD CONSTRAINT `mxp_chart_of_acc_heads_accounts_sub_heads_id_foreign` FOREIGN KEY (`accounts_sub_heads_id`) REFERENCES `mxp_accounts_sub_heads` (`accounts_sub_heads_id`),
  ADD CONSTRAINT `mxp_chart_of_acc_heads_mxp_acc_classes_id_foreign` FOREIGN KEY (`mxp_acc_classes_id`) REFERENCES `mxp_acc_classes` (`mxp_acc_classes_id`),
  ADD CONSTRAINT `mxp_chart_of_acc_heads_mxp_acc_head_sub_classes_id_foreign` FOREIGN KEY (`mxp_acc_head_sub_classes_id`) REFERENCES `mxp_acc_head_sub_classes` (`mxp_acc_head_sub_classes_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
