<aside class="admin-sidebar">
  <div class="admin-sidebar-header">
    <div class="admin-logo">
      <img src="{{ asset('img/logo/logo_sipensi_3d.png') }}" alt="SIPENSI" class="admin-logo-img">
    </div>
    <button class="admin-sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <nav class="admin-sidebar-nav">
    <ul class="admin-menu">
      {{-- DASHBOARD --}}
      <li class="admin-menu-section">
        <span class="admin-menu-section-title">DASHBOARD</span>
      </li>
      <li class="admin-menu-item">
        <a href="{{ route('dashboard') }}" class="admin-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="fas fa-home admin-menu-icon"></i>
          <span class="admin-menu-text">Dashboard</span>
        </a>
      </li>

      {{-- MANAJEMEN DATA MASTER --}}
      <li class="admin-menu-section">
        <span class="admin-menu-section-title">MANAJEMEN DATA MASTER</span>
      </li>
      <li class="admin-menu-item admin-menu-item--has-dropdown {{ request()->routeIs('role-user.*') ? 'active' : '' }}">
        <a href="#" class="admin-menu-link admin-menu-link--dropdown {{ request()->routeIs('role-user.*') ? 'active' : '' }}" data-dropdown="role-user">
          <i class="fas fa-user admin-menu-icon"></i>
          <span class="admin-menu-text">Role User</span>
          <i class="fas fa-chevron-down admin-menu-arrow"></i>
        </a>
        <ul class="admin-submenu" id="dropdown-role-user">
          <li class="admin-submenu-item">
            <a href="{{ route('role-user.admin.index') }}" class="admin-submenu-link {{ request()->routeIs('role-user.admin.*') ? 'active' : '' }}">
              <i class="fas fa-chevron-right admin-submenu-icon"></i>
              <span>Admin</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="admin-menu-item admin-menu-item--has-dropdown {{ request()->routeIs('wilayah.*') ? 'active' : '' }}">
        <a href="#" class="admin-menu-link admin-menu-link--dropdown {{ request()->routeIs('wilayah.*') ? 'active' : '' }}" data-dropdown="wilayah">
          <i class="fas fa-map admin-menu-icon"></i>
          <span class="admin-menu-text">Wilayah</span>
          <i class="fas fa-chevron-down admin-menu-arrow"></i>
        </a>
        <ul class="admin-submenu" id="dropdown-wilayah">
          <li class="admin-submenu-item">
            <a href="{{ route('wilayah.provinsi.index') }}" class="admin-submenu-link {{ request()->routeIs('wilayah.provinsi.*') ? 'active' : '' }}">
              <i class="fas fa-chevron-right admin-submenu-icon"></i>
              <span>Provinsi</span>
            </a>
          </li>
          <li class="admin-submenu-item">
            <a href="{{ route('wilayah.kabupaten.index') }}" class="admin-submenu-link {{ request()->routeIs('wilayah.kabupaten.*') ? 'active' : '' }}">
              <i class="fas fa-chevron-right admin-submenu-icon"></i>
              <span>Kabupaten/Kota</span>
            </a>
          </li>
          <li class="admin-submenu-item">
            <a href="{{ route('wilayah.kecamatan.index') }}" class="admin-submenu-link {{ request()->routeIs('wilayah.kecamatan.*') ? 'active' : '' }}">
              <i class="fas fa-chevron-right admin-submenu-icon"></i>
              <span>Kecamatan</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="admin-menu-item">
        <a href="{{ route('bidang-usaha.index') }}" class="admin-menu-link {{ request()->routeIs('bidang-usaha.*') ? 'active' : '' }}">
          <i class="fas fa-credit-card admin-menu-icon"></i>
          <span class="admin-menu-text">Bidang Usaha Tenant</span>
        </a>
      </li>
      <li class="admin-menu-item">
        <a href="{{ route('klasifikasi-bisnis.index') }}" class="admin-menu-link {{ request()->routeIs('klasifikasi-bisnis.*') ? 'active' : '' }}">
          <i class="fas fa-list admin-menu-icon"></i>
          <span class="admin-menu-text">Klasifikasi Bisnis Tenant</span>
        </a>
      </li>

      {{-- MANAJEMEN DATA INKUBATOR --}}
      <li class="admin-menu-section">
        <span class="admin-menu-section-title">MANAJEMEN DATA INKUBATOR</span>
      </li>
      <li class="admin-menu-item admin-menu-item--has-dropdown {{ request()->routeIs('lembaga-inkubator.*') ? 'active' : '' }}">
        <a href="#" class="admin-menu-link admin-menu-link--dropdown {{ request()->routeIs('lembaga-inkubator.*') ? 'active' : '' }}" data-dropdown="lembaga-inkubator">
          <i class="fas fa-users admin-menu-icon"></i>
          <span class="admin-menu-text">Lembaga Inkubator</span>
          <i class="fas fa-chevron-down admin-menu-arrow"></i>
        </a>
        <ul class="admin-submenu" id="dropdown-lembaga-inkubator">
          <li class="admin-submenu-item">
            <a href="{{ route('lembaga-inkubator.index') }}" class="admin-submenu-link {{ request()->routeIs('lembaga-inkubator.index') || request()->routeIs('lembaga-inkubator.export.*') ? 'active' : '' }}">
              <i class="fas fa-chevron-right admin-submenu-icon"></i>
              <span>List lembaga inkubator</span>
            </a>
          </li>
          <li class="admin-submenu-item">
            <a href="{{ route('laporan.index') }}" class="admin-submenu-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
              <i class="fas fa-chevron-right admin-submenu-icon"></i>
              <span>List Laporan Lembaga Inkubator</span>
            </a>
          </li>
          <li class="admin-submenu-item">
            <a href="{{ route('persetujuan.index') }}" class="admin-submenu-link {{ request()->routeIs('persetujuan.*') ? 'active' : '' }}">
              <i class="fas fa-chevron-right admin-submenu-icon"></i>
              <span>Persetujuan</span>
            </a>
          </li>
          <li class="admin-submenu-item">
            <a href="{{ route('pemeringkatan.index') }}" class="admin-submenu-link {{ request()->routeIs('pemeringkatan.*') ? 'active' : '' }}">
              <i class="fas fa-chevron-right admin-submenu-icon"></i>
              <span>Pemeringkatan</span>
            </a>
          </li>
        </ul>
      </li>

      {{-- MANAJEMEN DATA PUBLIK --}}
      <li class="admin-menu-section">
        <span class="admin-menu-section-title">MANAJEMEN DATA PUBLIK</span>
      </li>
      <li class="admin-menu-item">
        <a href="{{ route('manajemen-gambar.index') }}" class="admin-menu-link {{ request()->routeIs('manajemen-gambar.*') ? 'active' : '' }}">
          <i class="fas fa-image admin-menu-icon"></i>
          <span class="admin-menu-text">Manajemen Gambar</span>
        </a>
      </li>
      <li class="admin-menu-item">
        <a href="{{ route('admin.berita.index') }}" class="admin-menu-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
          <i class="fas fa-newspaper admin-menu-icon"></i>
          <span class="admin-menu-text">Berita</span>
        </a>
      </li>
      <li class="admin-menu-item">
        <a href="{{ route('admin.kontak.index') }}" class="admin-menu-link {{ request()->routeIs('admin.kontak.*') ? 'active' : '' }}">
          <i class="fas fa-envelope admin-menu-icon"></i>
          <span class="admin-menu-text">Kontak Kami</span>
        </a>
      </li>
    </ul>
  </nav>

  {{-- SEARCH BAR --}}
  <div class="admin-sidebar-search">
    <div class="admin-search-box">
      <i class="fas fa-search admin-search-icon"></i>
      <input type="text" class="admin-search-input" placeholder="Search...">
    </div>
  </div>
</aside>
