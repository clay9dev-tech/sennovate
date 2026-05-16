/**
 * Sennovate Mobile Menu Enhancement
 * Fixes mobile navigation below 1024px
 */
(function () {
  var MOBILE_BREAKPOINT = 1024;

  function isMobile() {
    return window.innerWidth <= MOBILE_BREAKPOINT;
  }

  var menus = document.querySelector('.menus');
  var menuToggle = document.querySelector('.main-menu-toggle');

  if (menuToggle && menus) {
    menuToggle.addEventListener('click', function () {
      requestAnimationFrame(function () {
        var isOpen = document.body.classList.contains('nav-open');
        menus.classList.toggle('is-open', isOpen);
        if (!isOpen) {
          document.querySelectorAll('.sub-menu.active').forEach(function (sm) {
            sm.classList.remove('active');
          });
          document.querySelectorAll('.sub-menu-toggle[aria-expanded="true"]').forEach(function (t) {
            t.setAttribute('aria-expanded', 'false');
          });
        }
      });
    });
  }

  document.querySelectorAll('#main-menu .menu-item-has-children > .menu-item-link').forEach(function (link) {
    link.addEventListener('click', function (e) {
      if (!isMobile()) return;
      e.preventDefault();
      var menuItem = link.closest('.menu-item-has-children');
      var subMenu = menuItem ? menuItem.querySelector('.sub-menu') : null;
      var toggle = menuItem ? menuItem.querySelector('.sub-menu-toggle') : null;
      if (!subMenu) return;
      var isNowOpen = !subMenu.classList.contains('active');
      var parent = menuItem.parentElement;
      if (parent) {
        parent.querySelectorAll('.menu-item-has-children > .sub-menu.active').forEach(function (sm) {
          if (sm !== subMenu) {
            sm.classList.remove('active');
            var siblingToggle = sm.parentElement.querySelector('.sub-menu-toggle');
            if (siblingToggle) siblingToggle.setAttribute('aria-expanded', 'false');
          }
        });
      }
      subMenu.classList.toggle('active', isNowOpen);
      if (toggle) toggle.setAttribute('aria-expanded', isNowOpen ? 'true' : 'false');
    });
  });

  document.querySelectorAll('.sub-menu-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      var menuItem = toggle.closest('.menu-item-has-children');
      var subMenu = menuItem ? menuItem.querySelector('.sub-menu') : null;
      if (!subMenu) return;
      var isNowOpen = !subMenu.classList.contains('active');
      subMenu.classList.toggle('active', isNowOpen);
      toggle.setAttribute('aria-expanded', isNowOpen ? 'true' : 'false');
    });
  });

  document.addEventListener('click', function (e) {
    if (!e.target.closest('header') && document.body.classList.contains('nav-open')) {
      document.body.classList.remove('nav-open');
      if (menus) menus.classList.remove('is-open');
      document.querySelectorAll('.sub-menu.active').forEach(function (sm) {
        sm.classList.remove('active');
      });
    }
  });

  document.querySelectorAll('#main-menu .menu-item:not(.menu-item-has-children) a').forEach(function (link) {
    link.addEventListener('click', function () {
      if (document.body.classList.contains('nav-open')) {
        document.body.classList.remove('nav-open');
        if (menus) menus.classList.remove('is-open');
      }
    });
  });

  window.addEventListener('resize', function () {
    if (!isMobile()) {
      document.body.classList.remove('nav-open');
      if (menus) menus.classList.remove('is-open');
      document.querySelectorAll('.sub-menu.active').forEach(function (sm) {
        sm.classList.remove('active');
      });
    }
  });

})();