<?php $this->load->view('_heading/_headerContent'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Dashboard
    <small>Control panel</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>150</h3>
          <p>New Users</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-person-add"></i>
        </div>
        <a href="<?php echo site_url('user'); ?>" class="small-box-footer ajaxify">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>53<sup style="font-size:20px">%</sup></h3>
          <p>Active Sessions</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="<?php echo site_url('user'); ?>" class="small-box-footer ajaxify">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>44</h3>
          <p>User Registrations</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-stalker"></i>
        </div>
        <a href="<?php echo site_url('user'); ?>" class="small-box-footer ajaxify">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>65</h3>
          <p>Unique Visitors</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="<?php echo site_url('user'); ?>" class="small-box-footer ajaxify">
          More info <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <!-- ./col -->
  </div>
  <!-- /.row -->

  <!-- Main row -->
  <div class="row">
    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">

      <!-- Area chart -->
      <div class="box box-info">
        <div class="box-header with-border">
          <i class="fa fa-bar-chart-o"></i>
          <h3 class="box-title">Visitors Overview</h3>
          <div class="box-tools pull-right">
            <ul class="nav nav-pills ui-sortable-handle" id="revenue-chart-tab">
              <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
              <li><a href="#sales-chart" data-toggle="tab">Donut</a></li>
            </ul>
          </div>
        </div>
        <div class="box-body">
          <div class="tab-content no-padding">
            <!-- Area chart -->
            <div class="chart tab-pane active" id="revenue-chart" style="position:relative;height:300px;"></div>
            <!-- Donut chart -->
            <div class="chart tab-pane" id="sales-chart" style="position:relative;height:300px;"></div>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <!-- Chat box -->
      <div class="box box-warning direct-chat direct-chat-warning">
        <div class="box-header">
          <i class="fa fa-comments-o"></i>
          <h3 class="box-title">Direct Chat</h3>
          <div class="box-tools pull-right">
            <span data-toggle="tooltip" title="3 New Messages" class="badge bg-yellow">3</span>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="direct-chat-messages">
            <!-- Message 1 -->
            <div class="direct-chat-msg">
              <div class="direct-chat-info clearfix">
                <span class="direct-chat-name pull-left">Alexander Pierce</span>
                <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
              </div>
              <img class="direct-chat-img" src="<?php echo base_url(); ?>assets/admin-lte/dist/img/user1-128x128.jpg" alt="user image">
              <div class="direct-chat-text">
                Is this template really for free? That is amazing. Thank you so much!
              </div>
            </div>
            <!-- Message 2 -->
            <div class="direct-chat-msg right">
              <div class="direct-chat-info clearfix">
                <span class="direct-chat-name pull-right">Sarah Bullock</span>
                <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
              </div>
              <img class="direct-chat-img" src="<?php echo base_url(); ?>assets/admin-lte/dist/img/user3-128x128.jpg" alt="user image">
              <div class="direct-chat-text">
                You're welcome! Yes, it's completely free.
              </div>
            </div>
            <!-- Message 3 -->
            <div class="direct-chat-msg">
              <div class="direct-chat-info clearfix">
                <span class="direct-chat-name pull-left">Alexander Pierce</span>
                <span class="direct-chat-timestamp pull-right">23 Jan 5:37 pm</span>
              </div>
              <img class="direct-chat-img" src="<?php echo base_url(); ?>assets/admin-lte/dist/img/user1-128x128.jpg" alt="user image">
              <div class="direct-chat-text">
                I will spread the word about this template.
              </div>
            </div>
          </div>
          <!-- /.direct-chat-messages -->
        </div>
        <div class="box-footer">
          <form action="#" method="post">
            <div class="input-group">
              <input type="text" name="message" placeholder="Type Message ..." class="form-control">
              <span class="input-group-btn">
                <button type="button" class="btn btn-warning btn-flat">Send</button>
              </span>
            </div>
          </form>
        </div>
      </div>
      <!-- /.direct-chat -->

      <!-- TO DO List -->
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-flag-o"></i>
          <h3 class="box-title">To Do List</h3>
          <div class="box-tools pull-right">
            <ul class="pagination pagination-sm no-margin pull-right">
              <li><a href="#">«</a></li>
              <li><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">»</a></li>
            </ul>
          </div>
        </div>
        <div class="box-body">
          <ul class="todo-list">
            <li>
              <span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
              <div class="icheckbox_flat-blue" style="position:relative"><input type="checkbox" style="position:absolute;opacity:0"></div>
              <span class="text">Design a nice theme</span>
              <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
              <div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o"></i></div>
            </li>
            <li>
              <span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
              <div class="icheckbox_flat-blue" style="position:relative"><input type="checkbox" style="position:absolute;opacity:0"></div>
              <span class="text">Make the theme responsive</span>
              <small class="label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
              <div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o"></i></div>
            </li>
            <li>
              <span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
              <div class="icheckbox_flat-blue" style="position:relative"><input type="checkbox" style="position:absolute;opacity:0"></div>
              <span class="text">Let theme shine like a star</span>
              <small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>
              <div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o"></i></div>
            </li>
            <li>
              <span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
              <div class="icheckbox_flat-blue" style="position:relative"><input type="checkbox" style="position:absolute;opacity:0"></div>
              <span class="text">Check your messages and notifications</span>
              <small class="label label-success"><i class="fa fa-clock-o"></i> 1 week</small>
              <div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o"></i></div>
            </li>
            <li>
              <span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
              <div class="icheckbox_flat-blue" style="position:relative"><input type="checkbox" style="position:absolute;opacity:0"></div>
              <span class="text">Plan for next month</span>
              <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 month</small>
              <div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o"></i></div>
            </li>
          </ul>
        </div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>
        </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.Left col -->

    <!-- Right col -->
    <section class="col-lg-5 connectedSortable">

      <!-- Map box -->
      <div class="box box-solid bg-light-blue-gradient">
        <div class="box-header">
          <i class="fa fa-map-marker"></i>
          <h3 class="box-title">Quick Access</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range">
              <i class="fa fa-calendar"></i>
            </button>
            <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-tooltip="tooltip" title="Collapse">
              <i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body pad">
          <div class="row">
            <div class="col-xs-4 text-center" style="padding:10px 5px">
              <a href="<?php echo site_url('user'); ?>" class="ajaxify btn btn-app" style="width:100%">
                <i class="fa fa-user"></i> Users
              </a>
            </div>
            <div class="col-xs-4 text-center" style="padding:10px 5px">
              <a href="<?php echo site_url('menu'); ?>" class="ajaxify btn btn-app" style="width:100%">
                <i class="fa fa-th-list"></i> Menus
              </a>
            </div>
            <div class="col-xs-4 text-center" style="padding:10px 5px">
              <a href="<?php echo site_url('user-grup'); ?>" class="ajaxify btn btn-app" style="width:100%">
                <i class="fa fa-users"></i> Groups
              </a>
            </div>
            <div class="col-xs-4 text-center" style="padding:10px 5px">
              <a href="<?php echo site_url('department'); ?>" class="ajaxify btn btn-app" style="width:100%">
                <i class="fa fa-building-o"></i> Dept.
              </a>
            </div>
            <div class="col-xs-4 text-center" style="padding:10px 5px">
              <a href="<?php echo site_url('cabang'); ?>" class="ajaxify btn btn-app" style="width:100%">
                <i class="fa fa-map-marker"></i> Branch
              </a>
            </div>
            <div class="col-xs-4 text-center" style="padding:10px 5px">
              <a href="<?php echo site_url('profile'); ?>" class="ajaxify btn btn-app" style="width:100%">
                <i class="fa fa-user-circle"></i> Profile
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box -->

      <!-- Recent Activity -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Recent Activity</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <ul class="timeline timeline-inverse">
            <li>
              <i class="fa fa-user bg-aqua"></i>
              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 12 mins ago</span>
                <h3 class="timeline-header"><a href="#">Admin System</a> added a new user account</h3>
              </div>
            </li>
            <li>
              <i class="fa fa-pencil bg-yellow"></i>
              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 1 hour ago</span>
                <h3 class="timeline-header"><a href="#">HR Admin</a> updated department data</h3>
              </div>
            </li>
            <li>
              <i class="fa fa-building-o bg-orange"></i>
              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 2 hours ago</span>
                <h3 class="timeline-header"><a href="#">Super Admin</a> registered a new branch</h3>
              </div>
            </li>
            <li>
              <i class="fa fa-cog bg-green"></i>
              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> Yesterday</span>
                <h3 class="timeline-header"><a href="#">You</a> changed profile settings</h3>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <!-- /.box -->

      <!-- Tasks Progress -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Tasks Progress</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <ul class="list-unstyled">
            <li>
              <a href="#">Custom Template Design</a>
              <div class="progress progress-xs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </li>
            <li>
              <a href="#">Update Resume</a>
              <div class="progress progress-xs progress-striped active">
                <div class="progress-bar progress-bar-warning" style="width: 95%"></div>
              </div>
            </li>
            <li>
              <a href="#">Laravel Integration</a>
              <div class="progress progress-xs">
                <div class="progress-bar progress-bar-primary" style="width: 50%"></div>
              </div>
            </li>
            <li>
              <a href="#">Back End Framework</a>
              <div class="progress progress-xs progress-striped active">
                <div class="progress-bar progress-bar-success" style="width: 68%"></div>
              </div>
            </li>
          </ul>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.Right col -->
  </div>
  <!-- /.row (main row) -->

</section>
<!-- /.content -->

<!-- Flot Charts & Graph Scripts -->
<script src="<?php echo base_url(); ?>assets/admin-lte/bower_components/flot/jquery.flot.js"></script>
<script src="<?php echo base_url(); ?>assets/admin-lte/bower_components/flot/jquery.flot.resize.js"></script>

<script>
$(function () {

  /* Area chart - Visitors */
  var areaData = [];
  var areaData2 = [];
  for (var i = 1; i <= 30; i++) {
    areaData.push([i, Math.floor(Math.random() * 100) + 20]);
    areaData2.push([i, Math.floor(Math.random() * 80) + 10]);
  }
  $.plot('#revenue-chart', [
    {
      data: areaData,
      lines: { show: true, fill: true },
      color: '#00c0ef',
      label: 'Visitors'
    },
    {
      data: areaData2,
      lines: { show: true, fill: true },
      color: '#39cccc',
      label: 'Page Views'
    }
  ], {
    grid: { hoverable: true, clickable: true, borderWidth: 0 },
    yaxis: { min: 0 },
    xaxis: { tickDecimals: 0 }
  });

  /* Donut chart - Sales */
  $.plot('#sales-chart', [
    { label: 'Users', data: 44, color: '#00a65a' },
    { label: 'Groups', data: 18, color: '#f56954' },
    { label: 'Dept.', data: 14, color: '#00c0ef' },
    { label: 'Branch', data: 6, color: '#f39c12' }
  ], {
    series: {
      pie: {
        show: true,
        innerRadius: 0.4,
        label: {
          show: true,
          formatter: function (label, series) {
            return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
          },
          background: { opacity: 0.6, color: '#000' }
        }
      }
    }
  });

});
</script>