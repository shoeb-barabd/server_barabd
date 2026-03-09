<script>
  (() => {
    const storageKey = 'barabd-theme';
    let savedTheme = null;
    try {
      savedTheme = localStorage.getItem(storageKey);
    } catch (_) {
      savedTheme = null;
    }

    const defaultTheme = 'dark';
    const theme = savedTheme === 'dark' || savedTheme === 'light'
      ? savedTheme
      : defaultTheme;

    const root = document.documentElement;
    root.setAttribute('data-bs-theme', theme);
    root.setAttribute('data-theme', theme);
  })();
</script>
