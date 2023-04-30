<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel&#45;heading">
                <i class="fa fa-list fa-lg"></i> <?=$_LOCATION['name'];?><?php echo '<span style="color:#f5f5f5">'. $_SERVER['SERVER_ADDR'].'</span>'; ?>
            </div>
            <!-- /.panel-heading -->

            <div class="panel-body">
                <iframe src="https://elearning.taipei/mpage/home/view_dcsdcourse_news_more" width="100%" height="2000px" frameborder="0" scrolling="no">
                </iframe>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<script src="<?=HTTP_PLUGIN;?>jquery.highlight-3.js"></script>
<script>
$(document).ready(function() {
    $('#filter-form select').change(function(){
        $('#filter-form').submit();
    });

    <?php if (isset($filter['q']) && $filter['q'] != ''){ ?>
    $('#list-form').highlight('<?=$filter['q'];?>');
    <?php } ?>
});

</script>
