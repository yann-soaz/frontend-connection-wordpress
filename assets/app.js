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

  let returnConnectLink = document.getElementById('ys-return-connect');
  if (returnConnectLink) {
    returnConnectLink.addEventListener('click', function (e) {
      e.preventDefault();
      showConnection();
    })
  }

  let forgetLink = document.getElementById('ys-forgot-pass');
  if (forgetLink) {
    forgetLink.addEventListener('click', function (e) {
      e.preventDefault();
      showForget();
    })
  }

  let formLogin = document.getElementById('login-front-form');
  if (formLogin) {
    formLogin.addEventListener('submit', function (e) {
      e.preventDefault();
      formLogin.classList.add('loading');
      let datasToSend = getLoginFormValue(this, 'login');
      ys_ajax_call(datasToSend, function (json) {
        let status = 'danger';
        if (json.respont) {
          status = 'success';
          setTimeout(function () {
            window.location = ys_login_object.ys_redirect_url;
          }, 3000);
        }
        alert(formLogin, json.message, status);
        formLogin.classList.remove('loading');
      });
    })
  }
  let formSubscribe = document.getElementById('subscribe-front-form');
  if (formSubscribe) {
    formSubscribe.addEventListener('submit', function (e) {
      e.preventDefault();
      formSubscribe.classList.add('loading');
      let datasToSend = getLoginFormValue(this, 'subscribe');
      ys_ajax_call(datasToSend, function (json) {
          let status = 'danger';
          if (json.respont) {
            status = 'success';
          }
          alert(formSubscribe, json.message, status);
          formSubscribe.classList.remove('loading');
        });
    })
  }
  let formForgot = document.getElementById('forget-front-form');
  if (formForgot) {
    formForgot.addEventListener('submit', function (e) {
      e.preventDefault();
      formForgot.classList.add('loading');
      let datasToSend = getLoginFormValue(this, 'forget');
      ys_ajax_call(datasToSend, function (json) {
          let status = 'danger';
          if (json.respont) {
            status = 'success';
          }
          alert(formForgot, json.message, status);
          formForgot.classList.remove('loading');
        });
    })
  }
  
})


function showForget () {
  let loginForm = document.getElementById('login-front-form');
  if (loginForm) {
    loginForm.style.display = 'none';
  }
  let subscribeForm = document.getElementById('subscribe-front-form');
  if (subscribeForm) {
    subscribeForm.style.display = 'none';
  }
  let forgotForm = document.getElementById('forget-front-form');
  if (forgotForm) {
    forgotForm.style.display = 'block';
  }
}
function showConnection () {
  let loginForm = document.getElementById('login-front-form');
  if (loginForm) {
    loginForm.style.display = 'block';
  }
  let subscribeForm = document.getElementById('subscribe-front-form');
  if (subscribeForm) {
    subscribeForm.style.display = 'none';
  }
  let forgotForm = document.getElementById('forget-front-form');
  if (forgotForm) {
    forgotForm.style.display = 'none';
  }
}
function showSubscription () {
  let loginForm = document.getElementById('login-front-form');
  if (loginForm) {
    loginForm.style.display = 'none';
  }
  let subscribeForm = document.getElementById('subscribe-front-form');
  if (subscribeForm) {
    subscribeForm.style.display = 'block';
  }
  let forgotForm = document.getElementById('forget-front-form');
  if (forgotForm) {
    forgotForm.style.display = 'none';
  }
}


/**
 * Récupère les données de formulaire des form login et subscribe
 * @param {HTMLElement} form 
 * @param {string} context 
 */
function getLoginFormValue (form, context) {
  let values = new FormData();
  switch (context) {
    case 'subscribe':
      values.append('user_pass_confirm', form.querySelector('.user_pass_confirm').value);
      values.append('action', 'yssubscribe');
      values.append('security', document.getElementById('ys-subscribe').value);
    break;
    case 'forget' :
      values.append('action', 'yspassword');
      values.append('security', document.getElementById('ys-forget').value);
    break;
    default:
      values.append('action', 'yslogin');
      values.append('security', document.getElementById('ys-login').value);
    break;
  }
  if (context == 'subscribe') {
  } else {
  }
  values.append('user_email', form.querySelector('.user_mail').value);
  if (context != 'forget') {
    values.append('user_password', form.querySelector('.user_pass').value);
  }
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