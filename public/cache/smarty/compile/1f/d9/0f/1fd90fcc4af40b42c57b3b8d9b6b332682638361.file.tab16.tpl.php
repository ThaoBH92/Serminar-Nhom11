<?php /* Smarty version Smarty-3.1.19, created on 2014-11-10 00:15:13
         compiled from "/home/u366837675/public_html/modules/facebookcomments/tab16.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1873422157545fa12151ccb3-04527768%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1fd90fcc4af40b42c57b3b8d9b6b332682638361' => 
    array (
      0 => '/home/u366837675/public_html/modules/facebookcomments/tab16.tpl',
      1 => 1415510787,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1873422157545fa12151ccb3-04527768',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'var' => 0,
    'fcbc_scheme' => 0,
    'fcbc_width' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_545fa1215bd758_02157452',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_545fa1215bd758_02157452')) {function content_545fa1215bd758_02157452($_smarty_tpl) {?><section class="page-product-box">
    <h3 class="page-product-heading"><?php echo smartyTranslate(array('s'=>'Comments','mod'=>'facebookcomments'),$_smarty_tpl);?>
 (<fb:comments-count href="http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php echo $_SERVER['REQUEST_URI'];?>
"></fb:comments-count>)</h3>
    <?php $_smarty_tpl->tpl_vars['fcbc_width'] = new Smarty_variable($_smarty_tpl->tpl_vars['var']->value['fcbc_width'], null, 0);?>
    <?php $_smarty_tpl->tpl_vars['fcbc_nbp'] = new Smarty_variable($_smarty_tpl->tpl_vars['var']->value['fcbc_nbp'], null, 0);?>
    <?php $_smarty_tpl->tpl_vars['fcbc_scheme'] = new Smarty_variable($_smarty_tpl->tpl_vars['var']->value['fcbc_scheme'], null, 0);?>
    
    <style>
    .fb_ltr, .fb_iframe_widget, .fb_iframe_widget span {width: 100%!important}
    </style>
    
    <div id="fcbc" class="">
    <fb:comments href="http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php echo $_SERVER['REQUEST_URI'];?>
" colorscheme="<?php echo $_smarty_tpl->tpl_vars['fcbc_scheme']->value;?>
"  width="<?php echo $_smarty_tpl->tpl_vars['fcbc_width']->value;?>
"></fb:comments>
    </div>
</section><?php }} ?>
