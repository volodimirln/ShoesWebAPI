<?
	
	header('Access-Control-Allow-Origin: *'); 
    header('Access-Control-Allow-Headers: *'); 
    header('Access-Control-Allow-Methods: *'); 
    header('Access-Control-Allow-Credentials: true'); 
    header('Content-Type: application/json; charset=utf-8');
 
	/**
	 * 
	 */
	class API
	{	
		private  $queryList = [];

		private $srv = "localhost";
		private $dbUser = "0000";
	 	private $dbPass = "0000";
		private $dbName = "0000";

		private $token = '0000';

		public $filter;
		public $method;

		function __construct()
		{
			$this->filter = $_GET['filter'];
			$this->method = $_SERVER['REQUEST_METHOD'];

		}
		public function main()
		{
			$q = $_GET['q'];
			$params = explode('/', $q);
			$type = $params[1];
			if($type == 'GetOzonNotifications'){
				$date = new DateTime('now', new DateTimeZone('UTC'));
				echo json_encode(array("version" => "1.0", "name" => "Obuvashka23", "time" => $date->format("Y-m-d\TH:i:s\Z")));
			}
			if ($this->method === 'GET') {
				if($type == "test"){
					$qr = "CALL `test`();";
					$this->connDB($qr);
				}
				if($type == 'GetLastVersionCNT'){
						$f = scandir("/home/c/cx07681/api.obuvashka23/public_html/containers");
						foreach ($f as $file){
    					if(preg_match('/\.(zip)/', $file)){ 	
							$fl[] = array("filepath" => (string)$file, 
										  "time" =>  date("Ymdhhmm", filemtime("/home/c/cx07681/api.obuvashka23/public_html/containers/".$file)));
    					}
					}

					
						echo json_encode(array(array("fileName" => max($fl)["filepath"], "url" => "https://api.obuvashka23.ru/containers/".max($fl)["filepath"])));
						

				}
				if($type == 'ImagesShoes' && isset($_GET['productId']) && isset($_GET['status'])){
					if($_GET['productId'] == 0 && $_GET['status'] == 2){
						$getParSort = $_GET['sortField'];
							if ($_GET['sortField'] == "") {
								$getParSort = "id";
							}

							$getTypeSort = "";
							if($_GET['sortType'] == 0){
									$getTypeSort = "ASC";
							}elseif ($_GET['sortType'] == 1) {
								$getTypeSort = "DESC";
							}

					$limit = "";
					if(isset($_GET['limitRec'])){	
						if($_GET['оffsetRec']){
							$limit = "LIMIT {$_GET['limitRec']} OFFSET {$_GET['оffsetRec']}";
						}else{
							$limit = "LIMIT {$_GET['limitRec']}";
					
					}
					
				}

						$qr = "SELECT PIctureToProduct.id, `productId`, `photoPath`, `status`, Shoes.vendorCode FROM `PIctureToProduct`, `Shoes` WHERE Shoes.id = PIctureToProduct.productId ORDER BY {$getParSort} {$getTypeSort}  {$limit}";
					}else{
						$qr = "SELECT PIctureToProduct.id, `productId`, `photoPath`, `status`, Shoes.vendorCode FROM `PIctureToProduct`, `Shoes` WHERE Shoes.id = PIctureToProduct.productId and `productId` = {$_GET['productId']} and `status` = {$_GET['status']} ORDER BY `id` DESC";
					}
					$this->connDB($qr);
				}
				if($type == 'ImagesAccessories' && isset($_GET['accessoriesId']) && isset($_GET['status'])){
					if($_GET['accessoriesId'] == 0 && $_GET['status'] == 2){
						$qr = "SELECT PIctureToAccessories.id, `productId`, `photoPath`, `status`, Accessories.vendorCode FROM `PIctureToAccessories`, `Accessories` WHERE Accessories.id = PIctureToAccessories.productId";
					}else{
						$qr = "SELECT PIctureToAccessories.id, `productId`, `photoPath`, `status`, Accessories.vendorCode FROM `PIctureToAccessories`, `Accessories` WHERE Accessories.id = PIctureToAccessories.productId and `productId` = {$_GET['accessoriesId']} and `status` = {$_GET['status']} ORDER BY `id` DESC";
					}
					$this->connDB($qr);
				}
				if($type == 'ImagesShoesOpen' && isset($_GET['productId']) && isset($_GET['open'])){
					$r = (int)$_GET["R"];
					$g = (int)$_GET["G"];
					$b = (int)$_GET["B"];
					header("content-type: image/png");
					$conn = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					$query = mysqli_query($conn, "SELECT photoPath FROM PIctureToProduct WHERE PIctureToProduct.productId = {$_GET['productId']} AND PIctureToProduct.status = 1 LIMIT 1");
					while ($res = mysqli_fetch_assoc($query)) {
						$queryList[] = $res;
					}
					$imgurl = $queryList[0]['photoPath'];
					readfile("https://obuvashka23.ru/img/?filename=".str_replace(" ", "%20", $imgurl)."&R=$r&G=$g&B=$b");
				}
				if($type == 'ImagesBagsOpen' && isset($_GET['productId']) && isset($_GET['open'])){
					$r = (int)$_GET["R"];
					$g = (int)$_GET["G"];
					$b = (int)$_GET["B"];
					header("content-type: image/png");
					$conn = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					$query = mysqli_query($conn, "SELECT photoPath FROM PIctureToBag WHERE PIctureToBag.productId = {$_GET['productId']} AND PIctureToBag.status = 1 LIMIT 1");
					while ($res = mysqli_fetch_assoc($query)) {
						$queryList[] = $res;
					}
					$imgurl = $queryList[0]['photoPath'];
					readfile("https://obuvashka23.ru/img/?filename=".str_replace(" ", "%20", $imgurl)."&R=$r&G=$g&B=$b");
				}
				if($type == 'ImagesAccessOpen' && isset($_GET['productId']) && isset($_GET['open'])){
					$r = (int)$_GET["R"];
					$g = (int)$_GET["G"];
					$b = (int)$_GET["B"];
					header("content-type: image/png");
					$conn = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					$query = mysqli_query($conn, "SELECT photoPath FROM PIctureToAccessories WHERE PIctureToAccessories.productId = {$_GET['productId']} AND PIctureToAccessories.status = 1 LIMIT 1");
					while ($res = mysqli_fetch_assoc($query)) {
						$queryList[] = $res;
					}
					$imgurl = $queryList[0]['photoPath'];
					readfile("https://obuvashka23.ru/img/?filename=".str_replace(" ", "%20", $imgurl)."&R=$r&G=$g&B=$b");
				}
				if($type == 'ImagesBags' && isset($_GET['bagsId']) && isset($_GET['status'])){
					if($_GET['bagsId'] == 0 && $_GET['status'] == 2){
						$qr = "SELECT PIctureToBag.id, `productId`, `photoPath`, `status`, Bag.vendorCode FROM `PIctureToBag`, `Bag` WHERE Bag.id = PIctureToBag.productId";
					}else{
						$qr = "SELECT PIctureToBag.id, `productId`, `photoPath`, `status`, Bag.vendorCode FROM `PIctureToBag`, `Bag` WHERE Bag.id = PIctureToBag.productId and `productId` = {$_GET['bagsId']} and `status` = {$_GET['status']} ORDER BY `id` DESC";
					}
					$this->connDB($qr);
				}
				if($type == 'Genders'){
					$qr = "SELECT * FROM `Gender`";
					$this->connDB($qr);
				}
				if($type == 'Colors'){
					$qr = "SELECT * FROM `Color`";
					$this->connDB($qr);
				}
				if($type == 'TNVED'){
					$qr = "SELECT * FROM `TNVED`";
					$this->connDB($qr);
				}
				if($type == 'CountryBrands'){
					$qr = "SELECT * FROM `CountryBrand`";
					$this->connDB($qr);
				}

				if($type == 'Countries'){
					$qr = "SELECT * FROM `Country`";
					$this->connDB($qr);
				}
				if($type == 'Stylies'){
					$qr = "SELECT * FROM `Style`";
					$this->connDB($qr);
				}
				if($type == 'Outmaterials'){
					$qr = "SELECT * FROM `Outmaterial`";
					$this->connDB($qr);
				}

				if($type == 'Insolematerials'){
					$qr = "SELECT * FROM `InsoleMaterial`";
					$this->connDB($qr);
				}
				
				if ($type == 'vendorShoes') {
					$qr = "SELECT Shoes.id as id, discount as realdiscount, ROUND(discount * 100) as discount, Gender.gender as gender, Season.title as season, Type.title as type, Brands.title as brand, Materials.title as material, vendorCode, CONCAT(Type.title, ' ', Brands.title) as title, Shoes.description as description, price, markdown, popularity, outmaterial, insoleMaterial FROM `Shoes`, `Season`, `Type`, `Materials`, `Brands`, `Gender` WHERE Shoes.genderId = Gender.id and Shoes.seasonId = Season.id AND Shoes.typeId = Type.id and Shoes.materials = Materials.id and Shoes.brand = Brands.id AND `vendorCode` = '{$_GET['vendorCode']}'";
					$this->connDB($qr); 
				}
				if ($type == 'vendorAccessories') {
					$qr = "SELECT Accessories.id as id, discount as realdiscount, ROUND(discount * 100) as discount, typeId, vendorCode, Accessories.title as title, Accessories.description, num, price, color, sizeHead, Type.title as type, `popularity` FROM `Accessories`, `Type` WHERE Accessories.typeId = Type.id AND `vendorCode` = '{$_GET['vendorCode']}'";
					$this->connDB($qr); 
				}
				if ($type == 'vendorBags') {
					$qr = "SELECT Bag.id as id, num, discount as realdiscount, ROUND(discount * 100) as discount, vendorCode, Bag.title as title, price, Brands.title as brand, Gender.gender as gender, Bag.description  FROM `Bag`, `Brands`, `Gender` WHERE Bag.brandId = Brands.id and Bag.genderId = Gender.id  AND `vendorCode` = '{$_GET['vendorCode']}'";
					$this->connDB($qr); 
				}
				if($type == 'Brands'){
					$qr = "SELECT * FROM `Brands`";
					$this->connDB($qr);
				}//SELECT SUM(sum) FROM `Order`
				if($type == 'sumorder'){
					$qr = "SELECT SUM(sum) as sum FROM `Order`";
					$this->connDB($qr);
				}
				if($type == 'Materials'){
					$qr = "SELECT * FROM `Materials`";
					$this->connDB($qr);
				}
				if($type == 'Seasons'){
					$qr = "SELECT * FROM `Season`";
					$this->connDB($qr);
				}
				if($type == 'Size'){
					$getParSort = $_GET['sortField'];
							if ($_GET['sortField'] == "") {
								$getParSort = "id";
							}

							$getTypeSort = "";
							if($_GET['sortType'] == 0){
									$getTypeSort = "ASC";
							}elseif ($_GET['sortType'] == 1) {
								$getTypeSort = "DESC";
							}

					$limit = "";
					if(isset($_GET['limitRec'])){	
						if($_GET['оffsetRec']){
							$limit = "LIMIT {$_GET['limitRec']} OFFSET {$_GET['оffsetRec']}";
						}else{
							$limit = "LIMIT {$_GET['limitRec']}";
					}
				}

					$qr = "SELECT Size.id, shoesId, size, num, Shoes.title as shoesTitle, `vendorCode` FROM `Size`, `Shoes` WHERE Shoes.id = Size.shoesId ORDER BY {$getParSort} {$getTypeSort}  {$limit}";
					$this->connDB($qr);
				}
				if($type == 'Type'){
					if(!isset($_GET['typeObject'])){
						$qr = "SELECT * FROM `Type`";
					}else{
						$qr = "SELECT * FROM `Type` WHERE `typeObject` = '{$_GET["typeObject"]}'";
					}
					
					$this->connDB($qr);
				}
				if($type == 'BrandCount'){
					$qr = "SELECT DISTINCT Brands.title as brand, COUNT(Shoes.id) as `count` FROM Brands, Shoes WHERE Shoes.brand = Brands.id GROUP by Brands.title";
					$this->connDB($qr);
				}
				if($type == 'Shoes'){

					$getParSort = $_GET['sortField'];
							if ($_GET['sortField'] == "") {
								$getParSort = "popularity";
							}

							$getTypeSort = "";
							if($_GET['sortType'] == 0){
									$getTypeSort = "ASC";
							}elseif ($_GET['sortType'] == 1) {
								$getTypeSort = "DESC";
							}

							$limit = "";
							$getLimitRec = $_GET['limitRec'];
								$getOffsetRec = $_GET['оffsetRec'];

							if(isset($_GET['limitRec'])){	
								if($_GET['offsetRec']){
									$limit = "LIMIT {$_GET['limitRec']} OFFSET {$_GET['offsetRec']}";
								}else{
									$limit = "LIMIT {$_GET['limitRec']}";
								}	
							}
							$qr = "SELECT Shoes.id AS id, linkToOzon, linkToYandex, permanently_yandex, import_yandex, permanently_ozon, import_ozon, tnvedId, lwhwPackage, vendorCode, Shoes.outmaterialId, Shoes.insoleMaterialId, Shoes.colorId, Shoes.styleId, typeId, seasonId, brand, genderId, insoleMaterial, CONCAT(Type.title, ' ', Brands.title) as title, Shoes.description, `price`, `markdown`, Season.title AS season, Gender.gender AS gender, Type.title AS type,  materials, `outmaterial`, Brands.title AS brands, discount as realdiscount, ROUND(discount * 100) as discount, Shoes.popularity, Shoes.timeToAdd  FROM `Shoes`, `Season`, `Type`, `Gender`, `Brands`, `Materials` WHERE Shoes.seasonId = Season.id AND Shoes.genderId = Gender.id AND Shoes.typeId = Type.id AND Shoes.materials = Materials.id AND Shoes.brand = Brands.id AND vendorCode LIKE '%{$_GET['search']}%' ORDER BY {$getParSort} {$getTypeSort}  {$limit}";
						if($params[2] == 'shoesBorderPrice'){
							$qr ="SELECT MAX(price) as maxprice, MIN(price) as minprice FROM `Shoes`";
						}
						if($params[2] == 'shoesSizeBorderPrice'){
							$qr ="SELECT MAX(size) as maxsize, MIN(size) as minsize FROM `Size` ";
						}
						if($params[2] == 'filter'){

							$getParSort = $_GET['sortField'];
							if ($_GET['sortField'] == "") {
								$getParSort = "popularity";
							}

							$brand = "";
							if($_GET['brand'] != ""){
								$brand = " `brand` = {$_GET['brand']} AND";
							}
							$gender = "";
							if($_GET['gender'] != ""){
								$gender = " `genderId` = {$_GET['gender']} AND";
							}
							$season = "";
							if($_GET['season'] != ""){
								$season = " `seasonId` = {$_GET['season']} AND";
							}
							$typeShoes = "";
							if($_GET['typeShoes'] != ""){
								$typeShoes = " `typeId` = {$_GET['typeShoes']} AND";
							}
							

							$getTypeSort = "";
							if($_GET['sortType'] == 0){
									$getTypeSort = "ASC";
							}elseif ($_GET['sortType'] == 1) {
								$getTypeSort = "DESC";
							}

							$limit = "";
							$getLimitRec = $_GET['limitRec'];
								$getOffsetRec = $_GET['оffsetRec'];

							if(isset($_GET['limitRec'])){	
								if($_GET['оffsetRec']){
									$limit = "LIMIT {$_GET['limitRec']} OFFSET {$_GET['оffsetRec']}";
								}else{
									$limit = "LIMIT {$_GET['limitRec']}";
								}	
							}

							$priceUp = 0;
							$priceDown = 5000;
							if(isset($_GET['priceUp'])){
								$priceUp = $_GET['priceUp'];
								$priceDown = $_GET['priceDown'];
							}

							$discountUp = 0;
							$discountDown = 0.5;
							if(isset($_GET['discountUp'])){
								$discountUp = $_GET['discountUp'];
								$discountDown = $_GET['discountDown'];
							}

							$sizeUp = 0;
							$sizeDown = 50;
							if(isset($_GET['sizeUp'])){
								$sizeUp = $_GET['sizeUp'];
								$sizeDown = $_GET['sizeDown'];
							}
							if(isset($_GET['count'])){
								$qr = "SELECT DISTINCT COUNT(DISTINCT Shoes.id) as count, COUNT(Size.num) as countSize, SUM(Size.num * Shoes.price) as allprice FROM `Shoes`, `Season`, `Type`, `Gender`, `Brands`, `Materials`, `Size` WHERE  Shoes.seasonId = Season.id AND Shoes.genderId = Gender.id AND Shoes.typeId = Type.id AND Shoes.materials = Materials.id AND Shoes.brand = Brands.id AND Shoes.id = Size.shoesId AND vendorCode LIKE '%{$_GET['search']}%'  AND `markdown` LIKE '%{$_GET['markdown']}' AND {$gender} {$season} {$typeShoes}  {$brand}  `materials` LIKE '%{$_GET['materials']}' AND `outmaterial` LIKE '%{$_GET['outmaterial']}' AND `price` BETWEEN {$priceUp} AND {$priceDown} AND Size.size BETWEEN {$sizeUp} and {$sizeDown}  AND `discount` BETWEEN {$discountUp} AND {$discountDown}  ORDER BY Shoes.id";
							}else{
							$qr = "SELECT DISTINCT Shoes.id AS id, linkToOzon, linkToYandex, permanently_yandex, import_yandex, permanently_ozon, import_ozon, tnvedId, lwhwPackage, Shoes.colorId, Shoes.outmaterialId, Shoes.insoleMaterialId, Shoes.brand as brandId, vendorCode, CONCAT(Type.title, ' ', Brands.title) as title, Shoes.description, `price`, `markdown`, Season.title AS season, Gender.gender AS gender, Type.title AS type, Materials.title AS materials, `outmaterial`, Brands.title AS brands,  `discount` , `popularity` FROM `Shoes`, `Season`, `Type`, `Gender`, `Brands`, `Materials`, `Size`, `PIctureToProduct` WHERE PIctureToProduct.productId = Shoes.id AND Shoes.seasonId = Season.id AND Shoes.genderId = Gender.id AND Shoes.typeId = Type.id AND Shoes.materials = Materials.id AND Shoes.brand = Brands.id AND Shoes.id = Size.shoesId AND `markdown` LIKE '%{$_GET['markdown']}' AND {$gender} {$season} {$typeShoes} {$brand}  `materials` LIKE '%{$_GET['materials']}' AND `outmaterial` LIKE '%{$_GET['outmaterial']}' AND `price` BETWEEN {$priceUp} AND {$priceDown}  AND `discount` BETWEEN {$discountUp} AND {$discountDown} AND Size.size BETWEEN {$sizeUp} and {$sizeDown} GROUP BY Shoes.id ORDER BY {$getParSort} {$getTypeSort}  {$limit} ";
						}
							$this->connDB($qr);
						}else{
							$this->connDB($qr);
						}
				}
					if ($type == 'ShoesSize' || isset($_GET['idShoes'])) {
					$qr = "SELECT DISTINCT Shoes.id, Size.size, Size.num FROM `Size`, `Shoes` WHERE shoesId = Shoes.id AND Shoes.id =  {$_GET['idShoes']}";
					$this->connDB($qr);
				}

				if ($type == 'Bags') {
					
					 $getParSort = $_GET['sortField'];
							if ($_GET['sortField'] == "") {
								$getParSort = "popularity";
							}

							$getTypeSort = "";
							if($_GET['sortType'] == 0){
									$getTypeSort = "ASC";
							}elseif ($_GET['sortType'] == 1) {
								$getTypeSort = "DESC";
							}

							$limit = "";
							$getLimitRec = $_GET['limitRec'];
								$getOffsetRec = $_GET['оffsetRec'];

							if(isset($_GET['limitRec'])){	
								if($_GET['оffsetRec']){
									$limit = "LIMIT {$_GET['limitRec']} OFFSET {$_GET['оffsetRec']}";
								}else{
									$limit = "LIMIT {$_GET['limitRec']}";
								}	
							}

					$qr = "SELECT Bag.id as id, discount as realdiscount, ROUND(discount * 100) as discount, num, Bag.genderId as genderId, vendorCode, Bag.title as title, price, Brands.title as brand, Gender.gender as gender, Bag.description`   FROM `Bag`, `Brands`, `Gender` WHERE Bag.brandId = Brands.id and Bag.genderId = Gender.id ORDER BY {$getParSort} {$getTypeSort} {$limit}";
					if($params[2] == 'bagBorderPrice'){
						$qr ="SELECT MAX(price) as maxprice, MIN(price) as minprice FROM `Bag`";
					}
							if($params[2] == 'filter'){

								$getParSort = $_GET['sortField'];
							if ($_GET['sortField'] == "") {
								$getParSort = "popularity";
							}
							$discountUp = 0;
							$discountDown = 0.5;
							if(isset($_GET['discountUp'])){
								$discountUp = $_GET['discountUp'];
								$discountDown = $_GET['discountDown'];
							}

							$getTypeSort = "";
							if($_GET['sortType'] == 0){
									$getTypeSort = "ASC";
							}elseif ($_GET['sortType'] == 1) {
								$getTypeSort = "DESC";
							}

							$limit = "";
							$getLimitRec = $_GET['limitRec'];
								$getOffsetRec = $_GET['оffsetRec'];

							if(isset($_GET['limitRec'])){	
								if($_GET['оffsetRec']){
									$limit = "LIMIT {$_GET['limitRec']} OFFSET {$_GET['оffsetRec']}";
								}else{
									$limit = "LIMIT {$_GET['limitRec']}";
								}	
							}

							$priceUp = 0;
							$priceDown = 5000;
							if(isset($_GET['priceUp'])){
								$priceUp = $_GET['priceUp'];
								$priceDown = $_GET['priceDown'];
							}

							if(isset($_GET['count'])){
								$qr = "SELECT COUNT(Bag.id)  as count FROM `Bag`, `Brands`, `Gender` WHERE Bag.brandId = Brands.id and Bag.genderId = Gender.id AND `genderId` LIKE '%{$_GET['gender']}' AND `materialOutside` LIKE '%{$_GET['materialOutside']}' AND `materialInside` LIKE '%{$_GET['materialInside']}' AND `color` LIKE '%{$_GET['color']}' AND Brands.id LIKE '%{$_GET['brand']}' AND `price` BETWEEN {$priceUp}  AND {$priceDown} AND `discount` BETWEEN {$discountUp} AND {$discountDown}   ORDER BY {$getParSort} {$getTypeSort} ";
							}else{
								$qr = "SELECT Bag.id as id, num, Bag.genderId as genderId, Bag.brandId as brandId, vendorCode, Bag.title as title, price, Brands.title as brand, Gender.gender as gender, materialOutside, materialInside, Bag.description, color,  `discount`, `popularity` FROM `Bag`, `Brands`, `Gender` WHERE Bag.brandId = Brands.id and Bag.genderId = Gender.id AND `genderId` LIKE '%{$_GET['gender']}' AND `materialOutside` LIKE '%{$_GET['materialOutside']}' AND `materialInside` LIKE '%{$_GET['materialInside']}' AND `color` LIKE '%{$_GET['color']}' AND Brands.id LIKE '%{$_GET['brand']}' AND `price` BETWEEN {$priceUp}  AND {$priceDown} AND `discount` BETWEEN {$discountUp} AND {$discountDown}   ORDER BY {$getParSort} {$getTypeSort} {$limit}";
							}
								$this->connDB($qr);
						}else{
							$this->connDB($qr);
						}
				}
				if ($type == "Accessories") {
					$getParSort = $_GET['sortField'];
							if ($_GET['sortField'] == "") {
								$getParSort = "popularity";
							}

							$getTypeSort = "";
							if($_GET['sortType'] == 0){
									$getTypeSort = "ASC";
							}elseif ($_GET['sortType'] == 1) {
								$getTypeSort = "DESC";
							}

							$limit = "";
							$getLimitRec = $_GET['limitRec'];
								$getOffsetRec = $_GET['оffsetRec'];

							if(isset($_GET['limitRec'])){	
								if($_GET['оffsetRec']){
									$limit = "LIMIT {$_GET['limitRec']} OFFSET {$_GET['оffsetRec']}";
								}else{
									$limit = "LIMIT {$_GET['limitRec']}";
								}	


							}
					$qr = "SELECT Accessories.id as id, discount as realdiscount, ROUND(discount * 100) as discount, typeId, num, vendorCode, Accessories.title as title, Accessories.description, price, color, sizeHead, Type.title as type, `popularity` FROM `Accessories`, `Type` WHERE Accessories.typeId = Type.id ORDER BY  {$getParSort} {$getTypeSort} {$limit}";

					if($params[2] == 'accessoriesBorderPrice'){
						$qr ="SELECT MAX(price) as maxprice, MIN(price) as minprice FROM `Accessories`";
					}
					if($params[2] == 'filter'){

						$getParSort = $_GET['sortField'];
							if ($_GET['sortField'] == "") {
								$getParSort = "popularity";
							}

							$getTypeSort = "";
							if($_GET['sortType'] == 0){
									$getTypeSort = "ASC";
							}elseif ($_GET['sortType'] == 1) {
								$getTypeSort = "DESC";
							}
							$discountUp = 0;
							$discountDown = 0.5;
							if(isset($_GET['discountUp'])){
								$discountUp = $_GET['discountUp'];
								$discountDown = $_GET['discountDown'];
							}

							$limit = "";
							$getLimitRec = $_GET['limitRec'];
								$getOffsetRec = $_GET['оffsetRec'];

							if(isset($_GET['limitRec'])){	
								if($_GET['оffsetRec']){
									$limit = "LIMIT {$_GET['limitRec']} OFFSET {$_GET['оffsetRec']}";
								}else{
									$limit = "LIMIT {$_GET['limitRec']}";
								}	
							}
							$priceUp = 0;
							$priceDown = 10000;
							if(isset($_GET['priceUp'])){
								$priceUp = $_GET['priceUp'];
								$priceDown = $_GET['priceDown'];
							}

							if(isset($_GET['count'])){
								$qr = "SELECT COUNT(Accessories.id) as count FROM `Accessories`, `Type`  WHERE Accessories.typeId = Type.id AND `color` LIKE '%{$_GET['color']}' AND `sizeHead` LIKE '%{$_GET['sizeHead']}' AND typeId  LIKE '%{$_GET['typeId']}' AND `price` BETWEEN {$priceUp}  AND {$priceDown} AND `discount` BETWEEN {$discountUp} AND {$discountDown} ORDER BY  {$getParSort} {$getTypeSort} {$limit}";

							}else{
								$qr = "SELECT Accessories.id as id, vendorCode, num, Accessories.title as title, Accessories.description, price, color, sizeHead, Type.title as type,  `discount`, `popularity` FROM `Accessories`, `Type`  WHERE Accessories.typeId = Type.id AND `color` LIKE '%{$_GET['color']}' AND `sizeHead` LIKE '%{$_GET['sizeHead']}' AND typeId  LIKE '%{$_GET['typeId']}' AND `price` BETWEEN {$priceUp}  AND {$priceDown} AND `discount` BETWEEN {$discountUp} AND {$discountDown} ORDER BY  {$getParSort} {$getTypeSort} {$limit}";
							}
								$this->connDB($qr);
						}else{
							$this->connDB($qr);
						}
				}
			} elseif($this->method === 'POST'){
				if($type == $this->token && $params[2] == 'GetExportOzon'){


					$connkey = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					$qrkey = "SELECT * FROM `Keys` WHERE id = {$_GET["key"]}";
					$querykey = mysqli_query($connkey, $qrkey);
					$urltoken = "";
					$headertoken = "";
					$stockkey = "";
					while ($res = mysqli_fetch_assoc($querykey)) {
						if((int)$res["IdMarketplace"] == 1){
							$urltoken = $res["UrlToken"];
							$headertoken = $res["TokenHeder"];
							$stockkey = $res["stock"];
						}
					}

					$conn = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					$qr = "SELECT Concat(Shoes.vendorCode, '_', MAX(Size.size)) as vendorCode FROM `Size`, `Shoes` WHERE Size.shoesId = Shoes.id AND Size.shoesId = {$_POST["productId"]}";
					$query = mysqli_query($conn, $qr);
					while ($res = mysqli_fetch_assoc($query)) {
						$queryList[] = $res;
					}
					$task =  [
						"filter" => [
							"offer_id" => [
								$queryList[0]["vendorCode"]
							],
							"visibility" => "ALL"
						],
						"limit" => 100,
					];
					$ch1 = curl_init('https://api-seller.ozon.ru/v3/products/info/attributes');
					curl_setopt($ch1, CURLOPT_HTTPHEADER, 
				    array('Content-Type:application/json',  'Client-Id: 1445093', 'Api-Key: 06168270-7f27-440e-91f0-83f31fac42ff'));
					curl_setopt($ch1, CURLOPT_POST, 1);
					curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($task, JSON_UNESCAPED_UNICODE)); 
				    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
				    curl_setopt($ch1, CURLOPT_HEADER, false);
				    $res = curl_exec($ch1);
				    curl_close($ch1);
					$arr = json_decode($res)->{"result"};
				    echo json_encode($arr);

					$title = $arr[0]->{"name"};
					$height = $arr[0]->{"height"};
					$depth = $arr[0]->{"depth"};
					$width = $arr[0]->{"width"};
					$weight = $arr[0]->{"weight"};
					$rt = $arr[0]->{"attributes"};
					$qr = "UPDATE `Shoes` SET `title`='{$title}', `lwhwPackage`='{$depth};{$width};{$height};{$weight}' WHERE 1";
					$query = mysqli_query($conn, $qr);
				}
				if($type == $this->token && $params[2] == 'GetImportOzon'){

					$connkey = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					$qrkey = "SELECT * FROM `Keys` WHERE id = {$_POST["key"]}";
					$querykey = mysqli_query($connkey, $qrkey);
					$urltoken = "";
					$headertoken = "";
					$stockkey = "";
					while ($res = mysqli_fetch_assoc($querykey)) {
						if((int)$res["IdMarketplace"] == 2){
							$urltoken = $res["UrlToken"];
							$headertoken = $res["TokenHeder"];
							$stockkey = $res["stock"];
						}
					}


					$conn = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					if(isset($_POST["brandId"])){
						$condition = " and Shoes.brand = {$_POST["brandId"]}";
					}elseif(isset($_POST["productId"])){
						$condition = " and Shoes.id = {$_POST["productId"]}";
					}
					//$qr = "SELECT Shoes.id as id, Shoes.vendorCode, Shoes.description, Shoes.price, Shoes.discount, Type.title as type, Type.WeightPackage, Type.LengthPaсkage, Type.HeightPackage, Type.WidthPackage, Type.oz_category_id as category_id, Type.oz_TyoeId_8229 as type_id, Season.title as season, Season.oz_SeasonId_4495 as season_id, Brands.title as brand, Brands.oz_brandId_31 as brand_id, Gender.gender as gender, Gender.oz_GenderId_9163 as gender_id FROM `Type`, `Shoes`, `Season`, `Brands`, `Gender` WHERE Shoes.typeId = Type.id AND Shoes.seasonId = Season.id AND Shoes.brand = Brands.id AND Shoes.genderId = Gender.id and Shoes.id = 10";
					$qr = "SELECT Shoes.id as id, Size.num as num, Shoes.vendorCode, Shoes.description, Size.size as size, Size.oz_SizeId_4298 as sizeCode, Shoes.price, Shoes.discount, Type.title as type, Type.oz_category_id as category_id, Type.oz_TyoeId_8229 as type_id, Season.title as season, Season.oz_SeasonId_4495 as season_id, Brands.title as brand, Brands.oz_brandId_31 as brand_id, Gender.gender as gender, Gender.oz_GenderId_9163 as gender_id, Style.oz_styleId_4501 as style_id, CountryBrand.oz_countryId_9248 as country_9248, Country.oz_countryId_4389 as country_4389, Color.oz_colorId_10096 as color_id, InsoleMaterial.oz_insoleId_4516 as insoleMaterial_id, Materials.oz_MaterialId_4496 as material_id, Outmaterial.oz_outmaterial_4305 as outmaterial_id, Color.title as color, Style.title as style , Shoes.lwhwPackage, TNVED.oz_TNVEDId_22232 as tnved, Brands.doc FROM Size,  `Type`, `Shoes`, `TNVED`, `Season`, `Brands`, `Gender`, `CountryBrand`, `Country`, `Color`, `Materials`, `InsoleMaterial`, `Outmaterial`, `Style` WHERE Shoes.typeId = Type.id AND Shoes.seasonId = Season.id AND Shoes.brand = Brands.id AND Shoes.genderId = Gender.id and Size.shoesId = Shoes.id AND CountryBrand.id = Brands.countryId AND Country.id = Brands.countryManufId AND Color.id = Shoes.colorId AND Materials.id = Shoes.materials AND Shoes.insoleMaterialId = InsoleMaterial.id AND Shoes.outmaterialId = Outmaterial.id and Shoes.tnvedId = TNVED.id and Style.id = Shoes.styleId  {$condition}";
					$query = mysqli_query($conn, $qr);
					$arraytoproducts = [];

                    $atribute = array();
					$connect_for_import = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					while ($res = mysqli_fetch_assoc($query)) {
						$queryList[] = $res;
                        $atr = [];
						
						if((int)$res["category_id"] != 0) 
							if((int)$res["doc"] != 1) 
						
							


					#Бренд
					if(!isset($res["brand_id"])){
						$values = array("value" => "Нет бренда");
					}else{
						$values = array("dictionary_value_id" => (int)$res["brand_id"]);
					}
                    array_push($atr, array("complex_id" => 0, "id" => 31, "values" => array($values)));
					
					#Размер
					$values = array("dictionary_value_id" => (int)$res["sizeCode"]);
					array_push($atr, array("complex_id" => 0, "id" => 4298, "values" => array($values)));

					#Сезон
                    $values = array("dictionary_value_id" => (int)$res["season_id"]);
                    array_push($atr, array("complex_id" => 0, "id" => 4495, "values" => array($values)));
					
					#Тип
                    $values = array("dictionary_value_id" => (int)$res["type_id"]);
                    array_push($atr, array("complex_id" => 0, "id" => 8229, "values" => array($values)));

					#Объединить на одной карточке
                    $values = array("value" => $res["vendorCode"]."");
                    array_push($atr, array("complex_id" => 0, "id" => 8292, "values" => array($values)));

					#Пол
					$gender = explode(";", $res["gender_id"]);
					if(count($gender) > 1){
						$values = array("dictionary_value_id" => (int)$gender[0]);
						$values1 = array("dictionary_value_id" => (int)$gender[1]);
                    	array_push($atr, array("complex_id" => 0, "id" => 9163, "values" => array($values, $values1)));
					}else{
						$values = array("dictionary_value_id" => (int)$res["gender_id"]);
                    	array_push($atr, array("complex_id" => 0, "id" => 9163, "values" => array($values)));
					}

					#Цвет товара
                    $values = array("dictionary_value_id" => (int)$res["color_id"]);
                    array_push($atr, array("complex_id" => 0, "id" => 10096, "values" => array($values)));

					#ТН ВЭД коды ЕАЭС tnved
                    $values = array("dictionary_value_id" => (int)$res["tnved"]);
                    array_push($atr, array("complex_id" => 0, "id" => 22232, "values" => array($values)));

					$richcontent = '{
						"content": [
						  {
							"widgetName": "raTextBlock",
							"title": {
							  "content": [
								"'.$res["type"]." ".$res["brand"]." ".$res["style"]." стиль, ".$res["color"].", размер ".$res["size"].'"
							  ],
							  "size": "size5",
							  "color": "color4"
							},
							"theme": "primary",
							"padding": "type2",
							"gapSize": "m",
							"text": {
							  "size": "size2",
							  "align": "left",
							  "color": "color4",
							  "content": [
								"'.$res["type"]." ".$res["brand"]." ".$res["style"]." - ".$res["description"].'"
							  ]
							}
						  }
						],
						"version": 0.3
					  }';


					#Rich content
					//$values = array("value" => ($richcontent));
                    //array_push($atr, array("complex_id" => 0, "id" => 11254, "values" => array($values)));

					#Название
					$nameat =  $res["type"]." ".$res["brand"]." ".$res["style"]." стиль, ".$res["color"].", размер ".$res["size"];
                    $values = array("value" => $nameat);
                    array_push($atr, array("complex_id" => 0, "id" => 4180, "values" => array($values)));

					#Аннотация
                    $values = array("value" => $res["description"]);
                    array_push($atr, array("complex_id" => 0, "id" => 4191, "values" => array($values)));

					#Внутренний материал
					if($res["outmaterial_id"] != ""){
						$outmaterial = explode(";", $res["outmaterial_id"]);
					  if(count($outmaterial) > 1){
						$values = array("dictionary_value_id" => (int)$outmaterial[0]);
						$values1 = array("dictionary_value_id" => (int)$outmaterial[1]);
                    	array_push($atr, array("complex_id" => 0, "id" => 4305, "values" => array($values)));
					  }else{
						$values = array("dictionary_value_id" => (int)$res["outmaterial_id"]);
						array_push($atr, array("complex_id" => 0, "id" => 4305, "values" => array($values)));
					  }
					}
					


					#Страна-изготовитель
                    $values = array("dictionary_value_id" => (int)$res["country_4389"]);
                    array_push($atr, array("complex_id" => 0, "id" => 4389, "values" => array($values)));

					#Материал !!!!
					$material = explode(";", $res["material_id"]);
					if(count($material) > 1){
                    	$values = array("dictionary_value_id" => (int)$material[0]);
						$values1 = array("dictionary_value_id" => (int)$material[1]);
                    	array_push($atr, array("complex_id" => 0, "id" => 4496, "values" => array($values, $values1)));
					}else{
						$values = array("dictionary_value_id" => (int)$material[0]);
						array_push($atr, array("complex_id" => 0, "id" => 4496, "values" => array($values)));
					}

					#Стиль
                    $values = array("dictionary_value_id" => (int)$res["style_id"]);
                    array_push($atr, array("complex_id" => 0, "id" => 4501, "values" => array($values)));

					#Коллекция
					$values = array("dictionary_value_id" => (int)39116);
					array_push($atr, array("complex_id" => 0, "id" => 4503, "values" => array($values)));

					#Материал подошвы !!!!
					$insole = explode(";", $res["insoleMaterial_id"]);
					if(count($insole) > 1){
					$values = array("dictionary_value_id" => (int)$insole[0]);
					$values1 = array("dictionary_value_id" => (int)$insole[1]);
					array_push($atr, array("complex_id" => 0, "id" => 4516, "values" => array($values, $values1)));
					}
					else{
						$values = array("dictionary_value_id" => (int)$insole[0]);
						array_push($atr, array("complex_id" => 0, "id" => 4516, "values" => array($values)));
					}

					#Артикул
					$values = array("value" => $res["vendorCode"]);
					array_push($atr, array("complex_id" => 0, "id" => 9024, "values" => array($values)));

					#Страна бренда
					$values = array("dictionary_value_id" => (int)$res["country_9248"]);
					array_push($atr, array("complex_id" => 0, "id" => 9248, "values" => array($values)));

					#Целевая аудитория
					$values = array("dictionary_value_id" => (int)43242);
					$values1 = array("dictionary_value_id" => (int)43241);
					array_push($atr, array("complex_id" => 0, "id" => 9390, "values" => array($values, $values1)));

					#Размер производителя
					$values = array("value" => $res["size"]);
					array_push($atr, array("complex_id" => 0, "id" => 9533, "values" => array($values)));

					#Количество заводских упаковок
					$values = array("value" => "1");
					array_push($atr, array("complex_id" => 0, "id" => 11650, "values" => array($values)));

					#Гарантийный срок
					$values = array("value" => "30 дней");
					array_push($atr, array("complex_id" => 0, "id" => 8802, "values" => array($values)));

					#Ключевые слова
					$values = array("value" => $res["type"].";".$res["brand"].";детская обувь;обувь;".$res["season"]);
					array_push($atr, array("complex_id" => 0, "id" => 22336, "values" => array($values)));


					  //array_push($atr, array("complex_id" => 0, "id" => 11254, "values" => array(json_decode($richcontent))));

                    $qrimg = "SELECT photoPath FROM `PIctureToProduct` WHERE productId = {$res['id']} Order BY status DESC";
                    $queryimig = mysqli_query($conn, $qrimg);
                    $stringImg = array();
                    while ($resImg = mysqli_fetch_assoc($queryimig)) {
                        array_push($stringImg, "https://api.obuvashka23.ru/image/".$resImg['photoPath']);
                    }
					//"complex_attributes" => array(array("attributes" => array(array("id" => 11254, "complex_id" => 100001, "values" => array((json_decode($richcontent))))))), 
					$array_lwhwPackage = explode(";", $res["lwhwPackage"]);
                    array_push($atribute, array("attributes" => $atr, 
                        "description_category_id" => (int)$res["category_id"], 
                        "color_image" => "", 
                        "complex_attributes" => array(), 
                        "currency_code" => "RUB", 
                        "depth" => (int)$array_lwhwPackage[0], 
                        "dimension_unit" => "mm", 
                        "height" => (int)$array_lwhwPackage[2], 
                        "images" => ($stringImg), 
                        "images360" => array(), 
                        "name" => $res["type"]." ".$res["brand"], 
                        "offer_id" => $res["vendorCode"].""."_".$res["size"], 
                        "price" => $res["price"], 
                        "primary_image" => "", 
                        "vat" => "0", 
                        "weight" => (int)$array_lwhwPackage[3], 
                        "weight_unit" => "g", 
                        "width" => (int)$array_lwhwPackage[1]));
						$qr1 = "UPDATE `Shoes` SET `import_ozon`= 1, `permanently_ozon` = 1 WHERE vendorCode = '{$res["vendorCode"]}';";
					}
					
					$query1 = mysqli_query($conn, $qr1);

					$qrsize = "SELECT oz_SizeId_4298 FROM `Size` WHERE shoesId = {$_GET['shoesId']}";
					$querysize = mysqli_query($conn, $qrsize);
					$stringSize = "";
					while ($res = mysqli_fetch_assoc($querysize)) {
						$stringSize .= '{
							"dictionary_value_id": '. $res['oz_SizeId_4298'].'
						},';
					}

					$qrimg = "SELECT photoPath FROM `PIctureToProduct` WHERE productId = {$_GET['shoesId']} Order BY status DESC";
					$queryimig = mysqli_query($conn, $qrimg);
					$stringImg = "";
					while ($res = mysqli_fetch_assoc($queryimig)) {
						$stringImg .= '"https://api.obuvashka23.ru/image/'. ($res['photoPath']).'",';
					}

					http_response_code(200);
				
					$items = array("items" => $atribute);
					$ch = curl_init('https://api-seller.ozon.ru/v3/product/import');
					curl_setopt($ch, CURLOPT_HTTPHEADER, 
					array('Content-Type:application/json',  'Client-Id: '.$urltoken, 'Api-Key: '.$headertoken));
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($items, JSON_UNESCAPED_UNICODE)); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_HEADER, false);
					$res = curl_exec($ch);
					curl_close($ch);
					$res = json_decode($res, JSON_UNESCAPED_UNICODE);

					$task = array("task_id" => (string)$res["result"]["task_id"]);

					  $ch1 = curl_init('https://api-seller.ozon.ru/v1/product/import/info');
					  curl_setopt($ch1, CURLOPT_HTTPHEADER, 
					 array('Content-Type:application/json',  'Client-Id: '.$urltoken, 'Api-Key: '.$headertoken));
					  curl_setopt($ch1, CURLOPT_POST, 1);
					  curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($task, JSON_UNESCAPED_UNICODE)); 
					 curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
					 curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
					 curl_setopt($ch1, CURLOPT_HEADER, false);
					 $res = curl_exec($ch1);
					 curl_close($ch1);
					 
					 $task_info = json_decode($res, JSON_UNESCAPED_UNICODE);
					$task_array = $task_info["result"]["items"];
					$barcode = [];
					 for($i = 0; $i < count($task_array); $i++){
						sleep(0,1);
						$stocks = array("stocks" => array(array("offer_id" => $task_array[$i]["offer_id"], "stock" => (int)1, "warehouse_id" => (int)$stockkey)));
						$ch1 = curl_init('https://api-seller.ozon.ru/v2/products/stocks');
						curl_setopt($ch1, CURLOPT_HTTPHEADER, 
					   	array('Content-Type:application/json',  'Client-Id: '.$urltoken, 'Api-Key: '.$headertoken));
						curl_setopt($ch1, CURLOPT_POST, 1);
						curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($stocks, JSON_UNESCAPED_UNICODE)); 
					   	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
					   	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
					   	curl_setopt($ch1, CURLOPT_HEADER, false);
					   	$res = curl_exec($ch1);
					   	curl_close($ch1);
					   	array_push($barcode, json_decode($res));
					 }
					 //$barcodeGen = $barcode[0]->result[0]->product_id;
					 echo json_encode($items, JSON_UNESCAPED_UNICODE);
				}
				if($type == $this->token && $params[2] == 'GetImportYandex'){


					$connkey = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					$qrkey = "SELECT * FROM `Keys` WHERE id = {$_POST["key"]}";
					$querykey = mysqli_query($connkey, $qrkey);
					$urltoken = "";
					$headertoken = "";
					$stockkey = "";
					while ($res = mysqli_fetch_assoc($querykey)) {
						if((int)$res["IdMarketplace"] == 1){
							$urltoken = $res["UrlToken"];
							$headertoken = $res["TokenHeder"];
							$stockkey = $res["stock"];
						}
					}

					$conn = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					if(isset($_POST["brandId"])){
						$condition = " and Shoes.brand = {$_POST["brandId"]}";
					}elseif(isset($_POST["productId"])){
						$condition = " and Shoes.id = {$_POST["productId"]}";
					}
					$qr = "SELECT Shoes.id as id, Shoes.discount as discount, Size.num as num, Shoes.vendorCode, Shoes.description, Size.size as size, Size.oz_SizeId_4298 as sizeCode, Shoes.price, Shoes.discount, Type.title as type, Type.oz_category_id as category_id, Type.oz_TyoeId_8229 as type_id, Season.title as season, Season.title as season, Brands.title as brand, Brands.oz_brandId_31 as brand_id, Gender.gender as gender, Gender.oz_GenderId_9163 as gender_id, Style.oz_styleId_4501 as style_id, CountryBrand.oz_countryId_9248 as country_9248, Country.oz_countryId_4389 as country_4389, Color.oz_colorId_10096 as color_id, InsoleMaterial.title as insoleMaterial, Materials.title as material, Outmaterial.oz_outmaterial_4305 as outmaterial_id, Color.title as color, Style.title as style , Shoes.lwhwPackage, TNVED.oz_TNVEDId_22232 as tnved, Brands.doc, Country.name as country_name FROM Size,  `Type`, `Shoes`, `TNVED`, `Season`, `Brands`, `Gender`, `CountryBrand`, `Country`, `Color`, `Materials`, `InsoleMaterial`, `Outmaterial`, `Style` WHERE Shoes.typeId = Type.id AND Shoes.seasonId = Season.id AND Shoes.brand = Brands.id AND Shoes.genderId = Gender.id and Size.shoesId = Shoes.id AND CountryBrand.id = Brands.countryId AND Country.id = Brands.countryManufId AND Color.id = Shoes.colorId AND Materials.id = Shoes.materials AND Shoes.insoleMaterialId = InsoleMaterial.id AND Shoes.outmaterialId = Outmaterial.id and Shoes.tnvedId = TNVED.id and Style.id = Shoes.styleId";
					//$qr = "SELECT Shoes.id as id, Size.num as num, Shoes.vendorCode, Shoes.description, Size.size as size, Size.oz_SizeId_4298 as sizeCode, Shoes.price, Shoes.discount, Type.title as type, Type.oz_category_id as category_id, Type.oz_TyoeId_8229 as type_id, Season.title as season, Season.oz_SeasonId_4495 as season_id, Brands.title as brand, Brands.oz_brandId_31 as brand_id, Gender.gender as gender, Gender.oz_GenderId_9163 as gender_id, Style.oz_styleId_4501 as style_id, CountryBrand.oz_countryId_9248 as country_9248, Country.oz_countryId_4389 as country_4389, Color.name as color, InsoleMaterial.oz_insoleId_4516 as insoleMaterial_id, Materials.oz_MaterialId_4496 as material_id, Outmaterial.oz_outmaterial_4305 as outmaterial_id, Color.title as color, Style.title as style , Shoes.lwhwPackage, TNVED.oz_TNVEDId_22232 as tnved, Brands.doc, Country.name as country_name FROM Size,  `Type`, `Shoes`, `TNVED`, `Season`, `Brands`, `Gender`, `CountryBrand`, `Country`, `Color`, `Materials`, `InsoleMaterial`, `Outmaterial`, `Style` WHERE Shoes.typeId = Type.id AND Shoes.seasonId = Season.id AND Shoes.brand = Brands.id AND Shoes.genderId = Gender.id and Size.shoesId = Shoes.id AND CountryBrand.id = Brands.countryId AND Country.id = Brands.countryManufId AND Color.id = Shoes.colorId AND Materials.id = Shoes.materials AND Shoes.insoleMaterialId = InsoleMaterial.id AND Shoes.outmaterialId = Outmaterial.id and Shoes.tnvedId = TNVED.id and Style.id = Shoes.styleId  {$condition}";
					$query = mysqli_query($conn, $qr);
					$arraytoproducts = [];

                    $atribute = array();
                    $size = array();
					$connect_for_import = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					while ($res = mysqli_fetch_assoc($query)) {
						$queryList[] = $res;
                        if($res["id"] == $_POST["productId"]){
							$connect = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$qr1 = "UPDATE `Shoes` SET `import_yandex`= 1, `permanently_yandex` = 1 WHERE vendorCode = '{$res["vendorCode"]}';";
							$query1 = mysqli_query($connect, $qr1);
		
							array_push($size, array($res["vendorCode"].""."_".$res["size"], (int)$res["num"]));
						//marketCategoryId type
						$qrimg = "SELECT photoPath FROM `PIctureToProduct` WHERE productId = {$res['id']} Order BY status DESC";
						$queryimig = mysqli_query($conn, $qrimg);
						$stringImg = array();
						$price = 0;
						while ($resImg = mysqli_fetch_assoc($queryimig)) {
							$price = $res["price"];
							array_push($stringImg,  str_replace(" ", "%20", "https://api.obuvashka23.ru/image/".$resImg['photoPath']));
						}
						$gender = $res["gender"];
						if($gender == "Для всех"){
							$gender = "унисекс";
						}
						if($gender == "Для девочек"){
							$gender = "женский";
						}
						if($gender == "Для мальчиков"){
							$gender = "мужской";
						}
						$array_lwhwPackage = explode(";", $res["lwhwPackage"]);
						array_push($atribute, array(
							"offer" =>[
								"offerId" => $res["vendorCode"].""."_".$res["size"],
								"name" => $res["type"]." ".$res["brand"]." ".$res["color"],
								"marketCategoryId" => 53253411,
								"category" => $res["type"],
								"pictures" => ($stringImg),
								"vendor" => $res["brand"],
								"description" => $res["description"],
								"manufacturerCountries" => array($res["country_name"]),
								"weightDimensions" => [
									"length" => 10,
									"width" => 10,
									"height" => 10,
									"weight" =>10,
								],
								"vendorCode" => $res["vendorCode"],
								"type" => "DEFAULT",
								"downloadable" => false,
								"adult" => false,
								"age" => [
									"value" => 0,
									"ageUnit" => "YEAR",
								],
								"params" => [
									[
										"name" => "Размерная сетка",
										"value" => "RU",
									],
									[
										"name" => "Материал верха",
										"value" => mb_strtolower($res["material"]),
									],
									[
										"name" => "Сезон",
										"value" => mb_strtolower($res["season"]),
									],
									[
										"name" => "Материал подошвы",
										"value" => mb_strtolower($res["insoleMaterial"]),
									],
									[
										"name" => "Артикул",
										"value" => $res["vendorCode"],
									],
									[
										"name" => "Размер на бирке",
										"value" => $res["size"],
									],
									[
										"name" => "Размер в сетке",
										"value" => (int)$res["size"],
									],
									[
										"name" => "Цвет товара для фильтра",
										"value" => $res["color"],
									],
									[
										"name" => "Цвет товара для карточки",
										"value" => $res["color"],
									],
									[
										"name" => "Номер карточки",
										"value" => $res["id"],
									],
									[
										"name" => "Пол",
										"value" => mb_strtolower($gender),
									],
									[
										"name" => "Тип",
										"value" => mb_strtolower($res["type"]),
									],
								],
								
								"purchasePrice" => [
									"value" => (int)$res["price"] * 0.6,
									"currencyId" => "RUR",
								],
								
								"additionalExpenses" => [
									"value" => (int)$res["price"] * 0.5,
									"currencyId" => "RUR",
								],
							
								"cofinancePrice" => [
									"value" => (int)$res["price"] * 0.3,
									"currencyId" => "RUR",
								]
						]));
						
						$offerMapping = [
							"offer" => $atribute,
						];
					}
				}
					$data = [
							"offerMappings" => $atribute,
						];
						
						$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					$ch = curl_init('https://api.partner.market.yandex.ru/businesses/'.$urltoken.'/offer-mappings/update');
					curl_setopt($ch, CURLOPT_HTTPHEADER, 
					array('Content-Type:application/json', 'Authorization: Bearer '.$headertoken));
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $json); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_HEADER, false);
					$res = curl_exec($ch);
					curl_close($ch);

					foreach($atribute as $value){
					$ch = curl_init('https://api.partner.market.yandex.ru/businesses/'.$urltoken.'/offer-prices/updates');
					curl_setopt($ch, CURLOPT_HTTPHEADER, 
					array('Content-Type:application/json', 'Authorization: Bearer '.$headertoken));
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, 
				'{
					"offers": [
					  {
						"offerId": "'.$value["offer"]["offerId"].'",
						"price": {
						  "value": '.$price.',
						  "currencyId": "RUB"
						}
					  }
					]
				  }'
				); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_HEADER, false);
					$res = curl_exec($ch);
					curl_close($ch);
					}
				$endresult = "";
					foreach($size as $valuesize){
						$ch = curl_init('https://api.partner.market.yandex.ru/campaigns/'.$stockkey.'/offers/stocks');
						curl_setopt($ch, CURLOPT_HTTPHEADER, 
						array('Content-Type:application/json', 'Authorization: Bearer '.$headertoken));
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, 
					'{
						"skus": [
						  {
							"sku": "'.$valuesize[0].'",
							"items": [
							  {
								"count": '.$valuesize[1].'
							  }
							]
						  }
						]
					  }}'
					); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_HEADER, false);
						$res = curl_exec($ch);
						curl_close($ch);
						$endresult = $res;
					}
				echo $endresult;
				}
				if($type == 'GetPublicKey'){
					$pub_key = file_get_contents('/home/c/cx07681/api.obuvashka23/public_key.pem'); 
					$enc = urlencode($pub_key);
					echo '[{"Public_key":"'.$enc.'"}]'; 
				}
				if($type == 'GetSessionRSA'){
					$priv = openssl_get_privatekey(file_get_contents('/home/c/cx07681/api.obuvashka23/private_key.pem'));
					$client_public_key = $_POST['ClientPublicKey'];
					  $mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
					 if(isset($_POST['ipAddressRSA'])){
					  	$priv = openssl_get_privatekey(file_get_contents('/home/c/cx07681/api.obuvashka23/private_key.pem'));
					 	$data = $_POST['ipAddressRSA'];
						openssl_private_decrypt(base64_decode($data), $ipAddress, $priv);
						$qr='SELECT Session.id as id, adminId, ipAddress, Administrarion.token as  token, timeEnter FROM `Session`, `Administrarion` WHERE ipAddress = "'.$ipAddress.'" AND Session.adminId = Administrarion.id';
						$query = $mysqli->query($qr);
						while ($res = $query->fetch_assoc()) {
							$queryList[] = $res;
						}
						http_response_code(200);

						$pku = openssl_pkey_get_public(urldecode($client_public_key));
						$pk  = openssl_get_publickey($pku);
						openssl_public_encrypt($queryList[0]["token"], $encrypted, $pk);
						$queryList[0]["token"] = base64_encode($encrypted);

						openssl_public_encrypt($queryList[0]["ipAddress"], $queryList[0]["ipAddress"], $pk);
						$queryList[0]["ipAddress"] = base64_encode($queryList[0]["ipAddress"]);

						echo json_encode($queryList, JSON_UNESCAPED_UNICODE);
					}
				}
				if($type == 'GetAdministrationRSA'){
					$priv = openssl_get_privatekey(file_get_contents('/home/c/cx07681/api.obuvashka23/private_key.pem'));
					$client_public_key = $_POST['ClientPublicKey'];

					if(isset($_POST['loginRSA']) && isset($_POST['passwordRSA'])){
						$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
						$login = $mysqli ->real_escape_string($_POST['loginRSA']);
						$password = $mysqli ->real_escape_string($_POST['passwordRSA']);

						openssl_private_decrypt(base64_decode($login), $login, $priv);
						openssl_private_decrypt(base64_decode($password), $password, $priv);

						$qr = 'SELECT * FROM `Administrarion` WHERE login = "'.$login.'" and password = "'.$password.'"';
					 	$query = $mysqli->query($qr);
					 	while ($res = $query->fetch_assoc()) {
					 		$queryList[] = $res;
					 	}
					 http_response_code(200);

					 $pku = openssl_pkey_get_public(urldecode($client_public_key));
					 $pk  = openssl_get_publickey($pku);
					
					 openssl_public_encrypt($queryList[0]["login"], $queryList[0]["login"], $pk);
						$queryList[0]["login"] = base64_encode($queryList[0]["login"]);

						openssl_public_encrypt($queryList[0]["password"], $queryList[0]["password"], $pk);
						$queryList[0]["password"] = base64_encode($queryList[0]["password"]);						
						
						openssl_public_encrypt($queryList[0]["token"], $queryList[0]["token"], $pk);
						$queryList[0]["token"] = base64_encode($queryList[0]["token"]);

					 echo json_encode($queryList, JSON_UNESCAPED_UNICODE);
					}
				}

				if($type == $this->token){
					if($params[2] == 'CreateNewRSA'){
					$config = [ 
						"private_key_bits" => 2048, 
						"private_key_type" => OPENSSL_KEYTYPE_RSA, 
					]; 
					  
					$keypair = openssl_pkey_new($config); 
					  
					openssl_pkey_export($keypair, $private_key); 
					  
					$public_key = openssl_pkey_get_details($keypair); 
					$public_key = $public_key["key"]; 
					  
					$pr = "" . $private_key . ""; 
					$pb = "" . $public_key . ""; 

						$myfile = fopen("private_key.pem", "w");
						fwrite($myfile, $pr);
						fclose($myfile);

						$myfile = fopen("public_key.pem", "w");
						fwrite($myfile, $pb);
						fclose($myfile);
					}
				}
				if($type == 'AddSessionRSA'){
					$priv = openssl_get_privatekey(file_get_contents('/home/c/cx07681/api.obuvashka23/private_key.pem'));
					openssl_private_decrypt(base64_decode($_POST['token']), $tokenPOST, $priv);
					openssl_private_decrypt(base64_decode($_POST['ipAddress']), $ipAddress, $priv);

					$token = $this->token;    

					if($tokenPOST == $token){                                        
					$qr="INSERT INTO `Session`(`adminId`, `ipAddress`) VALUES ({$_POST['adminId']},'{$ipAddress}')";
					$this->connDB($qr);
					}
				}
				if($type == $this->token){
					ini_set('upload_max_filesize', '1M');
				if($params[2] == 'Image' && isset($_POST["productId"]) || isset($_POST["status"])){//|| isset($_POST["productId"]) || isset($_POST["status"])
					if ($_FILES && $_FILES["filename"]["error"]== UPLOAD_ERR_OK && $_FILES["filename"]["type"] == "image/jpeg" || $_FILES["filename"]["type"] == "image/png")
						{
							$uploaddir = '/home/c/cx07681/api.obuvashka23/public_html/image/';
							$uploadfile = $uploaddir . basename($_FILES["filename"]["name"]);
    						move_uploaded_file($_FILES["filename"]["tmp_name"], $uploadfile);
							$qr = "INSERT INTO `PIctureToProduct`(`productId`, `photoPath`, `status`) VALUES ('{$_POST["productId"]}','{$_FILES["filename"]["name"]}','{$_POST["status"]}')";
							$this->connDB($qr);
    						echo "Файл загружен";
						}
					}
				}
				if($type == $this->token){
					if($params[2] == 'ImageAccessories' || isset($_POST["accessoriesId"]) || isset($_POST["statusAccess"])){
						if ($_FILES && $_FILES["filenameAccess"]["error"]== UPLOAD_ERR_OK && $_FILES["filenameAccess"]["type"] == "image/jpeg" || $_FILES["filenameAccess"]["type"] == "image/png")
							{
								$uploaddir = '/home/c/cx07681/api.obuvashka23/public_html/image/';
								$uploadfile = $uploaddir . basename($_FILES["filenameAccess"]["name"]);
								move_uploaded_file($_FILES["filenameAccess"]["tmp_name"], $uploadfile);
								$qr = "INSERT INTO `PIctureToAccessories`(`productId`, `photoPath`, `status`) VALUES ('{$_POST["accessoriesId"]}','{$_FILES["filenameAccess"]["name"]}','{$_POST["statusAccess"]}')";
								$this->connDB($qr);
								echo "Файл загружен";
							}
						}
					}
					if($type == $this->token){

						if($params[2] == 'ImageBag' || isset($_POST["bagId"]) || isset($_POST["statusBag"])){
							if ($_FILES && $_FILES["filenameBag"]["error"]== UPLOAD_ERR_OK && $_FILES["filenameBag"]["type"] == "image/jpeg" || $_FILES["filenameBag"]["type"] == "image/png")
								{
									$uploaddir = '/home/c/cx07681/api.obuvashka23/public_html/image/';
									$uploadfile = $uploaddir . basename($_FILES["filenameBag"]["name"]);
									move_uploaded_file($_FILES["filenameBag"]["tmp_name"], $uploadfile);
									$qr = "INSERT INTO `PIctureToBag`(`productId`, `photoPath`, `status`) VALUES ('{$_POST["bagId"]}','{$_FILES["filenameBag"]["name"]}','{$_POST["statusBag"]}')";
									$this->connDB($qr);
									echo "Файл загружен";
								}
							}
						}
				
				if($type == $this->token){
					if($params[2] == "AddBrand"){
						//$this->addPrimaryTable($_POST['title'], $_POST['description'], "Brands", 0);oz_brandId_31
						$this->addBrand($_POST['title'], $_POST['description'], $_POST['countryManufId'], $_POST['countryId'], $_POST['oz_brandId_31'], 0);
					}
				}
				if($type == 'GetAdministration'){
					if(isset($_POST['login']) && isset($_POST['password'])){
						$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
						$login = $mysqli ->real_escape_string($_POST['login']);
						$password = $mysqli ->real_escape_string($_POST['password']);
						$qr = 'SELECT * FROM `Administrarion` WHERE login = "'.$login.'" and password = "'.$password.'"';
					 	$query = $mysqli->query($qr);
					 	while ($res = $query->fetch_assoc()) {
					 		$queryList[] = $res;
					 	}
					 http_response_code(200);
					 echo json_encode($queryList, JSON_UNESCAPED_UNICODE);
					}
				}
				if($type == 'GetSession'){
					if(isset($_POST['ipAddress'])){
						$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
						$ipAddress = $mysqli ->real_escape_string($_POST['ipAddress']);
						$qr='SELECT Session.id as id, adminId, ipAddress, Administrarion.token as  token, timeEnter FROM `Session`, `Administrarion` WHERE ipAddress = "'.$ipAddress.'" AND Session.adminId = Administrarion.id';
						$query = $mysqli->query($qr);
						while ($res = $query->fetch_assoc()) {
							$queryList[] = $res;
						}
						http_response_code(200);
						echo json_encode($queryList, JSON_UNESCAPED_UNICODE);
					}
				}

				

				if($type == 'AddSession'){
					$token = $this->token;    
					if($_POST['token'] == $token){                                        
					$qr="INSERT INTO `Session`(`adminId`, `ipAddress`) VALUES ({$_POST['adminId']},'{$_POST['ipAddress']}')";
					$this->connDB($qr);
					}
				}
			if($type == $this->token){
				if($params[2] == 'Orders'){
					$qr = "SELECT * FROM `Order` ORDER BY `id` DESC";
					$this->connDB($qr);
				}
			}

			if($type == $this->token){
			if($params[2] == 'faq'){
				$qr = "SELECT * FROM `faq` ORDER BY `id` ASC";
				$this->connDB($qr);
			}
		}
		if($type == $this->token){
			if($params[2] == 'keys'){
				$qr = "SELECT Keys.id, IdMarketplace, IsTest, Title FROM `Keys`, `Marketplaces` where Keys.IdMarketplace = Marketplaces.id";
				$this->connDB($qr);
			}
		}
				if($type == $this->token){
					if($params[2] == "AddGender"){
						$this->addGender($_POST['gender'], 0);
					}
				}
				if($type == $this->token){
					if($params[2] == "AddType"){
						$this->addPrimaryTable($_POST['title'], $_POST['description'], "Type", 0);
					}
				}
				if($type == $this->token){
					if($params[2] == "AddSeason"){
						$this->addPrimaryTable($_POST['title'], $_POST['description'], "Season", 0);
					}
				}
				if($type == $this->token){
					if($params[2] == "AddMaterial"){
						$this->addPrimaryTable($_POST['title'], $_POST['description'], "Materials", 0);
					}
				}
				if($type == $this->token){
					if($params[2] == "AddAccessories"){
						$this->addAccessories($_POST["vendorCode"], $_POST["title"], $_POST["price"], $_POST["typeId"], $_POST["description"], $_POST["sizeHead"], $_POST["color"], $_POST["discount"], $_POST["popularity"], $_POST["num"], 0);
					}
				}
				if($type == $this->token){
					if($params[2] == "AddBag"){
						$this->addBag($_POST["vendorCode"], $_POST["title"], $_POST["price"], $_POST["brandId"], $_POST["description"], $_POST["genderId"], $_POST["color"], $_POST["discount"], $_POST["popularity"], $_POST["materialOutside"], $_POST["materialInside"], $_POST["num"], 0);
					}
				}
				if($type == $this->token){
					if($params[2] == "AddShoesSize"){
						$this->addShoesSize($_POST["shoesId"], $_POST["size"], $_POST["num"], $_POST['oz_SizeId_4298'], 0);
					}
				}
				if($type == $this->token){
					if($params[2] == "AddShoes"){
						$this->addShoes($_POST["vendorCode"], $_POST["title"], $_POST["price"], $_POST["brandId"], 
						$_POST["description"], $_POST["genderId"], $_POST["discount"], $_POST["popularity"], 
						$_POST["materialOutside"], $_POST["materialInside"], $_POST["markdown"], $_POST["seasonId"], 
						$_POST["typeId"], $_POST["insoleMaterial"], $_POST["timeToAdd"], $_POST["colorId"], $_POST["styleId"], 
						$_POST["outmaterialId"], $_POST["insoleMaterialId"], $_POST["lwhwPackage"],  $_POST["tnvedId"], 
						$_POST["linkToOzon"], $_POST["linkToYandex"], 0);
					}
				}
			} elseif($this->method === 'PATCH'){
				if($type == $this->token){
					$data = file_get_contents('php://input');
					$put = json_decode($data, true);
						if($params[2] == "AddShoes"){
							$this->addShoes($put["vendorCode"], $put["title"], $put["price"], $put["brand"], $put["description"], $put["genderId"], $put["discount"], $put["popularity"], $put["outmaterials"], $put["materials"], $put["markdown"], $put["seasonId"], $put["typeId"], $put["insoleMaterial"],$put["timeToAdd"], $put["colorId"], $put["styleId"], $put["outmaterialId"], $put["insoleMaterialId"], $put["lwhwPackage"], $put["tnvedId"], $put["linkToOzon"], $put["linkToYandex"], $put["id"]);//$lwhwPackage
						}		
					
						if($params[2] == "AddBrand"){
							$this->addBrand($put['title'], $put['description'], $put['countryManufId'], $put['countryId'], $put['oz_brandId_31'], $put['id']);
						}
						if($params[2] == "AddGender"){
							$this->addGender($put['gender'], $put["id"]);
						}		
					
						if($params[2] == "AddType"){
							$this->addPrimaryTable($put['title'], $put['description'], "Type", $put["id"]);
						}		
					
						if($params[2] == "AddSeason"){
							$this->addPrimaryTable($put['title'], $put['description'], "Season", $put["id"]);
						}		
					
						if($params[2] == "AddMaterial"){
							$this->addPrimaryTable($put['title'], $put['description'], "Materials", $put["id"]);
						}		
					
						if($params[2] == "AddAccessories"){
							$this->addAccessories($put["vendorCode"], $put["title"], $put["price"], $put["typeId"], $put["description"], $put["sizeHead"], $put["color"], $put["discount"], $put["popularity"], $put["num"], $put["id"]);
					}
					if($params[2] == "AddBag"){
						$this->addBag($put["vendorCode"], $put["title"], $put["price"], $put["brandId"], $put["description"], $put["genderId"], $put["color"], $put["discount"], $put["popularity"], $put["materialOutside"], $put["materialInside"], $put["num"], $put["id"]);
				}
				if($params[2] == "AddShoesSize"){
					$this->addShoesSize($put["shoesId"], $put["size"], $put["num"], $put["oz_SizeId_4298"], $put["id"]);
				}

				}
			} elseif($this->method === 'DELETE'){
				if($type == $this->token){
					$data = file_get_contents('php://input');
					$deldata = json_decode($data, true);
					if($type == $this->token){
						if($params[2] == "DelBrand"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Brands` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelOrder"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Order` WHERE  `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelAccessories"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Accessories` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelImageShoes"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$filename = $deldata["filename"];
							$uploaddir = "/home/c/cx07681/api.obuvashka23/public_html/image/$filename";
							unlink($uploaddir);
							$sql = "DELETE FROM `PIctureToProduct` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelImageAccessories"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$filename = $deldata["filename"];
							$uploaddir = "/home/c/cx07681/api.obuvashka23/public_html/image/$filename";
							unlink($uploaddir);
							$sql = "DELETE FROM `PIctureToAccessories` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelImageBags"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$filename = $deldata["filename"];
							$uploaddir = "/home/c/cx07681/api.obuvashka23/public_html/image/$filename";
							unlink($uploaddir);
							$sql = "DELETE FROM `PIctureToBag` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						
						if($params[2] == "DelBag"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Bag` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}

						if($params[2] == "DelMaterials"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Materials` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}

						if($params[2] == "DelSeason"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Season` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelShoes"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Shoes` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelSize"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Size` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelType"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Type` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
						if($params[2] == "DelGender"){
							$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
							$id = $deldata["id"];
							$sql = "DELETE FROM `Gender` WHERE `id` = $id";
							if ($mysqli ->query($sql) === TRUE) {
								echo json_encode("Record is delete");
							}
						}
					}
				}
			} 
			
		}
		
		public function connDB($qr){
			$conn = mysqli_connect($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
			$query = mysqli_query($conn, $qr);
			while ($res = mysqli_fetch_assoc($query)) {
				$queryList[] = $res;
		}
		http_response_code(200);
		echo json_encode($queryList, JSON_UNESCAPED_UNICODE);

		}
		public function addGender($gender, $id){
			$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
			if ($mysqli->connect_error) {
				http_response_code(500);
				echo json_encode("Connection failed: " . $mysqli->connect_error, JSON_UNESCAPED_UNICODE);
			}
			$sql = "";
			if($id == 0){
				$sql = "INSERT INTO `Gender`(`gender`) VALUES ('$gender')";
			}else{
				$sql = "UPDATE `Gender` SET `gender`='$gender' WHERE `id` = $id";
			}
			if ($mysqli ->query($sql) === TRUE) {
				http_response_code(201);
				if($id == 0){
					$this->connDB("SELECT * FROM `Gender` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1");
				} else{
					$this->connDB("SELECT * FROM `Gender` WHERE id = $id");
				}			} else {
				http_response_code(500);
				echo json_encode("Error: " . $sql . "<br>" . $mysqli->error, JSON_UNESCAPED_UNICODE);
			}
			
			$mysqli->close();
		}
		public function addPrimaryTable($title, $description, $table, $id){
			$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
			if ($mysqli->connect_error) {
				http_response_code(500);
				echo json_encode("Connection failed: " . $mysqli->connect_error, JSON_UNESCAPED_UNICODE);
			}
			$sql = "";
			if($id == 0){
				$sql = "INSERT INTO `$table`(`title`, `description`) VALUES ('$title','$description')";
			}else{
				$sql = "UPDATE `$table` SET `title`='$title',`description`='$description' WHERE `id` = $id ";
			}

			if ($mysqli ->query($sql) === TRUE) {
				http_response_code(201);
				if($id == 0){
					$this->connDB("SELECT * FROM `$table` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1");
				} else{
					$this->connDB("SELECT * FROM `$table` WHERE id = $id");
				}
			} else {
				http_response_code(500);
				echo json_encode("Error: " . $sql . "<br>" . $mysqli->error, JSON_UNESCAPED_UNICODE);
			}
			$mysqli->close();
		}

		public function addBrand($title, $description, $countryManufId, $countryId, $oz_brandId_31, $id){
			$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
			if ($mysqli->connect_error) {
				http_response_code(500);
				echo json_encode("Connection failed: " . $mysqli->connect_error, JSON_UNESCAPED_UNICODE);
			}
			$sql = "";
			if($id == 0){
				$sql = "INSERT INTO `Brands`(`title`, `description`, `countryManufId`, `countryId`, `oz_brandId_31`) VALUES ('$title','$description', $countryManufId, $countryId, $oz_brandId_31)";
			}else{
				$sql = "UPDATE `Brands` SET `title`='$title',`description`='$description', `countryManufId` = $countryManufId, `countryId` = $countryId, `oz_brandId_31` = $oz_brandId_31  WHERE `id` = $id ";
			}

			if ($mysqli ->query($sql) === TRUE) {
				http_response_code(201);
				if($id == 0){
					$this->connDB("SELECT * FROM `Brands` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1");
				} else{
					$this->connDB("SELECT * FROM `Brands` WHERE id = $id");
				}
			} else {
				http_response_code(500);
				echo json_encode("Error: " . $sql . "<br>" . $mysqli->error, JSON_UNESCAPED_UNICODE);
			}
			$mysqli->close();
		}


		public function addAccessories($vendorCode, $title, $price, $typeId, $description, $sizeHead, $color, $discount, $popularity, $num, $id){
			$discount = $discount / 100;
			$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
			if ($mysqli->connect_error) {
				http_response_code(500);
				echo json_encode("Connection failed: " . $mysqli->connect_error, JSON_UNESCAPED_UNICODE);
			}
			$sql = "";
			if($id == 0){
				$sql = "INSERT INTO `Accessories`(`vendorCode`, `title`, `price`, `typeId`, `description`, `sizeHead`, `color`, `discount`, `popularity`, `num`) 
			VALUES ('$vendorCode','$title', $price, $typeId,'$description', $sizeHead,'$color',$discount, $popularity, $num)";
			}else{
				$sql = "UPDATE `Accessories` SET `vendorCode`='$vendorCode',`title`='$title',`price`= $price,`typeId`=$typeId,`description`='$description',`sizeHead`=$sizeHead,`color`='$color',`discount`= $discount,`popularity`= $popularity, `num`=$num WHERE `id` = $id";
			}
			
			if ($mysqli ->query($sql) === TRUE) {
				http_response_code(201);
				if($id == 0){
					$this->connDB("SELECT * FROM `Accessories` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1");
				}else{
					$this->connDB("SELECT * FROM `Accessories` WHERE `id` = $id");
				}
			} else {
				http_response_code(500);
				echo json_encode("Error: " . $sql . "<br>" . $mysqli->error, JSON_UNESCAPED_UNICODE);
			}
			$mysqli->close();
		}

		public function addBag($vendorCode, $title, $price, $brandId, $description, $genderId, $color, $discount, $popularity, $materialOutside, $materialInside, $num, $id){
			$discount = $discount / 100;
			$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
			if ($mysqli->connect_error) {
				http_response_code(500);
				echo json_encode("Connection failed: " . $mysqli->connect_error, JSON_UNESCAPED_UNICODE);
			}
			$sql = "";

			if($id == 0){
				$sql = "INSERT INTO `Bag`(`vendorCode`, `materialOutside`, `materialInside`, `color`, `price`, `brandId`, `genderId`, `title`, `description`, `discount`, `popularity`, `num`)
				VALUES ('$vendorCode', $materialOutside, $materialInside,'$color', $price, $brandId, $genderId,'$title','$description',$discount,$popularity,$num)";   
			}else{
				$sql = "UPDATE `Bag` SET `vendorCode`='$vendorCode',`materialOutside`= $materialOutside,`materialInside`=$materialInside,`color`='$color',`price`='$price',`brandId`=$brandId,`genderId`=$genderId,`title`='$title',`description`='$description',`discount`=$discount,`popularity`= $popularity, `num` = $num WHERE `id` = $id";
			}
			
			if ($mysqli ->query($sql) === TRUE) {
				http_response_code(201);
				if($id == 0){
					$this->connDB("SELECT * FROM `Bag` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1");
				}else{
					$this->connDB("SELECT * FROM `Bag` WHERE id = $id");
				}
				
			} else {
				http_response_code(500);
				echo json_encode("Error: " . $sql . "<br>" . $mysqli->error, JSON_UNESCAPED_UNICODE);
			}
			$mysqli->close();
		}
		public function addShoes($vendorCode, $title, $price, $brandId, $description, $genderId, $discount, $popularity, $materialOutside, $materialInside, $markdown, $seasonId, $typeId, $insoleMaterial, $timeToAdd, $colorId, $styleId, $outmaterialId, $insoleMaterialId, $lwhwPackage, $tnvedId, $linkToOzon, $linkToYandex, $id){
			$discount = $discount / 100;
			$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
			if ($mysqli->connect_error) {
				http_response_code(500);
				echo json_encode("Connection failed: " . $mysqli->connect_error, JSON_UNESCAPED_UNICODE);
			}
			$sql = "";
			if($id == 0){
				$sql = "INSERT INTO `Shoes`(`vendorCode`, `title`, `description`, `price`, `markdown`, `genderId`, `seasonId`, `typeId`, `brand`, `materials`, `outmaterial`, `discount`, `popularity`, `insoleMaterial`, `colorId`, `styleId`, `outmaterialId`, `insoleMaterialId`, `lwhwPackage`, `tnvedId`, `linkToOzon`,`linkToYandex`, `import_ozon`, `permanently_ozon`, `permanently_yandex`, `import_yandex`)
							VALUES ('$vendorCode','$title','$description', $price, $markdown, $genderId, $seasonId, $typeId, $brandId,$materialInside,'$materialOutside', $discount, $popularity, '$insoleMaterial', $colorId, $styleId, $outmaterialId, $insoleMaterialId, '$lwhwPackage', $tnvedId, '$limit','$linkToYandex' 0, 0, 0, 0)";
			}else{
				$sql = "UPDATE `Shoes` SET `vendorCode`='$vendorCode',`title`='$title',`description`='$description', `price`=$price,`markdown`=$markdown,`genderId`=$genderId,`seasonId`=$seasonId,`typeId`=$typeId, `outmaterialId` = $outmaterialId, `insoleMaterialId` = $insoleMaterialId,`linkToOzon` = '$linkToOzon' ,`linkToYandex`= '$linkToYandex', `import_ozon` = 0, `import_yandex` = 0,`tnvedId` = $tnvedId, `lwhwPackage` = '$lwhwPackage', `colorId` = $colorId, `styleId` = $styleId, `brand`=$brandId, `materials`=$materialInside,`outmaterial`='$materialOutside',`discount`=$discount,`popularity`=$popularity, `insoleMaterial`='$insoleMaterial', `timeToAdd` = '$timeToAdd' WHERE `id` = $id";
			}
			if ($mysqli ->query($sql) === TRUE) {
				http_response_code(201);
				if($id == 0){
					$this->connDB("SELECT * FROM `Shoes` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1");
				}else{
					$this->connDB("SELECT * FROM `Shoes` WHERE id = $id");
				}
			} else {
				http_response_code(500);
				echo json_encode("Error: " . $sql . "<br>" . $mysqli->error, JSON_UNESCAPED_UNICODE);
			}
			$mysqli->close();
		}
		public function addShoesSize($shoesId, $size, $num, $oz_SizeId_4298, $id){
			$mysqli = new mysqli($this->srv, $this->dbUser, $this->dbPass, $this->dbName);
			if ($mysqli->connect_error) {
				http_response_code(500);
				echo json_encode("Connection failed: " . $mysqli->connect_error, JSON_UNESCAPED_UNICODE);
			}
			$sql = "";
			if($id == 0){
				$sql = "INSERT INTO `Size`(`shoesId`, `size`, `num`, `oz_SizeId_4298`) VALUES ($shoesId, $size, $num, $oz_SizeId_4298)"; 
			} else{
				$sql = "UPDATE `Size` SET `shoesId`= $shoesId,`size`= $size,`num`= $num, `oz_SizeId_4298` = $oz_SizeId_4298 WHERE `id` = $id";
			}

			if ($mysqli ->query($sql) === TRUE) {
				http_response_code(201);
				if($id == 0){
					$this->connDB("SELECT * FROM `Size` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1");
				}else{
					$this->connDB("SELECT * FROM `Size` WHERE id = $id");
				}
			} else {
				http_response_code(500);
				echo json_encode("Error: " . $sql . "<br>" . $mysqli->error, JSON_UNESCAPED_UNICODE);
			}
			$mysqli->close();
		}
	}

$a = new API();
$a->main();



?>