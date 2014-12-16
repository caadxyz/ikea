<?php

require('Bdd/Bdd.php');

class Catalog {

    public $name;
    public $products;

    public function __construct($name) {
        $this->name = $name;
        $this->products = array();
    }

    public function getName() {
        return $this->name;
    }

    public function addProduct($product) {
        array_push($this->products, $product);
    }

    public function toJson($fileName) {
        try {
            if (file_exists($fileName)) {
                unlink($fileName);
            }
            $fp = fopen($fileName, 'a');
            fwrite($fp, json_encode($this->products));
            fclose($fp);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function persist() {
        try {
            $bdd = Bdd::getInstance()->getConnexion();
            for($i=0;$i<sizeof($this->products);$i++){
                $req=$bdd->prepare( "INSERT INTO product SET name=:name, 
                                                    description=:description, 
                                                    image=:image, 
                                                    keyword=:keyword, 
                                                    height=:height,
                                                    width=:width, 
                                                    nb_pakage=:nb_package, 
                                                    category=:category, 
                                                    weight=:weight" );

                $data = array(
		        'name' => $this->products[$i]->getName(),
		        'description' => $this->products[$i]->getDescription(),
		        'image' => $this->products[$i]->getImage(),
                'keyword' => $this->products[$i]->getKeyword(),
                'height' => $this->products[$i]->getHeight(),
                'width' => $this->products[$i]->getWidth(),
		        'nb_package' => $this->products[$i]->getNbPackage(),
		        'category' => $this->products[$i]->getCategory(),
                'weight' => $this->products[$i]->getWeight()
		        );
                $req->execute( $data ); 
            }
            
       } 
       catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}

?>
