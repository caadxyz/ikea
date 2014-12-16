<?php

require("catalog/Catalog.php");
require("catalog/Product.php");

/***** Crawling function *****/
function crawler($url) {
    $ch = curl_init();
    $timeout = 999;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

/***** Get categories *****/
try {
    $htmlCategoryLinks = crawler('http://www.ikea.com/fr/fr/catalog/categories/departments/living_room/livingroom_storage/');
    $dom = new DOMDocument();
    @$dom->loadHTML($htmlCategoryLinks);
    $categoryLinksArray = array();
    foreach ($dom->getElementsByTagName('a') as $link) {
        if ($link->getAttribute('class') == "categoryName") {
            array_push($categoryLinksArray, $link->getAttribute('href'));
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

/***** Get products list *****/
try {
    $productLinksArray = array();
    for ($i = 0; $i < sizeof($categoryLinksArray); $i++) {
        $htmlCategoryLink = crawler("http://www.ikea.com" . $categoryLinksArray[$i]);
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlCategoryLink);
        $product = array();
        foreach ($dom->getElementsByTagName('a') as $product) {
            if ($product->getAttribute('onclick') == "irwStatTopProductClicked();") {

                if (!in_array($product->getAttribute('href'), $productLinksArray)) {
                    array_push($productLinksArray, $product->getAttribute('href'));
                }
            }
        }
    }
    //var_dump($productLinksArray);    
} catch (Exception $e) {
    echo $e->getMessage();
}

/***** Get each product details *****/

try {
    $catalog = new Catalog("Ikea");    
    for ($i = 0; $i < sizeof($productLinksArray); $i++) {
        $seen = array();
        $htmlProductDetails = crawler("http://www.ikea.com" . $productLinksArray[$i]);
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlProductDetails);
        $map = array();
        foreach ($dom->getElementsByTagName('div') as $div) {
            if ($div->getAttribute('class') == "productName") {
                $name = preg_replace("/&#?[a-z0-9]{2,8};/i","",trim($div->nodeValue));
                if(!in_array($name,$seen)){
                    array_push($seen, $name);
                    $map["name"]=$name;
                    echo $name."\n";
                }
                
            } 
            if ($div->getAttribute('id') == "custMaterials") {
                $description = preg_replace("/&#?[a-z0-9]{2,8};/i","",trim($div->nodeValue));
                if(!in_array($description,$seen)){
                    array_push($seen, $description);
                    $map["description"] = $description;
                }
            }   
            if ($div->getAttribute('id') == "metric") {
                $metrics = trim($div->nodeValue);
                $expMetrics = explode("cm",$metrics);
                $map["height"] = isset($expMetrics[0]) ? trim($expMetrics[0]) : "";
                $map["width"] = isset($expMetrics[1]) ? trim($expMetrics[1]) : "";
            }
        }
        foreach ($dom->getElementsByTagName('img') as $img) {
            if ($img->getAttribute('id') == "productImg") {
                $image = trim($img->getAttribute('src'));
                if(!in_array($image,$seen)){
                    array_push($seen, $image);
                    $map["image"] = "http://www.ikea.com" .$image;
                }
            }
        }
        foreach ($dom->getElementsByTagName('a') as $cat) {
            if ($cat->getAttribute('id') == "gotoSims_lnk1") {
                $category = preg_replace("/&#?[a-z0-9]{2,8};/i","",trim($cat->nodeValue));
                if(!in_array($category,$seen)){
                    array_push($seen, $category);
                    $map["category"] = trim($category);
                }
            }
        }
        
        $prod = new Product($map);
        $catalog->addProduct($prod);
    }
    // Save the products in the database */
    $catalog->persist();
    //var_dump($productLinksArray);    
} catch (Exception $e) {
    echo "Exception raised : ";
    echo $e->getMessage();
}

?>

