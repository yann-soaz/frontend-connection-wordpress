document.addEventListener('DOMContentLoaded', function () {

  let subscrideLink = document.getElementById('ys-inscription');
  if (subscrideLink) {
    subscrideLink.addEventListener('click', function (e) {
      e.preventDefault();
      showSubscription();
    })
  }

  let connectLink = document.getElementById('ys-connection');
  if (connectLink) {
    connectLink.addEventListener('click', function (e) {
      e.preventDefault();
      showConnection();
    })
  }

  let formLogin = document.getElementById('login-front-form');
  if (formLogin) {
    formLogin.addEventListener('submit', function (e) {
      e.preventDefault();
      let datasToSend = getLoginFormValue(this, 'login');
      ys_ajax_call(datasToSend, function (json) {
        let status = 'alert';
        if (json.respont) {
          status = 'success';
          setTimeout(function () {
            window.location = ys_login_object.ys_redirect_url;
          }, 3000);
        }
        alert(formLogin, json.message, status);
      });
    })
  }
  let formSubscribe = document.getElementById('subscribe-front-form');
  if (formSubscribe) {
    formSubscribe.addEventListener('submit', function (e) {
      e.preventDefault();
      let datasToSend = getLoginFormValue(this, 'subscribe');
      ys_ajax_call(datasToSend, function (json) {
          let status = 'alert';
          if (json.respont) {
            status = 'success';
          }
          alert(formSubscribe, json.message, status);
      });
    })
  }
  
})


function showConnection () {
  document.getElementById('login-front-form').style.display = 'block';
  document.getElementById('subscribe-front-form').style.display = 'none';
}
function showSubscription () {
  document.getElementById('login-front-form').style.display = 'none';
  document.getElementById('subscribe-front-form').style.display = 'block';
}


/**
 * Récupère les données de formulaire des form login et subscribe
 * @param {HTMLElement} form 
 * @param {string} context 
 */
function getLoginFormValue (form, context) {
  let values = new FormData();
  if (context == 'subscribe') {
    values.append('user_pass_confirm', form.querySelector('.user_pass_confirm').value);
    values.append('action', 'yssubscribe');
    values.append('security', document.getElementById('ys-subscribe').value);
  } else {
    values.append('action', 'yslogin');
    values.append('security', document.getElementById('ys-login').value);
  }
  values.append('user_email', form.querySelector('.user_mail').value);
  values.append('user_password', form.querySelector('.user_pass').value);
  return values;
}

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
  const alert = '<div class="alert alert-'+status+' alert-dismissible fade show" role="alert">'
    + message
    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
      +'<span aria-hidden="true">&times;</span>'
    +'</button>'
  +'</div>';
  if (parent) {
    var oldAlert = parent.querySelector('.alert');
    if (oldAlert) {
      oldAlert.remove();
    }
    parent.innerHTML = alert+parent.innerHTML;
  }
}