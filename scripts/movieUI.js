class MovieUI {
  constructor(movieStateManager) {
    if (!movieStateManager) {
      console.error(
        "MovieUI: Initialization failed - movieStateManager is required"
      );
      return;
    }

    this.stateManager = movieStateManager;
    console.log(
      "MovieUI: Starting initialization for movie",
      this.stateManager.movieId
    );

    this.initializeButtons();
    this.loadInitialState();
    console.log("MovieUI: Initialization completed successfully");
  }

  initializeButtons() {
    const buttonIds = ["watching", "watched", "liked", "disliked"];
    console.log("MovieUI: Initializing buttons", buttonIds);

    buttonIds.forEach((id) => {
      const button = document.getElementById(`${id}Btn`);
      if (!button) {
        console.error("MovieUI: Button initialization failed", {
          buttonId: `${id}Btn`,
          reason: "Element not found",
        });
        return;
      }

      this[`${id}Btn`] = button;
      button.addEventListener("click", (e) => {
        e.preventDefault();
        console.log("MovieUI: Button clicked", { buttonId: id });

        const methodName = `toggle${id.charAt(0).toUpperCase() + id.slice(1)}`;
        const newState = this.stateManager[methodName]();
        this.updateButtonStates(newState);
      });
    });
  }

  loadInitialState() {
    const state = this.stateManager.getFromLocalStorage();
    this.updateButtonStates(state);
  }

  updateButtonStates(state) {
    console.log("MovieUI: Updating button states", state);

    // Define styles for each button type
    const buttonStyles = {
      watching: {
        active: [
          "bg-blue-100",
          "dark:bg-blue-900",
          "border-blue-500",
          "text-blue-700",
          "dark:text-blue-200",
        ],
        inactive: [
          "hover:bg-gray-100",
          "dark:hover:bg-gray-700",
          "text-gray-700",
          "dark:text-gray-200",
        ],
      },
      watched: {
        active: [
          "bg-blue-100",
          "dark:bg-blue-900",
          "border-blue-500",
          "text-blue-700",
          "dark:text-blue-200",
        ],
        inactive: [
          "hover:bg-gray-100",
          "dark:hover:bg-gray-700",
          "text-gray-700",
          "dark:text-gray-200",
        ],
      },
      liked: {
        active: [
          "bg-green-100",
          "dark:bg-green-900",
          "border-green-500",
          "text-green-700",
          "dark:text-green-200",
        ],
        inactive: [
          "hover:bg-gray-100",
          "dark:hover:bg-gray-700",
          "text-gray-700",
          "dark:text-gray-200",
        ],
      },
      disliked: {
        active: [
          "bg-red-100",
          "dark:bg-red-900",
          "border-red-500",
          "text-red-700",
          "dark:text-red-200",
        ],
        inactive: [
          "hover:bg-gray-100",
          "dark:hover:bg-gray-700",
          "text-gray-700",
          "dark:text-gray-200",
        ],
      },
    };

    Object.entries(state).forEach(([key, value]) => {
      const button = this[`${key}Btn`];
      if (!button) return;

      const styles = buttonStyles[key];
      if (!styles) return;

      // Remove all existing style classes
      const allClasses = [
        ...buttonStyles.watching.active,
        ...buttonStyles.watching.inactive,
        ...buttonStyles.watched.active,
        ...buttonStyles.watched.inactive,
        ...buttonStyles.liked.active,
        ...buttonStyles.liked.inactive,
        ...buttonStyles.disliked.active,
        ...buttonStyles.disliked.inactive,
      ];
      button.classList.remove(...allClasses);

      // Add new style classes based on state
      const classesToAdd = value ? styles.active : styles.inactive;
      classesToAdd.forEach((cls) => button.classList.add(cls));
    });
  }
}

// Export MovieUI to global scope
window.MovieUI = MovieUI;
