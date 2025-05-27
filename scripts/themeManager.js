class ThemeManager {
  constructor() {
    this.themeToggle = document.getElementById("themeToggle");
    this.lightIcon = document.getElementById("lightIcon");
    this.darkIcon = document.getElementById("darkIcon");
    this.htmlElement = document.documentElement;

    this.theme = localStorage.getItem("theme") || "light";
    console.log("ThemeManager: Initializing with theme", this.theme);
    this.initializeTheme();
    this.bindEvents();
  }

  initializeTheme() {
    // Apply saved theme or default based on system preference
    if (
      this.theme === "dark" ||
      (!this.theme && window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
      this.htmlElement.classList.add("dark");
      this.lightIcon.classList.remove("hidden");
      this.darkIcon.classList.add("hidden");
      console.log("ThemeManager: Dark theme applied");
    } else {
      this.htmlElement.classList.remove("dark");
      this.lightIcon.classList.add("hidden");
      this.darkIcon.classList.remove("hidden");
      console.log("ThemeManager: Light theme applied");
    }
  }

  bindEvents() {
    this.themeToggle.addEventListener("click", () => this.toggleTheme());
  }

  toggleTheme() {
    this.htmlElement.classList.toggle("dark");
    const isDark = this.htmlElement.classList.contains("dark");

    // Update theme icons visibility
    if (isDark) {
      this.lightIcon.classList.remove("hidden");
      this.darkIcon.classList.add("hidden");
    } else {
      this.lightIcon.classList.add("hidden");
      this.darkIcon.classList.remove("hidden");
    }

    // Save theme preference to localStorage
    localStorage.setItem("theme", isDark ? "dark" : "light");
    console.log("ThemeManager: Theme toggled to", isDark ? "dark" : "light");
  }
}

// Export ThemeManager to global scope
window.ThemeManager = ThemeManager;
