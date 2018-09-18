<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAllStoreProInDescOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_acc_class");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_acc_class`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
SELECT mac.*,
mash.sub_head,
CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details

FROM mxp_acc_classes mac
inner join mxp_accounts_heads mah on(mac.accounts_heads_id = mah.accounts_heads_id)
inner join mxp_accounts_sub_heads mash on(mash.accounts_sub_heads_id = mac.accounts_sub_heads_id)

WHERE mac.group_id=grp_id
AND mac.company_id=comp_id
AND mac.is_deleted=0
order by mac.mxp_acc_classes_id desc 
limit startedAt,limits;");

        DB::unprepared("DROP procedure IF EXISTS get_all_accounts_sub_head");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_accounts_sub_head`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
SELECT mash.*,
CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details

FROM mxp_accounts_sub_heads mash
inner join mxp_accounts_heads mah on(mash.accounts_heads_id = mah.accounts_heads_id)

WHERE mash.group_id=grp_id
AND mash.company_id=comp_id
AND mash.is_deleted=0
order by mash.accounts_sub_heads_id desc 
limit startedAt,limits;");

        DB::unprepared("DROP procedure IF EXISTS get_all_acc_sub_class");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_acc_sub_class`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
SELECT mahsc.*,
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
limit startedAt,limits;");

        DB::unprepared("DROP procedure IF EXISTS get_all_chart_of_accounts");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_chart_of_accounts`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
SELECT mcoah.*,
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
limit startedAt,limits;");

        DB::unprepared("DROP procedure IF EXISTS get_all_lc_purchase_products");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_lc_purchase_products`(IN `grp_id` INT(11), IN `comp_id` INT(11))
SELECT mlp.*,
GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as product_details,
GROUP_CONCAT(mu.first_name,'(',mu.address,')') as client_details,
mi.invoice_code

FROM mxp_lc_purchases mlp
inner join mxp_product pr on(mlp.product_id = pr.id)
inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
inner join mxp_unit un ON(un.id=pk.unit_id)
inner join mxp_users mu on(mlp.client_id=mu.user_id)
inner join mxp_invoice mi on(mlp.invoice_id=mi.id)

WHERE mlp.com_group_id=grp_id
AND mlp.company_id=comp_id
AND mlp.is_deleted=0
GROUP BY mlp.lc_purchase_id
order by mlp.lc_purchase_id desc;");

        DB::unprepared("DROP procedure IF EXISTS get_all_packets");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_packets`(IN `com_id` INT(11), IN `group_id` INT(11))
SELECT pk.*, u.name as unit_name 
FROM mxp_packet pk 
inner JOIN mxp_unit u ON(pk.unit_id=u.id) 
WHERE pk.company_id=com_id 
AND pk.com_group_id=group_id 
AND pk.is_deleted=0 
AND pk.is_active=1
order by pk.id desc;");

        DB::unprepared("DROP procedure IF EXISTS get_all_product_purchase_info");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_product_purchase_info`(IN `grp_id` INT(11), IN `comp_id` INT(11))
SELECT 
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
ON mc.id = mpp.company_id

LEFT JOIN mxp_taxvats mt
ON mt.id = mtc.vat_tax_id

WHERE mpp.com_group_id = 1 AND mpp.company_id = 0 AND mpp.is_deleted = 0 AND mpp.is_active =1
GROUP BY mtc.purchase_id
order by mtc.purchase_id desc;");

        DB::unprepared("DROP procedure IF EXISTS get_all_products_of_stock");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_products_of_stock`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
select st.*, 
pp.price, mu.first_name as client_name, 
mu.address,
pg.name as product_group, 
pr.name as product_name, 
pk.name as packet_name,
pk.quantity as packet_quantity, 
u.name as unit_name, 
pk.unit_quantity, mu.email 

from mxp_stock st 
inner join mxp_product_purchase pp on(st.purchase_id = pp.id) 
inner join mxp_users mu on (pp.client_id = mu.user_id)
inner join mxp_product pr on(st.product_id = pr.id) 
inner JOIN mxp_packet pk ON(pr.packing_id=pk.id) 
inner JOIN mxp_product_group pg on ( pr.product_group_id=pg.id) 
inner join mxp_unit u ON(u.id=pk.unit_id)

WHERE st.com_group_id=grp_id 
AND st.company_id=comp_id 
AND st.status=1 
AND st.is_deleted=0
order by st.id desc
limit startedAt,limits;");

        DB::unprepared("DROP procedure IF EXISTS get_all_products");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_products`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
if(startedAt >= 0) then
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
 end if;");

        DB::unprepared("DROP procedure IF EXISTS get_all_sales_product");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_sales_product`(IN `grp_id` INT(11), IN `comp_id` INT(11),IN `startedAt` INT(11), IN `limits` INT(11))
SELECT 
mp.name as product_name,
mpk.name as packet_name,
mpk.quantity as packet_quantity,
munit.name as unit_name,
mpk.unit_quantity,
mu.first_name as client_name, 
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
LEFT JOIN mxp_users mu ON mu.user_id = msp.client_id
LEFT JOIN mxp_transports mt ON mt.invoice_id = msp.invoice_id
LEFT JOIN mxp_packet mpk ON mpk.id = mp.packing_id
LEFT JOIN mxp_unit munit ON munit.id = mpk.unit_id 
LEFT JOIN mxp_taxvat_cals mtvc ON mtvc.invoice_id = msp.invoice_id
LEFT JOIN mxp_taxvats mtv ON mtvc.vat_tax_id=mtv.id
WHERE msp.com_group_id=grp_id
AND msp.company_id=comp_id
AND msp.is_deleted=0
GROUP BY mtvc.sale_purchase_id
order by mtvc.sale_purchase_id desc
limit startedAt,limits;");

        DB::unprepared("DROP procedure IF EXISTS get_all_stocks");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_stocks`(IN `grp_id` INT, IN `comp_id` INT,IN `startedAt` INT(11), IN `limits` INT(11))
select pp.*, 
mpr.name as product_name, 
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

WHERE pp.com_group_id=grp_id 
AND pp.company_id=comp_id 
AND pp.is_active=1 
AND pp.is_deleted=0 
AND pp.stock_status=0 
order by pp.id desc
limit startedAt,limits;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
