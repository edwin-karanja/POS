<div class="row">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{ asset('img/icon-user-default.png') }}" alt="User profile picture">

                <h3 class="profile-username text-center">{{ $customer->name }}</h3>
                <p class="text-muted text-center">{{ $customer->address }}</p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Email</b> <a class="pull-right">{{ $customer->email or 'Empty' }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Telephone</b> <a class="pull-right">{{ $customer->phone_number or 'Empty' }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Transactions To Date</b> <a class="pull-right">{{ $countAllSales }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Total Cash Transacted</b> <a class="pull-right">{{ 'Kshs' }} {{ number_format($totalSale, 2) }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Balance Due</b> <a class="pull-right">{{ 'Kshs' }} {{ number_format(0, 2) }}</a>
                    </li>
                </ul>
                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
            </div>
          <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ number_format($totalSale, 2) }}</h3>
                        <h4>Total Sales</h4>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    &nbsp;
                </div>
            </div>

            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ number_format($todayTotal, 2) }}</h3>
                        <h4>Today's Sale</h4>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    &nbsp;
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $countAllSales }}</h3>
                        <h4>Transactions</h4>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    &nbsp;
                </div>
            </div>

            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $countTodaySales }}</h3>
                        <h4>Today's Transactions</h4>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
</div>