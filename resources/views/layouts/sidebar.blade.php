@push('css')
    <style>
        .main-sidebar,
        .sidebar {
            height: 100vh !important;
            /* Pastikan sidebar memenuhi tinggi layar */
            overflow-y: auto !important;
            /* Paksa scrollbar vertikal agar muncul */
        }
    </style>
@endpush

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
                <li class="header">MASTER</li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cube"></i> <span>Kategori</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active">
                            <a href="{{ route('kategori.index') }}">
                                <i class="fa fa-circle-o"></i> <span>Kategori Produk</span>
                            </a>
                        </li>

                        <li class="active">
                            <a href="{{ route('kategori_lapangan.index') }}">
                                <i class="fa fa-circle-o"></i> <span>Kategori Lapangan</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('produk.index') }}">
                        <i class="fa fa-cubes"></i> <span>Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('lapangan.index') }}">
                        <i class="fa fa-futbol-o"></i> <span>Lapangan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('member.index') }}">
                        <i class="fa fa-id-card"></i> <span>Member</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('supplier.index') }}">
                        <i class="fa fa-truck"></i> <span>Supplier</span>
                    </a>
                </li>
                <li class="header">TRANSAKSI</li>
                <li>
                    <a href="{{ route('pengeluaran.index') }}">
                        <i class="fa fa-money"></i> <span>Pengeluaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembelian.index') }}">
                        <i class="fa fa-download"></i> <span>Pembelian</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('penjualan.index') }}">
                        <i class="fa fa-upload"></i> <span>Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('penyewaan.index') }}">
                        <i class="fa fa-upload"></i> <span>Penyewaan</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Aktif</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active">
                            <a href="{{ route('transaksi.index') }}">
                                <i class="fa fa-circle-o"></i> <span>Transaksi Produk</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('transaksi_penyewaan.index') }}">
                                <i class="fa fa-circle-o"></i> <span>Transaksi Lapangan</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Baru</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active">
                            <a href="{{ route('transaksi.baru') }}">
                                <i class="fa fa-circle-o"></i> <span>Transaksi Produk</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('transaksi_penyewaan.baru') }}">
                                <i class="fa fa-circle-o"></i> <span>Transaksi Lapangan</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="header">REPORT</li>
                <li>
                    <a href="{{ route('laporan.index') }}">
                        <i class="fa fa-file-pdf-o"></i> <span>Laporan</span>
                    </a>
                </li>
                <li class="header">SYSTEM</li>
                <li>
                    <a href="{{ route('user.index') }}">
                        <i class="fa fa-users"></i> <span>User</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('setting.index') }}">
                        <i class="fa fa-cogs"></i> <span>Pengaturan</span>
                    </a>
                </li>
            @else
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Aktif</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active">
                            <a href="{{ route('transaksi.index') }}">
                                <i class="fa fa-circle-o"></i> <span>Transaksi Produk</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('transaksi_penyewaan.index') }}">
                                <i class="fa fa-circle-o"></i> <span>Transaksi Lapangan</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Baru</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active">
                            <a href="{{ route('transaksi.baru') }}">
                                <i class="fa fa-circle-o"></i> <span>Transaksi Produk</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('transaksi_penyewaan.baru') }}">
                                <i class="fa fa-circle-o"></i> <span>transaksi Lapangan</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
