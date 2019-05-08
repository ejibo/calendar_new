<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:79:"/home/wwwroot/ss_calendar_new/public/../application/index/view/index/index.html";i:1555342625;s:70:"/home/wwwroot/ss_calendar_new/application/common/view/public/base.html";i:1555342625;s:72:"/home/wwwroot/ss_calendar_new/application/common/view/public/header.html";i:1555342625;s:74:"/home/wwwroot/ss_calendar_new/application/common/view/public/left_nav.html";i:1555342625;s:74:"/home/wwwroot/ss_calendar_new/application/common/view/public/head_nav.html";i:1555342625;s:72:"/home/wwwroot/ss_calendar_new/application/common/view/public/footer.html";i:1555342625;}*/ ?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>北大软微日程管理系统</title>
    <meta name="description" content="北大软微日程管理系统">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/static/assets/css/normalize.css">
    <link rel="stylesheet" href="/static/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/static/assets/css/themify-icons.css">
    <link rel="stylesheet" href="/static/assets/css/pe-icon-7-filled.css">
    <link rel="stylesheet" href="/static/assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="/static/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="/static/assets/css/lib/datatable/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="/static/assets/css/style.css">
</head>
<!--其他样式-->

<!--其他样式-->
<body>
<!-- 左边栏开始 -->


<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if(is_array($menu_info) || $menu_info instanceof \think\Collection || $menu_info instanceof \think\Paginator): $i = 0; $__LIST__ = $menu_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;if($list['is_one'] == 1): ?>
                <li class="menu-title"><?php echo $list['menu_description']; ?></li>
                <?php else: if($list['menu_id'] == $menu_id_now): ?>
                <li class="active menu-item-has-children">
                    <a href=<?php echo url($list['module']."/".$list['controller']."/".$list['action']); ?>> <i class="menu-icon fa <?php echo $list['icon']; ?>"></i><?php echo $list['menu_description']; ?> </a>

                </li>
                <?php else: ?>
                <li class="menu-item-has-children">
                    <a href=<?php echo url($list['module']."/".$list['controller']."/".$list['action']); ?>> <i class="menu-icon fa <?php echo $list['icon']; ?>"></i><?php echo $list['menu_description']; ?> </a>
                </li>
                <?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>
<!-- 左边栏结束 -->

<!-- Right Panel -->
<div id="right-panel" class="right-panel">
    <!-- 标题栏开始 -->
    

<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><img src="/static/images/logo.png" alt="Logo"></a>
            <a class="navbar-brand hidden" href="#"><img src="/static/images/logo2.png" alt="Logo"></a>
            <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
        </div>
    </div>
    <div class="top-right">
        <div class="header-menu">
            <div class="header-left">
                <a href="<?php echo url('login/login/loginout'); ?>">
                    张三[退出]
                </a>

                <div class="dropdown for-notification">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="count bg-danger">2</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="message">
                        <p class="red">待办事项</p>
                    </div>
                </div>
            </div>

            <div class="user-area dropdown float-right">
                <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="user-avatar rounded-circle" src="/static/images/admin.jpg" alt="User Avatar">
                </a>

                <div class="user-menu dropdown-menu">
                    <a class="nav-link" href="#"><i class="fa fa-user"></i>我的资料</a>
                    <a class="nav-link" href="<?php echo url('login/login/loginout'); ?>"><i class="fa fa-power-off"></i>退出</a>
                </div>
            </div>

        </div>
    </div>
</header>
    <!-- 标题栏结束 -->

    <!-- Content -->
    
<!-- Content -->
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">Test&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

    <!-- /.content -->
    <div class="clearfix"></div>
    <!-- 页脚开始 -->
    <!-- Footer -->
<footer class="site-footer">
    <div class="footer-inner bg-white">
        <div class="row">
            <div class="col-sm-6">
                Copyright &copy; 2019 SSPKU Admin. 友情链接 <a href="http://www.ss.pku.edu.cn/" target="_blank" title="北京大学软件与微电子学院">北京大学软件与微电子学院</a>
            </div>
            <div class="col-sm-6 text-right"> Designed by SunJiajing
            </div>
        </div>
    </div>
</footer>
<!-- /.site-footer -->
    <!-- 页脚结束 -->
</div>
<!-- /#right-panel -->

<!-- 公用js -->
<script src="/static/assets/js/vendor/jquery-2.1.4.min.js"></script>
<script src="/static/assets/js/popper.min.js"></script>
<script src="/static/assets/js/bootstrap.min.js"></script>
<script src="/static/assets/js/jquery.matchHeight.min.js"></script>
<script src="/static/assets/js/main.js"></script>
<script src="/static/assets/js/lib/data-table/datatables.min.js"></script>
<script src="/static/assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
<script src="/static/assets/js/lib/data-table/dataTables.buttons.min.js"></script>
<script src="/static/assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
<script src="/static/assets/js/lib/data-table/jszip.min.js"></script>
<script src="/static/assets/js/lib/data-table/vfs_fonts.js"></script>
<script src="/static/assets/js/lib/data-table/buttons.html5.min.js"></script>
<script src="/static/assets/js/lib/data-table/buttons.print.min.js"></script>
<script src="/static/assets/js/lib/data-table/buttons.colVis.min.js"></script>
<script src="/static/assets/js/init/datatables-init.js"></script>
<!-- 公用js -->
<script type="text/javascript">
    /*    if(localStorage.getItem("menu")===null){
            localStorage.setItem("menu",0);
        }
        $('.menu-item-has-children').eq(parseInt(localStorage.getItem("menu"))).addClass("active").siblings().removeClass("active");
        $('.menu-item-has-children').each(function (index,val) {
            var item = $(this);
            item.click(function () {
                localStorage.setItem("menu",index);
                item.addClass("active").siblings().removeClass("active");
            });
        });*/
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#bootstrap-data-table-export').DataTable();
    } );
</script>
<!-- 此页相关的js -->

<!-- 此页相关的js -->
</body>
</html>