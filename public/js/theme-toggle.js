(function () {
  const storageKey = 'barabd-theme';
  const root = document.documentElement;

  const getTheme = () => (root.getAttribute('data-bs-theme') || 'light');
  const saveTheme = (value) => {
    try {
      localStorage.setItem(storageKey, value);
    } catch (_) {
      /* ignore storage errors */
    }
  };

  const updateMetaThemeColor = (theme) => {
    const meta = document.querySelector('meta[name="theme-color"]');
    if (!meta) return;
    meta.setAttribute('content', theme === 'dark' ? '#0f172a' : '#ffffff');
  };

  const setTheme = (value, persist = true) => {
    const theme = value === 'dark' ? 'dark' : 'light';
    root.setAttribute('data-bs-theme', theme);
    root.setAttribute('data-theme', theme);
    if (persist) {
      saveTheme(theme);
    }
    updateMetaThemeColor(theme);
    refreshToggleState(theme);
  };

  const toggleTheme = () => {
    setTheme(getTheme() === 'dark' ? 'light' : 'dark');
  };

  const refreshToggleState = (theme) => {
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
      const icon = btn.querySelector('.theme-toggle-icon');
      const label = btn.querySelector('.theme-toggle-label');
      if (icon) {
        icon.className = 'theme-toggle-icon bi ' + (theme === 'dark' ? 'bi-sun-fill' : 'bi-moon-stars');
      }
      if (label) {
        label.textContent = theme === 'dark' ? 'Light' : 'Dark';
      }
      btn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
    });
  };

  const createToggleButton = () => {
    if (document.querySelector('[data-theme-toggle]')) {
      return;
    }

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.setAttribute('data-theme-toggle', '1');
    btn.className = 'theme-toggle-btn btn btn-outline-secondary btn-sm';
    btn.innerHTML = '<i class="theme-toggle-icon bi bi-moon-stars"></i><span class="theme-toggle-label d-none d-sm-inline">Dark</span>';
    btn.addEventListener('click', toggleTheme);

    const nav =
      document.querySelector('.app-header .navbar-nav.ms-auto') ||
      document.querySelector('.navbar-nav.ms-auto') ||
      document.querySelector('.navbar-nav');

    if (nav) {
      const wrapper = document.createElement('li');
      wrapper.className = 'nav-item d-flex align-items-center';
      wrapper.appendChild(btn);
      nav.appendChild(wrapper);
    } else {
      btn.classList.add('theme-toggle-floating');
      document.body.appendChild(btn);
    }
  };

  const listenToSystem = () => {
    const media = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
    if (!media) return;

    const handler = (event) => {
      try {
        const stored = localStorage.getItem(storageKey);
        if (stored === 'dark' || stored === 'light') {
          return;
        }
      } catch (_) {
        /* ignore */
      }
      setTheme(event.matches ? 'dark' : 'light', false);
    };

    if (media.addEventListener) {
      media.addEventListener('change', handler);
    } else if (media.addListener) {
      media.addListener(handler);
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    createToggleButton();
    setTheme(getTheme(), false);
    listenToSystem();
  });
})();
