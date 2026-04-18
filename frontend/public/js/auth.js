function setVisibility(elementId, shouldShow, displayClass = 'block') {
  const element = document.getElementById(elementId);
  if (!element) {
    return;
  }

  if (shouldShow) {
    element.classList.remove('hidden');
    if (displayClass) {
      element.classList.add(displayClass);
    }
  } else {
    element.classList.add('hidden');
    if (displayClass) {
      element.classList.remove(displayClass);
    }
  }
}

function updateAuthUi() {
  const user = window.MART_API.getStoredUser();
  const authenticated = window.MART_API.isAuthenticated() && user;

  const guestEl = document.getElementById('auth-guest');
  const userEl = document.getElementById('auth-user');

  if (guestEl) {
    if (authenticated) {
      guestEl.classList.add('hidden');
      guestEl.classList.remove('md:flex');
    } else {
      guestEl.classList.add('hidden', 'md:flex');
    }
  }

  if (userEl) {
    if (authenticated) {
      userEl.classList.add('hidden', 'md:flex');
    } else {
      userEl.classList.add('hidden');
      userEl.classList.remove('md:flex');
    }
  }

  setVisibility('mobile-auth-guest', !authenticated);
  setVisibility('mobile-auth-user', authenticated);
  setVisibility('desktop-orders-link', authenticated, 'inline');
  setVisibility('mobile-orders-link', authenticated, 'block');

  const desktopName = document.getElementById('auth-user-name');
  const mobileName = document.getElementById('mobile-auth-user-name');

  if (desktopName) {
    desktopName.textContent = authenticated ? user.name : '';
  }

  if (mobileName) {
    mobileName.textContent = authenticated ? user.name : '';
  }
}

async function handleLogout(event) {
  event.preventDefault();

  try {
    if (window.MART_API.isAuthenticated()) {
      await window.MART_API.logout();
    }
  } catch (error) {
    // Ignore logout request failures and clear local auth state anyway.
  } finally {
    window.MART_API.clearAuth();
    updateAuthUi();
    if (typeof window.updateCartBadge === 'function') {
      window.updateCartBadge();
    }
    window.showToast('You have been logged out.');
    if (window.location.pathname === window.MART_CONFIG.routes.orders || window.location.pathname === window.MART_CONFIG.routes.checkout) {
      window.location.href = window.MART_CONFIG.routes.shop;
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  updateAuthUi();

  const desktopLogout = document.getElementById('logout-button');
  const mobileLogout = document.getElementById('mobile-logout-button');

  if (desktopLogout) {
    desktopLogout.addEventListener('click', handleLogout);
  }

  if (mobileLogout) {
    mobileLogout.addEventListener('click', handleLogout);
  }
});

window.updateAuthUi = updateAuthUi;
