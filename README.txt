=== Woo material control for products ===
Contributors: atlantdak (Kishkin Dmitriy)
Donate link: #
Tags: woocommerce
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Плагин меняет логику учёта товров Woocommerce.
Если у товара указать его состав, тогда количество товаров будет автоматически просчитываться исходя из количества составляющих в наличии.
Есть отчёт запасов составляющих компонентов для продуктов.


== Description ==
Плагин добавляет возможности: 
- указывать состав продукта.
- расчитавает количество товаров на складе исходя из количества его составляющих в наличии
- если в наличии нет хотя-бы одного продукта который входит в состав товара, тогда товара нет в наличии
- при покупке составленного товара, количество составляющих продуктов на складе уменьшается
- при добавлении товара в корзину, составляющие продукта резервируются. (Для того что-бы продукты с одинаковыми составляющими имели актуальное количество запасов)
- есть отчёт запасов составляющих компонентов для продуктов.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `woo-material-control-for-products.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates


== Screenshots ==


== Changelog ==

= 1.0 =


== Arbitrary section ==



== A brief Markdown Example ==

