    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">TEAM 26</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <?php
              if(!isset($path['call_parts'][0]) || $path['call_parts'][0] == '') {
                echo '<li class="active"><a href="'.$path['url'].'">Home</a></li>';
                echo '<li><a href="'.$path['url'].'analysis/">Analysis</a></li>';
              }else if($path['call_parts'][0] == 'home') {
                echo '<li class="active"><a href="'.$path['url'].'">Home</a></li>';
                echo '<li><a href="'.$path['url'].'analysis/">Analysis</a></li>';
              }else if($path['call_parts'][0] == 'analysis') {
                echo '<li><a href="'.$path['url'].'">Home</a></li>';
                echo '<li class="active"><a href="'.$path['url'].'analysis/">Analysis</a></li>';
              }
            ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>