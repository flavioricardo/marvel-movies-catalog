class MovieStateManager {
  constructor(movieId) {
    if (!movieId) {
      console.error(
        "MovieStateManager: Initialization failed - movieId is required"
      );
      return;
    }

    this.movieId = movieId;
    this.storageKey = `movie_${movieId}_state`;
    console.log("MovieStateManager: Initialized", {
      movieId: this.movieId,
      storageKey: this.storageKey,
    });
  }

  getInitialState() {
    return {
      watching: false,
      watched: false,
      liked: false,
      disliked: false,
    };
  }

  saveToLocalStorage(state) {
    try {
      const currentState = this.getFromLocalStorage();
      const newState = { ...currentState, ...state };
      localStorage.setItem(this.storageKey, JSON.stringify(newState));
      console.log("MovieStateManager: State saved successfully", {
        movieId: this.movieId,
        previousState: currentState,
        newState: newState,
        changes: state,
      });
      return newState;
    } catch (error) {
      console.error("MovieStateManager: Failed to save state", {
        movieId: this.movieId,
        error: error.message,
      });
      return this.getInitialState();
    }
  }

  getFromLocalStorage() {
    try {
      const state = localStorage.getItem(this.storageKey);
      if (!state) return this.getInitialState();

      const parsedState = JSON.parse(state);
      console.log("MovieStateManager: State retrieved", parsedState);
      return parsedState;
    } catch (error) {
      console.error("MovieStateManager: Error retrieving state", error);
      return this.getInitialState();
    }
  }

  toggleState(key, oppositeKey) {
    console.log("MovieStateManager: Toggling state", {
      movieId: this.movieId,
      key: key,
      oppositeKey: oppositeKey,
    });

    const state = this.getFromLocalStorage();
    const newState = {
      [key]: !state[key],
    };

    // If activating a state, deactivate its opposite
    if (newState[key] && oppositeKey) {
      newState[oppositeKey] = false;
      console.log("MovieStateManager: Disabling opposite state", {
        oppositeKey: oppositeKey,
      });
    }

    return this.saveToLocalStorage(newState);
  }

  // State toggle methods using the generic toggleState
  toggleWatching() {
    return this.toggleState("watching", "watched");
  }

  toggleWatched() {
    return this.toggleState("watched", "watching");
  }

  toggleLiked() {
    return this.toggleState("liked", "disliked");
  }

  toggleDisliked() {
    return this.toggleState("disliked", "liked");
  }
}

// Make class available globally
window.MovieStateManager = MovieStateManager;
