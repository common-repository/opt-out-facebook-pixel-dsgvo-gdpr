// If cookie is removed, check the local storage. If this is true, create the cookie.
if (typeof fboo_data !== 'undefined' && document.cookie.indexOf(fboo_data.disable_string + '=true') == -1 && fboo_localstorage('get', fboo_data.disable_string) == 'true') {
  fboo_create_cookie();
}

function fboo_remove_cookie() {
  document.cookie = fboo_data.disable_string + '=; expires=Thu, 01 Jan 1970 00:00:01 UTC; path=/';
  window[fboo_data.disable_string] = false;
  fboo_localstorage('set', fboo_data.disable_string, false);
}

function fboo_create_cookie() {
  document.cookie = fboo_data.disable_string + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
  window[fboo_data.disable_string] = true;
  fboo_localstorage('set', fboo_data.disable_string, true);
}

function fboo_localstorage(method, name, value) {
  try {
    if ('localStorage' in window && window['localStorage'] !== null) {
      if (method == 'set' && typeof name !== 'undefined' && typeof value !== 'undefined') {
        localStorage.setItem(name, value);
      }

      if (method == 'get' && typeof name !== 'undefined') {
        return localStorage.getItem(name);
      }
    }
  } catch (e) {
    return false;
  }
}

function fboo_handle_optout() {
  var link = document.getElementById('fboo-link');

  if (document.cookie.indexOf(fboo_data.disable_string + '=true') > -1) {
    fboo_remove_cookie();

    if (fboo_data.hasOwnProperty('popup_activate')) {
      alert(fboo_data.popup_activate);
    }

    link.innerHTML = fboo_data.link_deactivate;
  }
  else {
    fboo_create_cookie();

    if (fboo_data.hasOwnProperty('popup_deactivate')) {
      alert(fboo_data.popup_deactivate);
    }

    link.innerHTML = fboo_data.link_activate;
  }

  link.className = link.className.replace(/\b-deactivate\b/, '-activate');

  if (fboo_data.hasOwnProperty('force_reload') && fboo_data.force_reload == true) {
    location.reload();
  }
}