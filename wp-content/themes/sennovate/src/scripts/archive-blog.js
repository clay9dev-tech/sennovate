document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.querySelector('.filter-toggle');
  const categoryList = document.querySelector('.custom-post-categories');
  if (!toggle || !categoryList) return;

  const mq = window.matchMedia('(max-width: 767px)'); // mobile breakpoint

  function setOpen(open) {
    categoryList.style.display = open ? 'block' : 'none';
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  // Default state: closed on mobile, open on desktop
  function applyDefault(e) {
    setOpen(!e.matches); // if mobile (matches) => false, else true
  }

  applyDefault(mq);
  // Update if the viewport crosses the breakpoint
  if (mq.addEventListener) mq.addEventListener('change', applyDefault);
  else mq.addListener(applyDefault); // older browsers

  // Toggle on click
  toggle.addEventListener('click', function () {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    setOpen(!isOpen);
  });
});
