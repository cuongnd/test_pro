<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/media/system/js/jquery-vertical-mega-menu-1/js/jquery.hoverIntent.minified.js');
$doc->addScript(JUri::root().'/media/system/js/jquery-vertical-mega-menu-1/js/jquery.dcverticalmegamenu.1.1.js');
$doc->addStyleSheet(JUri::root().'/media/system/js/jquery-vertical-mega-menu-1/css/vertical_menu_basic.css');
$doc->addStyleSheet(JUri::root().'/media/system/js/jquery-vertical-mega-menu-1/css/vertical_menu.css');
$scriptId = "script_module_mod_virtuemart_category_" . $module->id;

ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#mega-1').dcVerticalMegaMenu({
            rowItems: '3',
            speed: 'fast',
            effect: 'slide',
            direction: 'right'
        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);



?>
<div class="dcjq-vertical-mega-menu">
    <ul id="mega-1" class="menu">
        <li id="menu-item-0"><a href="#">Home</a></li>
        <li id="menu-item-1"><a href="#">Footwear</a>
            <ul>
                <li id="menu-item-4"><a href="#">Nike</a>
                    <ul>
                        <li id="menu-item-19"><a href="#">Nike Air Foamposite One</a></li>
                        <li id="menu-item-20"><a href="#">The Air Jordan 2011</a></li>
                        <li id="menu-item-21"><a href="#">Nike Hyperfuse Max</a></li>
                    </ul>
                </li>
                <li id="menu-item-6"><a href="#">Adidas</a>
                    <ul>
                        <li id="menu-item-25"><a href="#">Supernova Glide 2 W</a></li>
                        <li id="menu-item-26"><a href="#">adizero adios</a></li>
                        <li id="menu-item-27"><a href="#">adiSTAR Salvation 2 W</a></li>
                    </ul>
                </li>
                <li id="menu-item-7"><a href="#">Gola</a>
                    <ul>
                        <li id="menu-item-28"><a href="#">Gola Harrier</a></li>
                        <li id="menu-item-29"><a href="#">Gola Multi</a></li>
                        <li id="menu-item-30"><a href="#">Gola Chase</a></li>
                    </ul>
                </li>

                <li id="menu-item-8"><a href="#">Nike</a>
                    <ul>
                        <li id="menu-item-31"><a href="#">Lady Air Pegasus+ 27</a></li>
                        <li id="menu-item-32"><a href="#">Nike LunarGlide+ 2 </a></li>
                        <li id="menu-item-33"><a href="#">Nike LunarEclipse+</a></li>
                    </ul>
                </li>
                <li id="menu-item-10"><a href="#">Adidas</a>
                    <ul>
                        <li id="menu-item-37"><a href="#">Adidas Messenger Bag</a></li>
                        <li id="menu-item-38"><a href="#">Kanadia TR 2 Trail</a></li>
                        <li id="menu-item-39"><a href="#">Supernova Sequence 3</a></li>
                    </ul>
                </li>
                <li id="menu-item-11"><a href="#">Gola</a>
                    <ul>
                        <li id="menu-item-40"><a href="#">Gola Buzz</a></li>
                        <li id="menu-item-41"><a href="#">Gola Harrier</a></li>
                        <li id="menu-item-42"><a href="#">Gola Harrier Multi</a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <li id="menu-item-2"><a href="#">Jackets</a>
            <ul>
                <li id="menu-item-12"><a href="#">Blue Adidas Jacket</a></li>
                <li id="menu-item-13"><a href="#">White Training Jacket</a></li>
                <li id="menu-item-14"><a href="#">Red Adidas Jacket</a></li>
            </ul>
        </li>
        <li id="menu-item-3"><a href="#">Sports Bags</a>
            <ul>
                <li id="menu-item-15"><a href="#">Golf Bags</a>
                    <ul>
                        <li id="menu-item-43"><a href="#">IZZO Scout Stand Bag</a></li>
                        <li id="menu-item-44"><a href="#">OGIO DECIBEL Stand</a></li>
                        <li id="menu-item-45"><a href="#">Tribal Cart Bag</a></li>
                    </ul>
                </li>
                <li id="menu-item-17"><a href="#">Sports Bags</a>
                    <ul>
                        <li id="menu-item-47"><a href="#">Adidas Sports Bag</a></li>
                        <li id="menu-item-48"><a href="#">Nike Sports Bag</a></li>
                    </ul>
                </li>
                <li id="menu-item-18"><a href="#">Tennis Bags</a>
                    <ul>
                        <li id="menu-item-49"><a href="#">Wilson Tennis Bag</a></li>
                        <li id="menu-item-50"><a href="#">Adidas Tennis Bag</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li id="menu-item-51"><a href="#">Customer Service</a>
            <ul>
                <li id="menu-item-52"><a href="#">Shipping</a></li>
                <li id="menu-item-53"><a href="#">Refunds/Returns</a></li>
                <li id="menu-item-54"><a href="#">Payment</a></li>
                <li id="menu-item-55"><a href="#">Terms &amp; Conditions</a></li>
            </ul>
        </li>
    </ul>
</div>

