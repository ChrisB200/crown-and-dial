<html>

<head>
  @vite('resources/css/app.css')
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
    rel="stylesheet">
  @yield('html-head')
</head>

<body>
  @yield('content')
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const html = document.documentElement;

      // Load saved theme
      const saved = localStorage.getItem("theme");
      if (saved === "dark") html.classList.add("dark");

      // Find ALL theme toggle components
      const toggles = document.querySelectorAll("[data-theme-toggle]");

      toggles.forEach(toggle => {
        const moon = toggle.querySelector("[data-moon]");
        const sun = toggle.querySelector("[data-sun]");

        // Initial icon state
        const isDark = html.classList.contains("dark");
        moon.classList.toggle("hidden", isDark);
        sun.classList.toggle("hidden", !isDark);

        toggle.addEventListener("click", () => {
          const nowDark = html.classList.toggle("dark");

          // Switch icons
          moon.classList.toggle("hidden", nowDark);
          sun.classList.toggle("hidden", !nowDark);

          // Save preference
          localStorage.setItem("theme", nowDark ? "dark" : "light");
        });
      });
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const html = document.documentElement;

      // Load saved size
      const savedSize = localStorage.getItem("fontSize");
      if (savedSize) {
        html.style.fontSize = savedSize + "px";
      }

      // Add listeners to all font size buttons
      document.querySelectorAll(".font-size-btn").forEach(btn => {
        btn.addEventListener("click", () => {
          const size = btn.dataset.size;
          html.style.fontSize = size + "px";

          // Save preference
          localStorage.setItem("fontSize", size);
        });
      });
    });
  </script>
</body>

</html>
