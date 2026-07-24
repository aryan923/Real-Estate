<aside
  :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
  class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[220px] lg:w-[220px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-4 duration-300 ease-linear dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0"
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
      x-init="selected = window.location.pathname.includes('/admin/enquiries') ? 'Leads' : window.location.pathname.includes('/admin/customers') ? 'Customers' : window.location.pathname.includes('/admin/projects') ? 'Projects' : window.location.pathname.includes('/admin/inventory') ? 'Inventory' : window.location.pathname.includes('/admin/site-visits') ? 'Site Visits' : window.location.pathname.includes('/admin/follow-ups') ? 'Follow-ups' : window.location.pathname.includes('/admin/bookings') ? 'Bookings' : window.location.pathname.includes('/admin/payments') ? 'Payments' : window.location.pathname.includes('/admin/documents') ? 'Documents' : window.location.pathname.includes('/admin/reports') ? 'Reports' : window.location.pathname.includes('/admin/user-management') ? 'Users' : window.location.pathname.includes('/admin/general-setting') ? 'Settings' : 'Dashboard'"
    >
      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
          <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">MENU</span>
        </h3>

        <ul class="mb-6 flex flex-col gap-4">
          @can('view_dashboard')
          <li>
            <a
              href="{{ route('admin.dashboard') }}"
              @click="selected = 'Dashboard'"
              class="menu-item group"
              :class="(selected === 'Dashboard') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Dashboard') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Dashboard</span>
            </a>
          </li>
          @endcan

          @can('view_enquiries')
          <li>
            <a
              href="{{ route('admin.enquiries.index') }}"
              @click="selected = 'Leads'"
              class="menu-item group"
              :class="(selected === 'Leads') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Leads') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Leads</span>
            </a>
          </li>
          @endcan

          @can('view_customers')
          <li>
            <a
              href="{{ route('admin.customers.index') }}"
              @click="selected = 'Customers'"
              class="menu-item group"
              :class="(selected === 'Customers') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Customers') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Customers</span>
            </a>
          </li>
          @endcan

          @can('view_projects')
          <li>
            <a
              href="{{ route('admin.projects.index') }}"
              @click="selected = 'Projects'"
              class="menu-item group"
              :class="(selected === 'Projects') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Projects') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Projects</span>
            </a>
          </li>
          @endcan

          @can('view_properties')
          <li>
            <a
              href="{{ route('admin.inventory.index') }}"
              @click="selected = 'Inventory'"
              class="menu-item group"
              :class="(selected === 'Inventory') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Inventory') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Inventory</span>
            </a>
          </li>
          @endcan

          @can('view_site_visits')
          <li>
            <a
              href="{{ route('admin.site-visits.index') }}"
              @click="selected = 'Site Visits'"
              class="menu-item group"
              :class="(selected === 'Site Visits') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Site Visits') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Site Visits</span>
            </a>
          </li>
          @endcan



          @can('view_bookings')
          <li>
            <a
              href="{{ route('admin.bookings.index') }}"
              @click="selected = 'Bookings'"
              class="menu-item group"
              :class="(selected === 'Bookings') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Bookings') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Bookings</span>
            </a>
          </li>
          @endcan

          @can('view_payments')
          <li>
            <a
              href="{{ route('admin.payments.index') }}"
              @click="selected = 'Payments'"
              class="menu-item group"
              :class="(selected === 'Payments') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Payments') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Payments</span>
            </a>
          </li>
          @endcan

          @can('view_documents')
          <li>
            <a
              href="{{ route('admin.documents.index') }}"
              @click="selected = 'Documents'"
              class="menu-item group"
              :class="(selected === 'Documents') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Documents') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Documents</span>
            </a>
          </li>
          @endcan

          @can('view_reports')
          <li>
            <a
              href="{{ route('admin.reports.index') }}"
              @click="selected = 'Reports'"
              class="menu-item group"
              :class="(selected === 'Reports') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Reports') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Reports</span>
            </a>
          </li>
          @endcan

          @can('view_users')
          <li>
            <a
              href="{{ route('admin.user-management') }}"
              @click="selected = 'Users'"
              class="menu-item group"
              :class="(selected === 'Users') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Users') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Users</span>
            </a>
          </li>
          @endcan

          @can('view_settings')
          <li>
            <a
              href="{{ route('admin.general-setting') }}"
              @click="selected = 'Settings'"
              class="menu-item group"
              :class="(selected === 'Settings') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Settings') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Settings</span>
            </a>
          </li>
          @endcan
        </ul>
      </div>

      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
          <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">CMS & MASTER</span>
        </h3>

        <ul class="mb-6 flex flex-col gap-4">
          @can('view_properties')
          <li>
            <a
              href="{{ route('admin.properties') }}"
              @click="selected = 'Properties'"
              class="menu-item group"
              :class="(selected === 'Properties') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Properties') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Properties</span>
            </a>
          </li>
          @endcan

          @can('view_categories')
          <li>
            <a
              href="{{ route('admin.categories') }}"
              @click="selected = 'Categories'"
              class="menu-item group"
              :class="(selected === 'Categories') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Categories') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 5C4 3.89543 4.89543 3 6 3H10L12 5H18C19.1046 5 20 5.89543 20 7V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Categories</span>
            </a>
          </li>
          @endcan

          @can('view_features')
          <li>
            <a
              href="{{ route('admin.features') }}"
              @click="selected = 'Features'"
              class="menu-item group"
              :class="(selected === 'Features') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Features') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 17.25L7.053 20.69L8.276 14.31L4 10.18L10.449 9.47L12 3.25L13.551 9.47L20 10.18L15.724 14.31L16.947 20.69L12 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Features</span>
            </a>
          </li>
          @endcan

          @can('view_cities')
          <li>
            <a
              href="{{ route('admin.cities') }}"
              @click="selected = 'Cities'"
              class="menu-item group"
              :class="(selected === 'Cities') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Cities') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Cities</span>
            </a>
          </li>
          @endcan

          @can('view_currencies')
          <li>
            <a
              href="{{ route('admin.currencies') }}"
              @click="selected = 'Currencies'"
              class="menu-item group"
              :class="(selected === 'Currencies') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Currencies') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Currencies</span>
            </a>
          </li>
          @endcan

          @can('view_testimonials')
          <li>
            <a
              href="{{ route('admin.testimonials') }}"
              @click="selected = 'Testimonials'"
              class="menu-item group"
              :class="(selected === 'Testimonials') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Testimonials') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Testimonials</span>
            </a>
          </li>
          @endcan

          @can('view_agents')
          <li>
            <a
              href="{{ route('admin.agents') }}"
              @click="selected = 'Agents'"
              class="menu-item group"
              :class="(selected === 'Agents') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Agents') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Agents</span>
            </a>
          </li>
          @endcan

          @can('view_posts')
          <li>
            <a
              href="{{ route('admin.posts') }}"
              @click="selected = 'Blog'"
              class="menu-item group"
              :class="(selected === 'Blog') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Blog') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10l4 4v10a2 2 0 0 1-2 2zM12 11H7m10 4H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Blog</span>
            </a>
          </li>
          @endcan

          @can('view_pages')
          <li>
            <a
              href="{{ route('admin.pages') }}"
              @click="selected = 'Pages'"
              class="menu-item group"
              :class="(selected === 'Pages') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Pages') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Pages</span>
            </a>
          </li>
          @endcan

          @can('view_comments')
          <li>
            <a
              href="{{ route('admin.comments') }}"
              @click="selected = 'Comments'"
              class="menu-item group"
              :class="(selected === 'Comments') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Comments') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Comments</span>
            </a>
          </li>
          @endcan

          @can('view_roles')
          <li>
            <a
              href="{{ route('admin.roles-permissions') }}"
              @click="selected = 'Roles'"
              class="menu-item group"
              :class="(selected === 'Roles') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg :class="(selected === 'Roles') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Roles</span>
            </a>
          </li>
          @endcan
        </ul>
      </div>
    </nav>
  </div>
</aside>
