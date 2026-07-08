<aside
  :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
  class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 duration-300 ease-linear dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
  @click.outside="sidebarToggle = false"
>
  <div
    :class="sidebarToggle ? 'justify-center' : 'justify-between'"
    class="sidebar-header flex items-center gap-3 pb-7 pt-8"
  >
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
      <img
        class="h-10 w-10 rounded-lg object-cover"
        src="{{ $adminSiteLogo ?? asset('vendor/tailadmin/src/images/logo/logo.svg') }}"
        alt="Logo"
      />
      <span class="text-base font-semibold text-gray-900 dark:text-white" :class="sidebarToggle ? 'lg:hidden' : ''">
        {{ $adminSiteName ?? 'TailAdmin' }}
      </span>
    </a>
  </div>

  <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
    <nav
      x-data="{ selected: $persist('Dashboard') }"
      x-init="selected = window.location.pathname.includes('/admin/user-management') ? 'User Management' : window.location.pathname.includes('/admin/roles-permissions') ? 'Roles & Permissions' : window.location.pathname.includes('/admin/general-setting') ? 'General Setting' : 'Dashboard'"
    >
      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
          <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">MENU</span>
        </h3>

        <ul class="mb-6 flex flex-col gap-4">
          <li>
            <a
              href="{{ route('admin.dashboard') }}"
              @click="selected = 'Dashboard'"
              class="menu-item group"
              :class="(selected === 'Dashboard' || page === 'dashboard' || page === 'ecommerce') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Dashboard</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.user-management') }}"
              @click="selected = 'User Management'"
              class="menu-item group"
              :class="(selected === 'User Management' || page === 'userManagement') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">User Management</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.roles-permissions') }}"
              @click="selected = 'Roles & Permissions'"
              class="menu-item group"
              :class="(selected === 'Roles & Permissions' || page === 'rolesPermissions') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Roles & Permissions</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.general-setting') }}"
              @click="selected = 'General Setting'"
              class="menu-item group"
              :class="(selected === 'General Setting' || page === 'generalSetting') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">General Setting</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</aside>
