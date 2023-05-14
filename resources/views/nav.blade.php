<?php $parent_category= Illuminate\Support\Facades\DB::select('SELECT * from categories WHERE parent_id IS NULL'); ?>
<nav class='navbar navbar-default'>
    <div class='container-fluid'>
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class='navbar-header'>
            <button type='button' class='navbar-toggle collapsed' data-toggle='collapse'
                data-target='#bs-example-navbar-collapse-1' aria-expanded='false'>
                <span class='sr-only'>Toggle navigation</span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
            </button>
            <a class='navbar-brand' href='/blog/public/'>Blog System</a>
        </div>
        <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
            <ul class='nav navbar-nav'>
                <?php foreach($parent_category as $cate): ?>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false"><?php echo $cate->cate_name;?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php $child_category = Illuminate\Support\Facades\DB::select('SELECT * from categories WHERE parent_id =?', [$cate->cate_id]); foreach($child_category as $child): ?>
                        <li><a
                                href="/blog/public/category/<?php echo $child->cate_id;?>"><?php echo $child->cate_name;?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                @endforeach
            </ul>

            <!-- <form class="navbar-form navbar-left" id="searchForm" action="/blog/public/search" method="POST">
                <div class="form-group">
                    <input type="text" id="keyword" name="keyword" size="30" class="form-control"
                        placeholder="Search Blogs Or Comments">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form> -->
            <ul class='nav navbar-nav navbar-right'>
                @if(Session::has('uid'))
                <li><a type="button" style="color:#3c78d8;"
                        href='/blog/public/admin/index'>{{ Session::get('nickname')}}</a></li>
                <li><a href='/blog/public/logout'>Logout</a></li>
                @else
                <li><a href='/blog/public/login'>Login</a></li>
                <li><a type="button" style="color:#3c78d8;" href='/blog/public/register'>Register</a></li>
                @endif


            </ul>
        </div>

    </div><!-- /.container-fluid -->
</nav>