<?php
final class BatchSQLQueryConstant {

	private function __construct() {

	}

	const SQL_INS_BATCH_DET = "insert into batch_details (batch_type_id,batch_status_id,batch_start_date_time) values (?,?,NOW())";

	const SQL_UPD_BATCH_DET = "update batch_details set batch_status_id = ?, batch_end_date_time=NOW() where batch_details_id = ?";

	const SQL_UPD_BATCH_CTRL = "update batch_ctrl set last_read_id=?,updated_date_time=NOW() where batch_type_id=?";

	const SQL_SEL_BATCH_TYPE = "select batch_type_id, batch_type_name, batch_size from batch_type where batch_type_id=? and is_active='Y'";

	const SQL_SEL_BATCH_RUN_STATUS = "select brs.status from batch_run_status brs, batch_type bt where brs.batch_type_id=bt.batch_type_id and brs.batch_type_id=? and bt.is_active=?";

	const SQL_SEL_BATCH_TYPE_run = "select bt.batch_type_id, bt.batch_type_name, bt.batch_size, bc.last_read_id from batch_type bt, batch_ctrl bc where bt.batch_type_id=? and bt.is_active='T' and bt.batch_type_id=bc.batch_type_id";

	const SQL_POST_NOTFICATION = "select requirement_expiration_date,notify_post_id, post_id, requirements, notification_email, notification_mobile from notify_post where notify_post_id > ? order by notify_post_id";
	const SQL_NOTFICATION = "select n.id as notification_id,n.emailaddress not_emailaddress,n.mobilenumber as not_mobilenumber,
			n.keywords,m.id as user_id,m.FirstName,m.LastName, m.EmailAddress as mem_eamiladdress, m.MobileNumber as mem_mobilenumber
			from notifications n, members m
			where n.createdby=m.id";

	const SQL_NOTFICATION_NEW = "select n.id as notification_id,n.emailaddress not_emailaddress,n.mobilenumber as not_mobilenumber,
			n.keywords from notifications n";

	const SQL_UPDATE_RUN_STATUS = "update batch_run_status set status=? where batch_type_id=?";

	const SQL_MEMBERS = "select id,mobilenumber,emailaddress from members where memberstatusid=?";

	const SQL_BID_NOTIFICATION = "select nb.notify_bid_id,b.id as bid_id, b.bidamount,b.description, p.id as post_id, p.title,
			m.id as post_user_id,m.mobilenumber as post_mobile,m.emailaddress as post_email, m.FirstName as postfname, m.LastName as postlname
			from notify_bid nb, bids b, posts p, members m
			where nb.notify_bid_id > ? and nb.bid_id=b.id and b.confirmon is null and b.postid=p.id and p.createdby=m.id ORDER BY nb.notify_bid_id ";

	const SQL_ALL_TAXONOMY = "select Keywords, Brand_Tag, Type_Tag, Gender_Tag, Age_Group_Tag, Services_Tag, Services_Name, Services_Type, synonym from taxonomy";

	const SQL_INS_NOTIFY_POST = "insert into notify_post (post_id,requirements,notification_email,notification_mobile,created_date_time,requirement_expiration_date) values (?,?,?,?,NOW(),?)";

	const SQL_INS_NOTIFY_POST_NON = "insert into notify_post_nonmatch (post_id,requirements,created_date_time) values (?,?,NOW())";

	const SQL_TAXONOMY_MATCH_GET = "select notify_post_match_id,post_id, requirements, requirement_expiration_date from notify_post_match where notify_post_match_id > ? order by notify_post_match_id";

	const SQL_TAXONOMY_NON_REQ = "select p.id as id, p.requirements as requirements,p.ExpireDateTime as ExpireDateTime, rmt.id as rmtid  from posts p,REQ_MAP_TAXONOMY rmt where rmt.id > ? and rmt.post_id=p.id order by rmt.id";

	const SQL_TAXONOMY_NON_NOTIFY = "select n.id as id, n.keywords as keywords, rmt.id as rmtid  from notifications n,NOTFICATION_MAP_TAXONOMY rmt where rmt.id > ? and rmt.NOTIFICATION_ID=n.id order by rmt.id";

	const SQL_TAXONOMY_PROD_MAP = "select Product_Category_Map_Id, Category_Id from product_category_map where upper(Product_Name) like ?";

	const SQL_TAXONOMY_CATEGORY = "select Category_Id from category where upper(Category_Name) like ?";

	const SQL_TAXONOMY_CATEGORY_SYN = "select Category_Id from categorysynonym where upper(Synonym) like ?";

	const UPDATE_REQ_WITH_CAT_PROD = "update posts set Category_Id = ?, Product_Category_Map_Id = ? where id = ?";

	const UPDATE_NOTFICATION_WITH_CAT_PROD = "update notifications set Category_Id = ?, Product_Category_Map_Id = ?, ModifiedOn=now() where id = ?";

	const INS_NOTIFY_POST_MATCH = "insert into notify_post_match(post_id, requirements, created_date_time, requirement_expiration_date) values (?,?,NOW(),?)";

	const SQL_TAXONOMY_PROD_SYN_MAP = "select ps.Product_Category_Map_Id, pcm.category_id from productsynonym ps, product_category_map pcm  where upper(Synonym) like ? and ps.Product_Category_Map_Id=pcm.Product_Category_Map_Id";

	const SQL_TAXONOMY_REQ = "select npm.post_id as id, npm.requirements as requirements,npm.requirement_expiration_date  as ExpireDateTime, npm.notify_post_match_id as notify_post_match_id, p.category_id as category_id, p.product_category_map_id as product_category_map_id  from notify_post_match npm, posts p where npm.notify_post_match_id > ? and npm.post_id=p.id and p.category_id is not null order by npm.notify_post_match_id";

	const SQL_NOTIFICATION_CAT = "select id, productcategoryid,subcategoryid,emailaddress,mobilenumber,keywords,category_id,product_category_map_id from notifications where category_id is not null";
	
	const SQL_PARENT_CATEGORY = "SELECT category_id FROM category WHERE category_id = ( SELECT Parent_Category_Id FROM category WHERE category_id =? )";
}
?>