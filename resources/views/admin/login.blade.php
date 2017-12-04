<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      &#x9;
      <title>泰和殿後台管理</title>
      <!-- Mobile device set-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Fav and touch icons--><!-- STYLES-->
      <link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
      <!-- STYLES END-->
      <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
      <link href="http://fonts.googleapis.com/css?family=PT+Serif:400,700" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" type="text/css">
      <!--if lt IE 9script(src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')
         | &#x9;
         script(src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js')-->&#x9;
   </head>
   <body class="control-panel-page">
      <!-- Navbar ==================================================-->
      <nav id="header-control-panel" class="navbar navbar-default navbar-fixed-top">
         <div class="container">
            <!-- Brand and toggle get grouped for better mobile display-->
            <div class="navbar-header">
               <h4 class="navbar-text pull-left">泰和殿後台管理</h4>
            </div>
            <!-- /navbar-header--><!-- Collect the nav links, forms, and other content for toggling--><!-- /navbar-collapse-->
         </div>
         <!-- /container-->
      </nav>
      <!-- /navbar-fixed-top-->
      <div class="container font-size-slarge">
         <div class="row m-top-10 m-bottom-10">
            <div class="col-sm-6 col-sm-offset-3">
               <div class="border-block-3 p-40">
                  <h2 class="text-center m-bottom-4">&#x7BA1;&#x7406;&#x54E1;&#x767B;&#x5165;</h2>
                  &#x9;&#x9;&#x9;&#x9;
                  <hr>
                  &#x9;&#x9;&#x9;&#x9;
                  <form action="/auth/local" method="POST" class="form-horizontal">
                     <div class="form-group">
                        <label class="col-md-2 control-label">&#x5E33;&#x865F;</label>&#x9;&#x9;&#x9;&#x9;&#x9;&#x9;
                        <div class="col-md-10"><input type="text" placeholder="User name" name="identifier" class="form-control"></div>
                     </div>
                     <!-- /form-group-->
                     <div class="form-group">
                        <label class="col-md-2 control-label">&#x5BC6;&#x78BC;</label>&#x9;&#x9;&#x9;&#x9;&#x9;&#x9;
                        <div class="col-md-10"><input type="password" placeholder="Password" name="password" class="form-control"></div>
                     </div>
                     <!-- /form-group-->
                     <div class="row m-top-6">
                        <div class="col-sm-6 col-sm-offset-3"><input type="submit" value="登入" class="btn btn-black btn-block border-radius-circle"></div>
                        <!-- /col-sm-6-->
                     </div>
                     <!-- /row-->
                  </form>
               </div>
               <!-- /border-block-3-->
            </div>
            <!-- /col-sm-6-->
         </div>
         <!-- /row-->
      </div>
   </body>
</html>
<!-- SCRIPTS-->