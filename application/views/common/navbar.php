<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?=HTTP_ROOT;?>" target="_block">
        <?=$_SETTING['web_title'];?>
    </a>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-top-links navbar-right ">
    <a id="menu-toggle" href="#" class="btn-menu toggle">
                <span style="color:white" ><i class="fa fa-bars"></i></span>
                </a>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <span class="hidden-xs" style="color:#F6F6F6;" >目前登入的使用者：<?=!empty($flags['user']['co_usrnick'])?$flags['user']['co_usrnick']:$flags['user']['name'];?></span>
        </a>
        <ul class="dropdown-menu">
            <li class="user-footer">
                <a class="btn btn-default btn-flat" href="<?=base_url('logout');?>">
                    <i class="fa fa-sign-out"></i>
                    Logout
                </a>
            </li>
            <?php if ($flags['user']['switch'] === TRUE) { ?>
            <li class="user-footer">
                <a class="btn btn-default btn-flat" href="<?=base_url('switch_back');?>">
                    <i class="fa fa-sign-out"></i>
                    切換回原始身分
                </a>
            </li>
            <?php } ?>
        </ul>
    </li>
    <!-- /.dropdown -->

</ul>
<!-- /.navbar-top-links -->


