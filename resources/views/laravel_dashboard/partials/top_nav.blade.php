<div class="container-fluid">
    
    
<ul class="nav navbar-nav">
    <li class="">
        <a href="{{ url('/home') }}"><i class="fa fa-tachometer"></i> Dashboard</a>
    </li>

    <li class="">
        <a href="{{ url('/items') }}"><i class="fa fa-server"></i> Items</a>
    </li>

    <li class="">
        <a href="{{ url('/categories') }}"><i class="fa fa-bookmark"></i> Categories</a>
    </li>

    <li class="">
        <a href="{{ url('/customers') }}"><i class="fa fa-users"></i> Customers</a>
    </li>

    <li class="">
        <a href="{{ url('/suppliers') }}"><i class="fa fa-users"></i> Suppliers</a>
    </li>

    <li class="">
        <a href="{{ url('/sales') }}"><i class="fa fa-shopping-bag"></i> Sales</a>
    </li>
</ul>
<ul class="nav navbar-nav navbar-right">
    
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {{ Auth::user()->name }}
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="#"><i class="fa fa-users"></i> Users</a></li>
            <li><a href="{{ url('/profile/' . Auth::user()->id) }}"><i class="fa fa-user"></i> Profile</a></li>
            <li><a href="{{ url('/settings') }}"><i class="fa fa-cog"></i> Settings</a></li>
            <li role="separator" class="divider"></li>
            <li>
                <a href="{{ url('/logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i> Logout
                </a>

                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </li>
    <li>
        <a href="#" @click.prevent="" data-toggle="control-sidebar"><i class="fa fa-cogs"></i></a>
    </li>
</ul>

</div>