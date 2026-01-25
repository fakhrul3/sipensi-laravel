/* ============================================
   ADMIN LAYOUT JS
   ============================================ */

(function() {
  'use strict';

  // Sidebar Toggle (Mobile)
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.admin-sidebar');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('active');
    });

    // Close sidebar when clicking outside (mobile)
    document.addEventListener('click', function(e) {
      if (window.innerWidth <= 768) {
        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
          sidebar.classList.remove('active');
        }
      }
    });
  }

  // Close sidebar on window resize
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768 && sidebar) {
      sidebar.classList.remove('active');
    }
  });

  // Active menu item highlight
  const activeMenuLink = document.querySelector('.admin-menu-link.active');
  if (activeMenuLink) {
    activeMenuLink.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  // Dropdown menu toggle
  const dropdownLinks = document.querySelectorAll('.admin-menu-link--dropdown');
  dropdownLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const menuItem = this.closest('.admin-menu-item--has-dropdown');
      const isActive = menuItem.classList.contains('active');
      
      // Close all other dropdowns
      document.querySelectorAll('.admin-menu-item--has-dropdown').forEach(function(item) {
        if (item !== menuItem) {
          item.classList.remove('active');
          const itemLink = item.querySelector('.admin-menu-link--dropdown');
          if (itemLink) {
            itemLink.classList.remove('active');
          }
        }
      });
      
      // Toggle current dropdown
      if (isActive) {
        menuItem.classList.remove('active');
        this.classList.remove('active');
      } else {
        menuItem.classList.add('active');
        this.classList.add('active');
      }
    });
  });

  // Auto-expand dropdown if submenu link is active
  const activeSubmenuLink = document.querySelector('.admin-submenu-link.active');
  if (activeSubmenuLink) {
    const dropdownItem = activeSubmenuLink.closest('.admin-menu-item--has-dropdown');
    if (dropdownItem) {
      dropdownItem.classList.add('active');
      const dropdownLink = dropdownItem.querySelector('.admin-menu-link--dropdown');
      if (dropdownLink) {
        dropdownLink.classList.add('active');
      }
    }
  }

})();
