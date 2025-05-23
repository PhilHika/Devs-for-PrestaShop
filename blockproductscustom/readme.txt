=== Custom Mega Menu ===
Contributors: Philippe Lavocat
Tags: Add product block (responsive)
Dev on : PrestaShop 8.2.1 - PHP: 8.2

== Description ==
This plugin allows you to easily add a custom product to your PrestaShop homepage. 
Define number products to display, no filters, no context to display (for v2)

== Installation ==
classique via module manager

== Techno ==
PHP
CSS
smarty (.tpl)
PrestaShop headers and displayHome hook (more to come for select position)

== version 1.0.0 ==
commit 23/05/2025

== files structure == 
blockproductscustom/
├── blockproductscustom.php
├── config.xml
├── index.php
├── views/
│   ├── css/
│   │   └── blockproductscustom.css
│   │   └── index.php
│   └── templates/
│       └── hook/
│           └── blockproductscustom.tpl
│           └── index.tpl

== Coming soon ==
- Define where to display : home/product/everywhere (footer)
- Css options
- Filter (backend criteria, related, specific category...)