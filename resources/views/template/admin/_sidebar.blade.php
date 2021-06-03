
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar mx-auto" height="100" src="{{ asset('assets/images/logo/'.get_logo()) }}" alt="User Image">
      </div>
      <ul class="app-menu">
        <li><a class="app-menu__item {{ Request::path() == 'admin' ? 'active' : '' }}" href="/admin"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
        <li><a class="app-menu__item {{ strpos(Request::url(), '/admin/user') ? 'active' : '' }}" href="/admin/user"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">User</span></a></li>
        <li><a class="app-menu__item {{ strpos(Request::url(), '/admin/transaksi') ? 'active' : '' }}" href="/admin/transaksi"><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Transaksi</span></a></li>
        <li><a class="app-menu__item {{ strpos(Request::url(), '/admin/email') ? 'active' : '' }}" href="/admin/email"><i class="app-menu__icon fa fa-envelope"></i><span class="app-menu__label">Email</span></a></li>
        <li><a class="app-menu__item {{ strpos(Request::url(), '/admin/pengaturan') ? 'active' : '' }}" href="/admin/pengaturan"><i class="app-menu__icon fa fa-cog"></i><span class="app-menu__label">Pengaturan</span></a></li>
        <li class="app-menu__submenu"><span class="app-menu__label">Report</span></li>
        <li><a class="app-menu__item {{ strpos(Request::url(), '/admin/report/analisa') ? 'active' : '' }}" href="/admin/report/analisa"><i class="app-menu__icon fa fa-line-chart"></i><span class="app-menu__label">Analisa</span></a></li>
        <li><a class="app-menu__item {{ strpos(Request::url(), '/admin/report/best-customer') ? 'active' : '' }}" href="/admin/report/best-customer"><i class="app-menu__icon fa fa-line-chart"></i><span class="app-menu__label">Best Customer</span></a></li>
        <li><a class="app-menu__item {{ strpos(Request::url(), '/admin/report/middle-customer') ? 'active' : '' }}" href="/admin/report/middle-customer"><i class="app-menu__icon fa fa-line-chart"></i><span class="app-menu__label">Middle Customer</span></a></li>
        <li><a class="app-menu__item {{ strpos(Request::url(), '/admin/report/low-customer') ? 'active' : '' }}" href="/admin/report/low-customer"><i class="app-menu__icon fa fa-line-chart"></i><span class="app-menu__label">Low Customer</span></a></li>
		<li class="treeview {{ strpos(Request::url(), '/admin/report/statistik') ? 'is-expanded' : '' }}"><a class="app-menu__item {{ strpos(Request::url(), '/admin/report/statistik') ? 'active' : '' }}" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-line-chart"></i><span class="app-menu__label">Statistik</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item {{ strpos(Request::url(), '/admin/report/statistik/arpu') ? 'active' : '' }}" href="/admin/report/statistik/arpu"><i class="icon fa fa-circle-o"></i> ARPU</a></li>
            <li><a class="treeview-item {{ strpos(Request::url(), '/admin/report/statistik/top-up') ? 'active' : '' }}" href="/admin/report/statistik/top-up"><i class="icon fa fa-circle-o"></i> Top Up</a></li>
            <li><a class="treeview-item {{ strpos(Request::url(), '/admin/report/statistik/trx') ? 'active' : '' }}" href="/admin/report/statistik/trx"><i class="icon fa fa-circle-o"></i> TRX</a></li>
          </ul>
        </li>
      </ul>
    </aside>