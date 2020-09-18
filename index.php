<?php
	include('../../init.php');

	//make circle a variable that will have to extract from the user
	//$circle=$_GET['circle'];
	//for now it is set to 1

	$response = array();
	$limit = '';
	if(isset($_GET ['limit'])){
		$val=$_GET ['limit'];
		$val=preg_replace('[ˆ0-9]','1',$val);
		$limit=' LIMIT ' . $val;
	}
	//GROCERY LISTING - change circle in this query
	$sqlG="SELECT g.id AS gid,g.name AS gname, b.name AS bname FROM grocery_item AS g,merchant AS m, brand AS b WHERE merchant_id=m.id AND brand_id=b.id AND m.circle=1 AND g.visible=1 ".$limit;

	$resultG = $con->query($sqlG);

	if ($resultG->num_rows>0) {

		while($rowg = $resultG->fetch_assoc()) {

			$id = $rowg['gid'];
			$name = $rowg['gname'];
			$brand = $rowg['bname'];
			$prod_cat_id=1;
			$db_table = "grocery_item";

			//load a thumbnail
			$thumbg="SELECT image FROM product_image WHERE prod_cat_id=$prod_cat_id and prod_id=$id LIMIT 1";
			$imgg=mysqli_query($con,$thumbg);
			$row_imgg = $imgg->fetch_assoc();
			$thumbnailg=$base_url.$row_imgg['image'];

			//get starting price for this item from pack_size
			$get_ps = "SELECT price FROM pack_size WHERE product_id=$id and prod_cat_id=$prod_cat_id ORDER BY size LIMIT 1";
			$ps = $con->query($get_ps);
			if ($ps->num_rows>0) {
				$ps_row = $ps->fetch_assoc();
				$price = $ps_row['price'];

				array_push($response,array("id"=>$id,"name"=>$name,"brand"=>$brand,"thumbnail"=>$thumbnailg,"starting_from_price"=>$price));
			}
		}
	}

	echo json_encode($response);
?>
