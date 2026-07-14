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
      x-init="selected = window.location.pathname.includes('/admin/enquiries') ? 'Manage Leads' : window.location.pathname.includes('/admin/testimonials') ? 'Manage Testimonials' : window.location.pathname.includes('/admin/posts') ? 'Manage Blog' : window.location.pathname.includes('/admin/comments') ? 'Manage Comments' : window.location.pathname.includes('/admin/agents') ? 'Manage Agents' : window.location.pathname.includes('/admin/user-management') ? 'User Management' : window.location.pathname.includes('/admin/roles-permissions') ? 'Roles & Permissions' : window.location.pathname.includes('/admin/general-setting') ? 'General Setting' : window.location.pathname.includes('/admin/categories') ? 'Categories' : window.location.pathname.includes('/admin/features') ? 'Features' : window.location.pathname.includes('/admin/cities') ? 'Cities' : window.location.pathname.includes('/admin/currencies') ? 'Currencies' : 'Dashboard'"
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
              <svg
                :class="(selected === 'Dashboard' || page === 'dashboard' || page === 'ecommerce') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z"
                  fill="currentColor"
                />
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Dashboard</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.page', ['page' => 'properties']) }}"
              @click="selected = 'Properties'"
              class="menu-item group"
              :class="(selected === 'Properties' || page === 'properties') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Properties' || page === 'properties') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M4 5C4 3.89543 4.89543 3 6 3H18C19.1046 3 20 3.89543 20 5V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 9H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M9 13H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M9 17H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Properties</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.enquiries.index') }}"
              @click="selected = 'Manage Leads'"
              class="menu-item group"
              :class="(selected === 'Manage Leads' || page === 'enquiries') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Manage Leads' || page === 'enquiries') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Manage Leads</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.testimonials') }}"
              @click="selected = 'Manage Testimonials'"
              class="menu-item group"
              :class="(selected === 'Manage Testimonials' || page === 'testimonials') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Manage Testimonials' || page === 'testimonials') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Manage Testimonials</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.posts') }}"
              @click="selected = 'Manage Blog'"
              class="menu-item group"
              :class="(selected === 'Manage Blog' || page === 'posts') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Manage Blog' || page === 'posts') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10l4 4v10a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 11H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M17 15H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Manage Blog</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.comments') }}"
              @click="selected = 'Manage Comments'"
              class="menu-item group"
              :class="(selected === 'Manage Comments' || page === 'comments') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Manage Comments' || page === 'comments') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Manage Comments</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.agents') }}"
              @click="selected = 'Manage Agents'"
              class="menu-item group"
              :class="(selected === 'Manage Agents' || page === 'agents') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Manage Agents' || page === 'agents') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Manage Agents</span>
            </a>
          </li>

          <li>
            <a
              href="{{ route('admin.user-management') }}"
              @click="selected = 'User Management'"
              class="menu-item group"
              :class="(selected === 'User Management' || page === 'userManagement') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'User Management' || page === 'userManagement') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M5.25 6.25C5.25 4.73122 6.48122 3.5 8 3.5C9.51878 3.5 10.75 4.73122 10.75 6.25C10.75 7.76878 9.51878 9 8 9C6.48122 9 5.25 7.76878 5.25 6.25ZM8 5C7.30964 5 6.75 5.55964 6.75 6.25C6.75 6.94036 7.30964 7.5 8 7.5C8.69036 7.5 9.25 6.94036 9.25 6.25C9.25 5.55964 8.69036 5 8 5ZM13.25 6.25C13.25 4.73122 14.4812 3.5 16 3.5C17.5188 3.5 18.75 4.73122 18.75 6.25C18.75 7.76878 17.5188 9 16 9C14.4812 9 13.25 7.76878 13.25 6.25ZM16 5C15.3096 5 14.75 5.55964 14.75 6.25C14.75 6.94036 15.3096 7.5 16 7.5C16.6904 7.5 17.25 6.94036 17.25 6.25C17.25 5.55964 16.6904 5 16 5ZM4.25 13C4.25 11.7574 5.25736 10.75 6.5 10.75H9.5C10.7426 10.75 11.75 11.7574 11.75 13V18C11.75 18.4142 11.4142 18.75 11 18.75C10.5858 18.75 10.25 18.4142 10.25 18V13C10.25 12.5858 9.91421 12.25 9.5 12.25H6.5C6.08579 12.25 5.75 12.5858 5.75 13V18C5.75 18.4142 5.41421 18.75 5 18.75C4.58579 18.75 4.25 18.4142 4.25 18V13ZM14.5 10.75C13.2574 10.75 12.25 11.7574 12.25 13V18C12.25 18.4142 12.5858 18.75 13 18.75C13.4142 18.75 13.75 18.4142 13.75 18V13C13.75 12.5858 14.0858 12.25 14.5 12.25H17.5C17.9142 12.25 18.25 12.5858 18.25 13V18C18.25 18.4142 18.5858 18.75 19 18.75C19.4142 18.75 19.75 18.4142 19.75 18V13C19.75 11.7574 18.7426 10.75 17.5 10.75H14.5Z"
                  fill="currentColor"
                />
              </svg>
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
              <svg
                :class="(selected === 'Roles & Permissions' || page === 'rolesPermissions') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M12 2.75C10.2051 2.75 8.75 4.20507 8.75 6C8.75 7.79493 10.2051 9.25 12 9.25C13.7949 9.25 15.25 7.79493 15.25 6C15.25 4.20507 13.7949 2.75 12 2.75ZM10.25 6C10.25 5.0335 11.0335 4.25 12 4.25C12.9665 4.25 13.75 5.0335 13.75 6C13.75 6.9665 12.9665 7.75 12 7.75C11.0335 7.75 10.25 6.9665 10.25 6ZM5.75 13.25C5.75 11.8693 6.86929 10.75 8.25 10.75H15.75C17.1307 10.75 18.25 11.8693 18.25 13.25V16.75C18.25 18.1307 17.1307 19.25 15.75 19.25H8.25C6.86929 19.25 5.75 18.1307 5.75 16.75V13.25ZM8.25 12.25C7.69772 12.25 7.25 12.6977 7.25 13.25V16.75C7.25 17.3023 7.69772 17.75 8.25 17.75H15.75C16.3023 17.75 16.75 17.3023 16.75 16.75V13.25C16.75 12.6977 16.3023 12.25 15.75 12.25H8.25Z"
                  fill="currentColor"
                />
              </svg>
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
              <svg
                :class="(selected === 'General Setting' || page === 'generalSetting') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M10.0026 2.5C10.3757 2.5 10.6782 2.80252 10.6782 3.17567V4.16926C11.6144 4.32494 12.4775 4.69561 13.2219 5.23361L13.9252 4.53026C14.189 4.26648 14.6167 4.26648 14.8805 4.53026L16.4748 6.12457C16.7386 6.38835 16.7386 6.81603 16.4748 7.07981L15.7714 7.78316C16.3094 8.52758 16.6801 9.39061 16.8358 10.3269H17.8294C18.2025 10.3269 18.505 10.6294 18.505 11.0025V13.2579C18.505 13.6311 18.2025 13.9336 17.8294 13.9336H16.8358C16.6801 14.8698 16.3094 15.7328 15.7714 16.4773L16.4748 17.1806C16.7386 17.4444 16.7386 17.8721 16.4748 18.1359L14.8805 19.7302C14.6167 19.994 14.189 19.994 13.9252 19.7302L13.2219 19.0269C12.4775 19.5649 11.6144 19.9355 10.6782 20.0912V21.0848C10.6782 21.4579 10.3757 21.7604 10.0026 21.7604H7.74715C7.374 21.7604 7.07148 21.4579 7.07148 21.0848V20.0912C6.13526 19.9355 5.27223 19.5649 4.52781 19.0269L3.82446 19.7302C3.56068 19.994 3.133 19.994 2.86922 19.7302L1.27491 18.1359C1.01113 17.8721 1.01113 17.4444 1.27491 17.1806L1.97826 16.4773C1.44026 15.7328 1.06959 14.8698 0.91391 13.9336H-0.0796814C-0.45283 13.9336 -0.755346 13.6311 -0.755346 13.2579V11.0025C-0.755346 10.6294 -0.45283 10.3269 -0.0796814 10.3269H0.91391C1.06959 9.39061 1.44026 8.52758 1.97826 7.78316L1.27491 7.07981C1.01113 6.81603 1.01113 6.38835 1.27491 6.12457L2.86922 4.53026C3.133 4.26648 3.56068 4.26648 3.82446 4.53026L4.52781 5.23361C5.27223 4.69561 6.13526 4.32494 7.07148 4.16926V3.17567C7.07148 2.80252 7.374 2.5 7.74715 2.5H10.0026ZM8.87486 8.62252C7.63195 8.62252 6.62436 9.63011 6.62436 10.873C6.62436 12.1159 7.63195 13.1235 8.87486 13.1235C10.1178 13.1235 11.1253 12.1159 11.1253 10.873C11.1253 9.63011 10.1178 8.62252 8.87486 8.62252Z"
                  fill="currentColor"
                />
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">General Setting</span>
            </a>
          </li>
        </ul>
      </div>

      <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
          <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">MASTER</span>
        </h3>

        <ul class="mb-6 flex flex-col gap-4">
          <li>
            <a
              href="{{ route('admin.categories') }}"
              @click="selected = 'Categories'"
              class="menu-item group"
              :class="(selected === 'Categories' || page === 'categories') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Categories' || page === 'categories') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M4 5C4 3.89543 4.89543 3 6 3H10L12 5H18C19.1046 5 20 5.89543 20 7V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Categories</span>
            </a>
          </li>
          <li>
            <a
              href="{{ route('admin.features') }}"
              @click="selected = 'Features'"
              class="menu-item group"
              :class="(selected === 'Features' || page === 'features') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Features' || page === 'features') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M12 17.25L7.053 20.69L8.276 14.31L4 10.18L10.449 9.47L12 3.25L13.551 9.47L20 10.18L15.724 14.31L16.947 20.69L12 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Features</span>
            </a>
          </li>
          <li>
            <a
              href="{{ route('admin.cities') }}"
              @click="selected = 'Cities'"
              class="menu-item group"
              :class="(selected === 'Cities' || page === 'cities') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Cities' || page === 'cities') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Cities</span>
            </a>
          </li>
          <li>
            <a
              href="{{ route('admin.currencies') }}"
              @click="selected = 'Currencies'"
              class="menu-item group"
              :class="(selected === 'Currencies' || page === 'currencies') ? 'menu-item-active' : 'menu-item-inactive'"
            >
              <svg
                :class="(selected === 'Currencies' || page === 'currencies') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">Currencies</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</aside>
