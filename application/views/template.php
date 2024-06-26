<!-- https://wrappixel.com/demos/free-admin-templates/matrix-admin/index.html -->
<!DOCTYPE html>
<html lang="en">
   <head>
      <script>
      var HOME_URL = '<?php echo base_url(); ?>';
      </script>

      <title>Controle Finanças</title>
      <meta charset="UTF-8" />
      <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>img/favicon.ico" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-responsive.min.css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/fullcalendar.css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/matrix-style.css" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/matrix-media.css" />
      <link href="<?php echo base_url(); ?>font-awesome/css/font-awesome.css" rel="stylesheet" />
      <link href="<?php echo base_url(); ?>js/Dynatable/jquery.dynatable.css" rel="stylesheet" />
      <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.gritter.css" />
      <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
   </head>
   <body>
      <!--Header-part-->
      <div id="header">
         <h1><a href="<?php echo base_url() . "Start"; ?>">Controle Admin</a></h1>
      </div>
      <!--close-Header-part-->
      <!--top-Header-menu-->
      <div id="user-nav" class="navbar navbar-inverse">
         <ul class="nav">
            <li  class="dropdown" id="profile-messages" >
               <a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Olá <?php echo $username; ?></span><b class="caret"></b></a>
               <ul class="dropdown-menu">
                  <?php
                  /*
                  <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
                  <li class="divider"></li>
                  <li><a href="#"><i class="icon-check"></i> My Tasks</a></li>
                  <li class="divider"></li>
                  */
                  ?>

                  <li><a href="<?php echo base_url() . 'Login/logout' ?>"><i class="icon-key"></i> Sair</a></li>
               </ul>
            </li>

            <?php
            /*
            <li class="dropdown" id="menu-messages">
               <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
               <ul class="dropdown-menu">
                  <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
                  <li class="divider"></li>
                  <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
                  <li class="divider"></li>
                  <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
                  <li class="divider"></li>
                  <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
               </ul>
            </li>
            */
            ?>

            <?php
            /*
            <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>
            */
            ?>

            <li class=""><a title="" href="<?php echo base_url() . 'Login/logout' ?>"><i class="icon icon-share-alt"></i> <span class="text">Sair</span></a></li>
         </ul>
      </div>
      <!--close-top-Header-menu-->

      <?php
      /*
      <!--start-top-serch-->
      <div id="search">
         <input type="text" placeholder="Search here..."/>
         <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
      </div>
      <!--close-top-serch-->
      */
      ?>

      <!--sidebar-menu-->
      <div id="sidebar">
         <a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>

         <?php
         // SO DOIS LEVELS PROGRAMADOS
         $vArrMenu = (isset($arrMenu)) ? $arrMenu: array();
         $baseUrl  = base_url();

         echo "<ul>";

         foreach($vArrMenu as $menuLvl1){
           $ml1Descricao  = $menuLvl1["descricao"];
           $ml1Controller = $menuLvl1["controller"];
           $ml1Action     = $menuLvl1["action"];
           $ml1Icon       = $menuLvl1["icon"];
           $ml1ArrChild   = $menuLvl1["child"];
           $isActive      = ($ml1Controller == $controller && $ml1Action == $action) ? " active ": "";
           $linkUrl       = $baseUrl . $ml1Controller . "/" . $ml1Action;

           if(count($ml1ArrChild) > 0){
             $isActive = "submenu";
             $linkUrl  = "javascript:;";
           }

           echo "<li class='$isActive'>";
           echo "  <a href='$linkUrl'>";
           echo "    $ml1Icon";
           echo "    <span>$ml1Descricao</span>";
           echo "  </a>";

           if(count($ml1ArrChild) > 0){
             echo "<ul>";

             foreach($ml1ArrChild as $menuLvl2){
               $ml2Descricao  = $menuLvl2["descricao"];
               $ml2Controller = $menuLvl2["controller"];
               $ml2Action     = $menuLvl2["action"];
               $ml2Icon       = $menuLvl2["icon"];
               $ml2ArrChild   = $menuLvl2["child"];
               $linkUrl       = $baseUrl . $ml2Controller . "/" . $ml2Action;

               echo "<li>";
               echo "  <a href='$linkUrl'>$ml2Descricao</a>";
               echo "</li>";
             }

             echo "</ul>";
           }
           echo "</li>";
         }

         echo "</ul>";
         ?>

         <?php
         /*
         <ul>
           <li class="active">
             <a href="<?php echo base_url() . "Start"; ?>">
               <i class="icon icon-home"></i>
               <span>Início</span>
             </a>
           </li>
           <li>
             <a href="<?php echo base_url() . "Produto"; ?>">
               <i class="icon icon-tasks"></i>
               <span>Produtos</span>
             </a>
           </li>
         </ul>
         */
         ?>

         <?php
         /*
         <ul>
            <li class="active"><a href="index.html"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
            <li> <a href="charts.html"><i class="icon icon-signal"></i> <span>Charts &amp; graphs</span></a> </li>
            <li> <a href="widgets.html"><i class="icon icon-inbox"></i> <span>Widgets</span></a> </li>
            <li><a href="tables.html"><i class="icon icon-th"></i> <span>Tables</span></a></li>
            <li><a href="grid.html"><i class="icon icon-fullscreen"></i> <span>Full width</span></a></li>
            <li class="submenu">
               <a href="#"><i class="icon icon-th-list"></i> <span>Forms</span> <span class="label label-important">3</span></a>
               <ul>
                  <li><a href="form-common.html">Basic Form</a></li>
                  <li><a href="form-validation.html">Form with Validation</a></li>
                  <li><a href="form-wizard.html">Form with Wizard</a></li>
               </ul>
            </li>
            <li><a href="buttons.html"><i class="icon icon-tint"></i> <span>Buttons &amp; icons</span></a></li>
            <li><a href="interface.html"><i class="icon icon-pencil"></i> <span>Eelements</span></a></li>
            <li class="submenu">
               <a href="#"><i class="icon icon-file"></i> <span>Addons</span> <span class="label label-important">5</span></a>
               <ul>
                  <li><a href="index2.html">Dashboard2</a></li>
                  <li><a href="gallery.html">Gallery</a></li>
                  <li><a href="calendar.html">Calendar</a></li>
                  <li><a href="invoice.html">Invoice</a></li>
                  <li><a href="chat.html">Chat option</a></li>
               </ul>
            </li>
            <li class="submenu">
               <a href="#"><i class="icon icon-info-sign"></i> <span>Error</span> <span class="label label-important">4</span></a>
               <ul>
                  <li><a href="error403.html">Error 403</a></li>
                  <li><a href="error404.html">Error 404</a></li>
                  <li><a href="error405.html">Error 405</a></li>
                  <li><a href="error500.html">Error 500</a></li>
               </ul>
            </li>
            <li class="content">
               <span>Monthly Bandwidth Transfer</span>
               <div class="progress progress-mini progress-danger active progress-striped">
                  <div style="width: 77%;" class="bar"></div>
               </div>
               <span class="percent">77%</span>
               <div class="stat">21419.94 / 14000 MB</div>
            </li>
            <li class="content">
               <span>Disk Space Usage</span>
               <div class="progress progress-mini active progress-striped">
                  <div style="width: 87%;" class="bar"></div>
               </div>
               <span class="percent">87%</span>
               <div class="stat">604.44 / 4000 MB</div>
            </li>
         </ul>
         */
         ?>
      </div>
      <!--sidebar-menu-->
      <!--main-container-part-->
      <div id="content">
         <!--breadcrumbs-->
         <div id="content-header">
            <div id="breadcrumb"><a href="<?php echo base_url() . "Start"; ?>"><i class="icon-home"></i> Home</a></div>
         </div>
         <!--End-breadcrumbs-->
         <!--Action boxes-->
         <div class="container-fluid">
           <?= $contents ?>

            <?php
            /*
            <div class="quick-actions_homepage">
               <ul class="quick-actions">
                  <li class="bg_lb"> <a href="index.html"> <i class="icon-dashboard"></i> <span class="label label-important">20</span> My Dashboard </a> </li>
                  <li class="bg_lg span3"> <a href="charts.html"> <i class="icon-signal"></i> Charts</a> </li>
                  <li class="bg_ly"> <a href="widgets.html"> <i class="icon-inbox"></i><span class="label label-success">101</span> Widgets </a> </li>
                  <li class="bg_lo"> <a href="tables.html"> <i class="icon-th"></i> Tables</a> </li>
                  <li class="bg_ls"> <a href="grid.html"> <i class="icon-fullscreen"></i> Full width</a> </li>
                  <li class="bg_lo span3"> <a href="form-common.html"> <i class="icon-th-list"></i> Forms</a> </li>
                  <li class="bg_ls"> <a href="buttons.html"> <i class="icon-tint"></i> Buttons</a> </li>
                  <li class="bg_lb"> <a href="interface.html"> <i class="icon-pencil"></i>Elements</a> </li>
                  <li class="bg_lg"> <a href="calendar.html"> <i class="icon-calendar"></i> Calendar</a> </li>
                  <li class="bg_lr"> <a href="error404.html"> <i class="icon-info-sign"></i> Error</a> </li>
               </ul>
            </div>
            <!--End-Action boxes-->
            <!--Chart-box-->
            <div class="row-fluid">
               <div class="widget-box">
                  <div class="widget-title bg_lg">
                     <span class="icon"><i class="icon-signal"></i></span>
                     <h5>Site Analytics</h5>
                  </div>
                  <div class="widget-content" >
                     <div class="row-fluid">
                        <div class="span9">
                           <div class="chart"></div>
                        </div>
                        <div class="span3">
                           <ul class="site-stats">
                              <li class="bg_lh"><i class="icon-user"></i> <strong>2540</strong> <small>Total Users</small></li>
                              <li class="bg_lh"><i class="icon-plus"></i> <strong>120</strong> <small>New Users </small></li>
                              <li class="bg_lh"><i class="icon-shopping-cart"></i> <strong>656</strong> <small>Total Shop</small></li>
                              <li class="bg_lh"><i class="icon-tag"></i> <strong>9540</strong> <small>Total Orders</small></li>
                              <li class="bg_lh"><i class="icon-repeat"></i> <strong>10</strong> <small>Pending Orders</small></li>
                              <li class="bg_lh"><i class="icon-globe"></i> <strong>8540</strong> <small>Online Orders</small></li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!--End-Chart-box-->
            <hr/>
            <div class="row-fluid">
               <div class="span6">
                  <div class="widget-box">
                     <div class="widget-title bg_ly" data-toggle="collapse" href="#collapseG2">
                        <span class="icon"><i class="icon-chevron-down"></i></span>
                        <h5>Latest Posts</h5>
                     </div>
                     <div class="widget-content nopadding collapse in" id="collapseG2">
                        <ul class="recent-posts">
                           <li>
                              <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av1.jpg"> </div>
                              <div class="article-post">
                                 <span class="user-info"> By: john Deo / Date: 2 Aug 2012 / Time:09:27 AM </span>
                                 <p><a href="#">This is a much longer one that will go on for a few lines.It has multiple paragraphs and is full of waffle to pad out the comment.</a> </p>
                              </div>
                           </li>
                           <li>
                              <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av2.jpg"> </div>
                              <div class="article-post">
                                 <span class="user-info"> By: john Deo / Date: 2 Aug 2012 / Time:09:27 AM </span>
                                 <p><a href="#">This is a much longer one that will go on for a few lines.It has multiple paragraphs and is full of waffle to pad out the comment.</a> </p>
                              </div>
                           </li>
                           <li>
                              <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av4.jpg"> </div>
                              <div class="article-post">
                                 <span class="user-info"> By: john Deo / Date: 2 Aug 2012 / Time:09:27 AM </span>
                                 <p><a href="#">This is a much longer one that will go on for a few lines.Itaffle to pad out the comment.</a> </p>
                              </div>
                           <li>
                              <button class="btn btn-warning btn-mini">View All</button>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="widget-box">
                     <div class="widget-title">
                        <span class="icon"><i class="icon-ok"></i></span>
                        <h5>To Do list</h5>
                     </div>
                     <div class="widget-content">
                        <div class="todo">
                           <ul>
                              <li class="clearfix">
                                 <div class="txt"> Luanch This theme on Themeforest <span class="by label">Alex</span></div>
                                 <div class="pull-right"> <a class="tip" href="#" title="Edit Task"><i class="icon-pencil"></i></a> <a class="tip" href="#" title="Delete"><i class="icon-remove"></i></a> </div>
                              </li>
                              <li class="clearfix">
                                 <div class="txt"> Manage Pending Orders <span class="date badge badge-warning">Today</span> </div>
                                 <div class="pull-right"> <a class="tip" href="#" title="Edit Task"><i class="icon-pencil"></i></a> <a class="tip" href="#" title="Delete"><i class="icon-remove"></i></a> </div>
                              </li>
                              <li class="clearfix">
                                 <div class="txt"> MAke your desk clean <span class="by label">Admin</span></div>
                                 <div class="pull-right"> <a class="tip" href="#" title="Edit Task"><i class="icon-pencil"></i></a> <a class="tip" href="#" title="Delete"><i class="icon-remove"></i></a> </div>
                              </li>
                              <li class="clearfix">
                                 <div class="txt"> Today we celebrate the theme <span class="date badge badge-info">08.03.2013</span> </div>
                                 <div class="pull-right"> <a class="tip" href="#" title="Edit Task"><i class="icon-pencil"></i></a> <a class="tip" href="#" title="Delete"><i class="icon-remove"></i></a> </div>
                              </li>
                              <li class="clearfix">
                                 <div class="txt"> Manage all the orders <span class="date badge badge-important">12.03.2013</span> </div>
                                 <div class="pull-right"> <a class="tip" href="#" title="Edit Task"><i class="icon-pencil"></i></a> <a class="tip" href="#" title="Delete"><i class="icon-remove"></i></a> </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="widget-box">
                     <div class="widget-title">
                        <span class="icon"><i class="icon-ok"></i></span>
                        <h5>Progress Box</h5>
                     </div>
                     <div class="widget-content">
                        <ul class="unstyled">
                           <li>
                              <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 81% Clicks <span class="pull-right strong">567</span>
                              <div class="progress progress-striped ">
                                 <div style="width: 81%;" class="bar"></div>
                              </div>
                           </li>
                           <li>
                              <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 72% Uniquie Clicks <span class="pull-right strong">507</span>
                              <div class="progress progress-success progress-striped ">
                                 <div style="width: 72%;" class="bar"></div>
                              </div>
                           </li>
                           <li>
                              <span class="icon24 icomoon-icon-arrow-down-2 red"></span> 53% Impressions <span class="pull-right strong">457</span>
                              <div class="progress progress-warning progress-striped ">
                                 <div style="width: 53%;" class="bar"></div>
                              </div>
                           </li>
                           <li>
                              <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 3% Online Users <span class="pull-right strong">8</span>
                              <div class="progress progress-danger progress-striped ">
                                 <div style="width: 3%;" class="bar"></div>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="widget-box">
                     <div class="widget-title bg_lo"  data-toggle="collapse" href="#collapseG3" >
                        <span class="icon"> <i class="icon-chevron-down"></i> </span>
                        <h5>News updates</h5>
                     </div>
                     <div class="widget-content nopadding updates collapse in" id="collapseG3">
                        <div class="new-update clearfix">
                           <i class="icon-ok-sign"></i>
                           <div class="update-done"><a title="" href="#"><strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</strong></a> <span>dolor sit amet, consectetur adipiscing eli</span> </div>
                           <div class="update-date"><span class="update-day">20</span>jan</div>
                        </div>
                        <div class="new-update clearfix"> <i class="icon-gift"></i> <span class="update-notice"> <a title="" href="#"><strong>Congratulation Maruti, Happy Birthday </strong></a> <span>many many happy returns of the day</span> </span> <span class="update-date"><span class="update-day">11</span>jan</span> </div>
                        <div class="new-update clearfix"> <i class="icon-move"></i> <span class="update-alert"> <a title="" href="#"><strong>Maruti is a Responsive Admin theme</strong></a> <span>But already everything was solved. It will ...</span> </span> <span class="update-date"><span class="update-day">07</span>Jan</span> </div>
                        <div class="new-update clearfix"> <i class="icon-leaf"></i> <span class="update-done"> <a title="" href="#"><strong>Envato approved Maruti Admin template</strong></a> <span>i am very happy to approved by TF</span> </span> <span class="update-date"><span class="update-day">05</span>jan</span> </div>
                        <div class="new-update clearfix"> <i class="icon-question-sign"></i> <span class="update-notice"> <a title="" href="#"><strong>I am alwayse here if you have any question</strong></a> <span>we glad that you choose our template</span> </span> <span class="update-date"><span class="update-day">01</span>jan</span> </div>
                     </div>
                  </div>
               </div>
               <div class="span6">
                  <div class="widget-box widget-chat">
                     <div class="widget-title bg_lb">
                        <span class="icon"> <i class="icon-comment"></i> </span>
                        <h5>Chat Option</h5>
                     </div>
                     <div class="widget-content nopadding collapse in" id="collapseG4">
                        <div class="chat-users panel-right2">
                           <div class="panel-title">
                              <h5>Online Users</h5>
                           </div>
                           <div class="panel-content nopadding">
                              <ul class="contact-list">
                                 <li id="user-Alex" class="online"><a href=""><img alt="" src="img/demo/av1.jpg" /> <span>Alex</span></a></li>
                                 <li id="user-Linda"><a href=""><img alt="" src="img/demo/av2.jpg" /> <span>Linda</span></a></li>
                                 <li id="user-John" class="online new"><a href=""><img alt="" src="img/demo/av3.jpg" /> <span>John</span></a><span class="msg-count badge badge-info">3</span></li>
                                 <li id="user-Mark" class="online"><a href=""><img alt="" src="img/demo/av4.jpg" /> <span>Mark</span></a></li>
                                 <li id="user-Maxi" class="online"><a href=""><img alt="" src="img/demo/av5.jpg" /> <span>Maxi</span></a></li>
                              </ul>
                           </div>
                        </div>
                        <div class="chat-content panel-left2">
                           <div class="chat-messages" id="chat-messages">
                              <div id="chat-messages-inner"></div>
                           </div>
                           <div class="chat-message well">
                              <button class="btn btn-success">Send</button>
                              <span class="input-box">
                              <input type="text" name="msg-box" id="msg-box" />
                              </span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="widget-box">
                     <div class="widget-title">
                        <span class="icon"><i class="icon-user"></i></span>
                        <h5>Our Partner (Box with Fix height)</h5>
                     </div>
                     <div class="widget-content nopadding fix_hgt">
                        <ul class="recent-posts">
                           <li>
                              <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av1.jpg"> </div>
                              <div class="article-post">
                                 <span class="user-info">John Deo</span>
                                 <p>Web Desginer &amp; creative Front end developer</p>
                              </div>
                           </li>
                           <li>
                              <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av2.jpg"> </div>
                              <div class="article-post">
                                 <span class="user-info">John Deo</span>
                                 <p>Web Desginer &amp; creative Front end developer</p>
                              </div>
                           </li>
                           <li>
                              <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av4.jpg"> </div>
                              <div class="article-post">
                                 <span class="user-info">John Deo</span>
                                 <p>Web Desginer &amp; creative Front end developer</p>
                              </div>
                        </ul>
                     </div>
                  </div>
                  <div class="accordion" id="collapse-group">
                     <div class="accordion-group widget-box">
                        <div class="accordion-heading">
                           <div class="widget-title">
                              <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                                 <span class="icon"><i class="icon-magnet"></i></span>
                                 <h5>Accordion Example 1</h5>
                              </a>
                           </div>
                        </div>
                        <div class="collapse in accordion-body" id="collapseGOne">
                           <div class="widget-content"> It has multiple paragraphs and is full of waffle to pad out the comment. Usually, you just wish these sorts of comments would come to an end. </div>
                        </div>
                     </div>
                     <div class="accordion-group widget-box">
                        <div class="accordion-heading">
                           <div class="widget-title">
                              <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse">
                                 <span class="icon"><i class="icon-magnet"></i></span>
                                 <h5>Accordion Example 2</h5>
                              </a>
                           </div>
                        </div>
                        <div class="collapse accordion-body" id="collapseGTwo">
                           <div class="widget-content">And is full of waffle to It has multiple paragraphs and is full of waffle to pad out the comment. Usually, you just wish these sorts of comments would come to an end.</div>
                        </div>
                     </div>
                     <div class="accordion-group widget-box">
                        <div class="accordion-heading">
                           <div class="widget-title">
                              <a data-parent="#collapse-group" href="#collapseGThree" data-toggle="collapse">
                                 <span class="icon"><i class="icon-magnet"></i></span>
                                 <h5>Accordion Example 3</h5>
                              </a>
                           </div>
                        </div>
                        <div class="collapse accordion-body" id="collapseGThree">
                           <div class="widget-content"> Waffle to It has multiple paragraphs and is full of waffle to pad out the comment. Usually, you just </div>
                        </div>
                     </div>
                  </div>
                  <div class="widget-box collapsible">
                     <div class="widget-title">
                        <a data-toggle="collapse" href="#collapseOne">
                           <span class="icon"><i class="icon-arrow-right"></i></span>
                           <h5>Toggle, Open by default, </h5>
                        </a>
                     </div>
                     <div id="collapseOne" class="collapse in">
                        <div class="widget-content"> This box is opened by default, paragraphs and is full of waffle to pad out the comment. Usually, you just wish these sorts of comments would come to an end. </div>
                     </div>
                     <div class="widget-title">
                        <a data-toggle="collapse" href="#collapseTwo">
                           <span class="icon"><i class="icon-remove"></i></span>
                           <h5>Toggle, closed by default</h5>
                        </a>
                     </div>
                     <div id="collapseTwo" class="collapse">
                        <div class="widget-content"> This box is now open </div>
                     </div>
                     <div class="widget-title">
                        <a data-toggle="collapse" href="#collapseThree">
                           <span class="icon"><i class="icon-remove"></i></span>
                           <h5>Toggle, closed by default</h5>
                        </a>
                     </div>
                     <div id="collapseThree" class="collapse">
                        <div class="widget-content"> This box is now open </div>
                     </div>
                  </div>
                  <div class="widget-box">
                     <div class="widget-title">
                        <ul class="nav nav-tabs">
                           <li class="active"><a data-toggle="tab" href="#tab1">Tab1</a></li>
                           <li><a data-toggle="tab" href="#tab2">Tab2</a></li>
                           <li><a data-toggle="tab" href="#tab3">Tab3</a></li>
                        </ul>
                     </div>
                     <div class="widget-content tab-content">
                        <div id="tab1" class="tab-pane active">
                           <p>And is full of waffle to It has multiple paragraphs and is full of waffle to pad out the comment. Usually, you just wish these sorts of comments would come to an end.multiple paragraphs and is full of waffle to pad out the comment.</p>
                           <img src="img/demo/demo-image1.jpg" alt="demo-image"/>
                        </div>
                        <div id="tab2" class="tab-pane">
                           <img src="img/demo/demo-image2.jpg" alt="demo-image"/>
                           <p>And is full of waffle to It has multiple paragraphs and is full of waffle to pad out the comment. Usually, you just wish these sorts of comments would come to an end.multiple paragraphs and is full of waffle to pad out the comment.</p>
                        </div>
                        <div id="tab3" class="tab-pane">
                           <p>And is full of waffle to It has multiple paragraphs and is full of waffle to pad out the comment. Usually, you just wish these sorts of comments would come to an end.multiple paragraphs and is full of waffle to pad out the comment. </p>
                           <img src="img/demo/demo-image3.jpg" alt="demo-image"/>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            */
            ?>
         </div>
      </div>
      <!--end-main-container-part-->
      <!--Footer-part-->
      <div class="row-fluid">
         <div id="footer" class="span12"> 2018 &copy; Controle Admin - Versão 0.0.1</div>
      </div>
      <!--end-Footer-part-->
      <script src="<?php echo base_url(); ?>js/excanvas.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.ui.custom.js"></script>
      <script src="<?php echo base_url(); ?>js/moment.min.js"></script>
      <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
      <script src="<?php echo base_url(); ?>js/Dynatable/jquery.dynatable.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery-maskmoney.v3.1.1.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.flot.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.flot.resize.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.peity.min.js"></script>
      <script src="<?php echo base_url(); ?>js/fullcalendar.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.gritter.min.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.wizard.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.uniform.js"></script>
      <script src="<?php echo base_url(); ?>js/select2.min.js"></script>
      <script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>
      <script src="<?php echo base_url(); ?>js/jquery.dataTables.min.js"></script>
      <script src="<?php echo base_url(); ?>js/bootbox.min.js"></script>
      <script src="<?php echo base_url(); ?>js/masked.js"></script>
      <script src="<?php echo base_url(); ?>js/numeric.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.dashboard.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.interface.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.chat.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.form_validation.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.form_common.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.popover.js"></script>
      <script src="<?php echo base_url(); ?>js/matrix.tables.js"></script>
      <script src="<?php echo base_url(); ?>js/custom.js?r=<?=date('YmdHis')?>"></script>
      <script type="text/javascript">
         // This function is called from the pop-up menus to transfer to
         // a different page. Ignore if the value returned is a null string:
         function goPage (newURL) {

             // if url is empty, skip the menu dividers and reset the menu selection to default
             if (newURL != "") {

                 // if url is "-", it is this page -- reset the menu:
                 if (newURL == "-" ) {
                     resetMenu();
                 }
                 // else, send page to designated URL
                 else {
                   document.location.href = newURL;
                 }
             }
         }

         // resets the menu selection upon entry to this page:
         function resetMenu() {
          document.gomenu.selector.selectedIndex = 2;
         }
      </script>

      <?php
      // essa variavel vem do Start/index
      $scriptPath = (isset($scriptPath)) ? $scriptPath: "";
      if( file_exists($scriptPath) ){
        ?>
        <script>
        fncTelaUpdateBd('<?php echo $scriptPath; ?>');
        </script>
        <?php
      }
      ?>
   </body>
</html>
