<?php 
if (!defined('_PS_VERSION_')) {
    exit;
}
// blockproductscustom test purpose
// location Modules/blockproductscustom/blockproductscustom.php


class Blockproductscustom extends Module
{
	public function __construct()
	{
		// name: "nom de code" unique, mais ce n'est pas le nom affiché dans le backend du module.
		$this->name = 'blockproductscustom';
		// tab : indique à Prestashop la catégorie du module, la liste de toutes les catégories stockées 
		// dans le fichier Controllers Admin AdminModuleController.php.author , name et version : 
		// auteur, nom et version du module.
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Philippe Lavocat';
		// needs_instance: indique à Prestashop de créer une instance de votre variable lors de l'accès à la page des modules. 
		// Cela n'est généralement pas nécessaire, mais si votre module doit afficher un message ou enregistrer quelque chose 
		// lorsque la page des modules est active, vous devez changer cela en 1.
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Products List Module');
		$this->description = $this->l('Displays number of products in the home page.');
	}
	
	// Ajouter 2 hook pour afficher (ou non/ false)
	/* Probleme ici ??? */
	public function install()
	{
		/* ROOT ISSUE ? with php 8.2
		if (parent::install() == false || $this->registerHook('displayHome') == false 
		|| $this->registerHook('displayHeader') == false || Configuration::updateValue('DP_Number_of_Products', 4) == false)
			return false;
			
		return true;
		*/

		/* VERSION BASIQUE */
		return parent::install() 
        && $this->registerHook('displayHome') 
        && $this->registerHook('displayHeader')
		&& Configuration::updateValue('DP_Number_of_Products', 4);
	}

	public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName('DP_Number_of_Products');
    }
	
	// Hook N° 1
	// Add hook => add CSS in headers
	public function hookdisplayHeader($params)
	{
		/* DEPRECATED !!! */
		// $this->context->controller->addCSS($this->_path.'blockproductscustom.css', 'all'); // Why 'all' parameter ???
		$this->context->controller->registerStylesheet(
            'module-blockproductscustom-style',
            'modules/' . $this->name . '/views/css/blockproductscustom.css',
            [
                'media' => 'all',
                'priority' => 150
            ]
        );
	}
	
	// Hook N° 2
	// Add hook => récupérer les produits + load ds fichier template
	/* 
	La fonction de récupération des produits ne renvoie pas les images ou les liens des produits, 
	nous devons donc appeler quelques fonctions différentes et «construire» une gamme de produits.
	*/
	public function hookdisplayHome($params)
	{
		$languageId = (int) $this->context->language->id;
		$numberOfProducts = (int)(Configuration::get("DP_Number_of_Products"));
		$productsData = Product::getProducts($languageId, 0, $numberOfProducts, "id_product", "ASC");
		
		if (!$productsData)
			return "error";

		$products = array();
		$link = $this->context->link;
		
		// Préparer les données utiles pour la vue/hook :
		foreach($productsData as $product){
			$tmp = Product::getCover($product['id_product']);
			array_push($products, array(
				'name' => $product['name'],
				'author' => $product['manufacturer_name'],
				'desc' => $product['description_short'],
				'price' => $product['price'],
				'link' => $link->getProductLink(new Product($product['id_product'])),
				'image' => $link->getImageLink($product['link_rewrite'], $tmp['id_image'])
			));
		}
		$this->smarty->assign(array(
			'products' => $products
		));

		// return $this->display(__FILE__, 'blockproductscustom.tpl'); => only if no sub folder !!!
		// return $this->display($this->local_path, '/views/templates/hook/blockproductscustom.tpl');
		return $this->display(__FILE__, 'views/templates/hook/blockproductscustom.tpl');
	}
	
	// Get_content => interface de configuration pour le module 
	// Cette fonction construit le code HTML pour afficher un formulaire avec une boîte numérique et unbouton de sauvegarde.
	// Encore une fois, j'utilise la méthode $this->l() pour que vous puissiez traduire le module dans d'autres langues à l'avenir, 
	// si vous avez besoin de le faire.
	//
	// $this->l() => language translation, avec effet dynamique sur le frontend !
	public function getContent()
	{
		/* 
		Cette fonction est de voir si la valeur a été soumise. Par exemple, si la valeur numProds existe en tant que variable $_GET ou $_POST.
		Nous mettons ensuite à jour la propriété où nous avons stocké la valeur. La méthode Tools::getValue 
		*/
		if (Tools::isSubmit('numProds')){
			Configuration::updateValue('DP_Number_of_Products', (int)(Tools::getValue('numProds')));
		}
		
		$html = '<div style="width:400px; margin:auto">';
		$html .= ' <h2>' . $this->displayName . ' Settings</h2>';
		$html .= ' <form action="'. Tools::safeOutput($_SERVER['REQUEST_URI']). '" method="post"><fieldset>';
		// NOTE :
		/* 
		Tools::safeOutput() est une méthode fournie par PrestaShop qui sert à protéger les chaînes de caractères que tu affiches dans le HTML.
		*/
		
		$html .= ' ' . $this->l('Number of Products to Display') . ': '
		  .'<input type="number" name="numProds" value="' . (int)(Configuration::get('DP_Number_of_Products')) . '" />';
		// NOTE : input type="number" :
		/*
		Certains vieux navigateurs (typiquement Internet Explorer 11 ou plus anciens) ne gèrent pas bien <input type="number">.
		Le champ s'affichera quand même, mais :
		Les flèches de sélection (↑ ↓) pourraient ne pas apparaître.
		Le contrôle du type (empêcher de taper autre chose que des chiffres) pourrait ne pas être appliqué.
		*/
		$html .= ' <input type="submit" value="' . $this->l('Save') . '" />';
		$html .= ' </fieldset></form>';
		$html .= '</div>';

		return $html;
	}
}