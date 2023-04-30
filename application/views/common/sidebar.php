<div class="navbar-default sidebar" role="navigation" style="margin-top:60px;" id="sidebar-wrapper"> 
    <div class="sidebar-nav  navbar-collapse" id="side-menu">
        <ul class="nav"> 
            <!-- <li class="sidebar&#45;search"> -->
            <!--     <div class="input&#45;group custom&#45;search&#45;form"> -->
            <!--         <input type="text" class="form&#45;control" placeholder="Search..."> -->
            <!--         <span class="input&#45;group&#45;btn"> -->
            <!--             <button class="btn btn&#45;default" type="button"> -->
            <!--                 <i class="fa fa&#45;search"></i> -->
            <!--             </button> -->
            <!--         </span> -->
            <!--     </div> -->
            <!-- </li>  id="side-menu"   id="sidebar-wrapper" -->

            <?php foreach ($_MENU as $menu) { ?>
            <?php if (in_array($menu['id'], $flags['permission']) || $menu['auth'] == 0) { ?>
            <li class="">
                <?php if (count($menu['sub']) == 0) { ?>
                <?php if (in_array($menu['id'], $flags['permission']) || $menu['auth'] == 0) { ?>
                <?php if($menu['link'] == '#'){ ?>
                <a href="#" onclick="return false">
                    <!-- <?php if($menu['icon']!='') echo "<i class=\"fa {$menu['icon']} fa-fw\"></i>";?> -->
                    <?=" {$menu['name']}";?>
                </a>
                <?php } else { ?>
                <a href="<?=base_url($menu['link']);?>">
                    <!-- <?php if($menu['icon']!='') echo "<i class=\"fa {$menu['icon']} fa-fw\"></i>";?> -->
                    <?=" {$menu['name']}";?>
                </a>
                <?php } ?>
                <?php } ?>
                <?php } else { ?>
                <?php if($menu['link'] == '#'){ ?>
                <a href="#" onclick="return false">
                    <!-- <?php if($menu['icon']!='') echo "<i class=\"fa {$menu['icon']} fa-fw\"></i>";?> -->
                    <?=" {$menu['name']}";?> <span class="fa arrow"></span>
                </a>
                <?php } else { ?>
                <a href="<?=base_url($menu['link']);?>">
                    <!-- <?php if($menu['icon']!='') echo "<i class=\"fa {$menu['icon']} fa-fw\"></i>";?> -->
                    <?=" {$menu['name']}";?> <span class="fa arrow"></span>
                </a>
                <?php } ?>
                <ul class="nav nav-second-level">
                    <?php foreach ($menu['sub'] as $item) { ?>
                    <?php if (in_array($item['id'], $flags['permission']) || $menu['auth'] == 0) { ?>
                    <li>
                        <?php if($item['link'] == '#'){ ?>
                        <a href="#" onclick="return false">
                            &emsp;
                            <!-- <?php if($item['icon']!='') echo "<i class=\"fa {$item['icon']} fa-fw\"></i>";?> -->
                            <?="{$item['name']}";?>
                            <!-- <i class=\"fa fa-angle-double-right\" nav-second-level>  class="nav nav-second-level" </i> -->
                        </a>
                        <?php } else { ?>
                        <?php if($item['name'] == '32A 志工管理系統' || $item['name'] == '32B 志工園地' || $item['name'] == '29J 公務人員學習時數查詢(終身學習入口網)' || $item['name'] == '19A 問卷設定' || $item['name'] == '19B 結果查詢(報表)'){ ?>
                        <a href="<?=base_url($item['link']);?>" target="_blank" title="<?=$item['name'];?>【開啟新視窗】">
                            &emsp;
                            <!-- <?php if($item['icon']!='') echo "<i class=\"fa {$item['icon']} fa-fw\"></i>";?> -->
                            <?="{$item['name']}";?>
                            <!-- <i class=\"fa fa-angle-double-right\" nav-second-level>  class="nav nav-second-level" </i> -->
                        </a>
                        <?php } else { ?>
                        <a href="<?=base_url($item['link']);?>">
                            &emsp;
                            <!-- <?php if($item['icon']!='') echo "<i class=\"fa {$item['icon']} fa-fw\"></i>";?> -->
                            <?="{$item['name']}";?>
                            <!-- <i class=\"fa fa-angle-double-right\" nav-second-level>  class="nav nav-second-level" </i> -->
                        </a>
                        <?php } ?>
                        <?php } ?>
                    </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
            <?php } ?>
            <?php } ?>



            <!-- <li> -->
            <!--    <a href="#"><i class="fa fa&#45;bar&#45;chart&#45;o fa&#45;fw"></i> Charts<span class="fa arrow"></span></a> -->
            <!--    <ul class="nav nav&#45;second&#45;level"> -->
            <!--        <li> -->
            <!--            <a href="flot.html">Flot Charts</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="morris.html">Morris.js Charts</a> -->
            <!--        </li> -->
            <!--    </ul> -->
            <!--    <!&#45;&#45; /.nav&#45;second&#45;level &#45;&#45;> -->
            <!-- </li> -->
            <!-- <li> -->
            <!--    <a href="tables.html"><i class="fa fa&#45;table fa&#45;fw"></i> Tables</a> -->
            <!-- </li> -->
            <!-- <li> -->
            <!--    <a href="forms.html"><i class="fa fa&#45;edit fa&#45;fw"></i> Forms</a> -->
            <!-- </li> -->
            <!-- <li> -->
            <!--    <a href="#"><i class="fa fa&#45;wrench fa&#45;fw"></i> UI Elements<span class="fa arrow"></span></a> -->
            <!--    <ul class="nav nav&#45;second&#45;level"> -->
            <!--        <li> -->
            <!--            <a href="panels&#45;wells.html">Panels and Wells</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="buttons.html">Buttons</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="notifications.html">Notifications</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="typography.html">Typography</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="icons.html"> Icons</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="grid.html">Grid</a> -->
            <!--        </li> -->
            <!--    </ul> -->
            <!--    <!&#45;&#45; /.nav&#45;second&#45;level &#45;&#45;> -->
            <!-- </li> -->
            <!-- <li> -->
            <!--    <a href="#"><i class="fa fa&#45;sitemap fa&#45;fw"></i> Multi&#45;Level Dropdown<span class="fa arrow"></span></a> -->
            <!--    <ul class="nav nav&#45;second&#45;level"> -->
            <!--        <li> -->
            <!--            <a href="#">Second Level Item</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="#">Second Level Item</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="#">Third Level <span class="fa arrow"></span></a> -->
            <!--            <ul class="nav nav&#45;third&#45;level"> -->
            <!--                <li> -->
            <!--                    <a href="#">Third Level Item</a> -->
            <!--                </li> -->
            <!--                <li> -->
            <!--                    <a href="#">Third Level Item</a> -->
            <!--                </li> -->
            <!--                <li> -->
            <!--                    <a href="#">Third Level Item</a> -->
            <!--                </li> -->
            <!--                <li> -->
            <!--                    <a href="#">Third Level Item</a> -->
            <!--                </li> -->
            <!--            </ul> -->
            <!--            <!&#45;&#45; /.nav&#45;third&#45;level &#45;&#45;> -->
            <!--        </li> -->
            <!--    </ul> -->
            <!--    <!&#45;&#45; /.nav&#45;second&#45;level &#45;&#45;> -->
            <!-- </li> -->
            <!-- <li> -->
            <!--    <a href="#"><i class="fa fa&#45;files&#45;o fa&#45;fw"></i> Sample Pages<span class="fa arrow"></span></a> -->
            <!--    <ul class="nav nav&#45;second&#45;level"> -->
            <!--        <li> -->
            <!--            <a href="blank.html">Blank Page</a> -->
            <!--        </li> -->
            <!--        <li> -->
            <!--            <a href="login.html">Login Page</a> -->
            <!--        </li> -->
            <!--    </ul> -->
            <!--    <!&#45;&#45; /.nav&#45;second&#45;level &#45;&#45;> -->
            <!-- </li> -->
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
