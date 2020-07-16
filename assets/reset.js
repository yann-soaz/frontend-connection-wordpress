document.addEventListener('DOMContentLoaded', function () {


  let formUser = document.getElementById('reset-front-form');
  if (formUser) {
    formUser.addEventListener('submit', function (e) {
      e.preventDefault();
      formUser.classList.add('loading');
      let datasToSend = new FormData(this);
      datasToSend.append('action', 'ysreset');
      ys_ajax_call(datasToSend, function (json) {
        let status = 'danger';
        if (json.respont) {
          status = 'success';
          setTimeout(function () {
            window.location = ys_login_object.ys_redirect_url;
          }, 2000)
        }
        alert(formUser, json.message, status);
        formUser.classList.remove('loading');
      });
    })
  }

})

/**
 * Envoi une requête sur l'url admin_ajax
 * @param {object} data 
 * @param {function} callback 
 */
function ys_ajax_call(data, callback) {
  fetch(ys_login_object.ys_ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    body: data
  }).then(response => response.json())
  .then(json => callback(json))
}

/**
 * Ajoute un message d'alerte sur "parent", efface le précédent message s'il existe
 * @param {HTMLElement} parent 
 * @param {string} message 
 * @param {string} status 
 */
function alert (parent, message, status) {
  const alert = constructAlert(status, message);
  if (parent) {
    var oldAlert = parent.querySelector('.alert');
    if (oldAlert) {
      oldAlert.remove();
    }
    parent.prepend(alert);
  }
}

/**
 * construit le html de l'alerte
 * @param {string} status class de l'élément
 * @param {string} message message à afficher
 */
function constructAlert (status, message) {
  let alert = document.createElement('div');
  alert.className = 'alert alert-'+status;
  alert.innerHTML = message;
  var btn = document.createElement('span');
  btn.innerHTML = '&times;';
  btn.addEventListener('click', function () {
    this.parentElement.remove();
  })
  alert.append(btn);
  return alert;
}