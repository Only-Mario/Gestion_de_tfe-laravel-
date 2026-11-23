(function () {
  'use strict';

  // Navigation mobile
  var body = document.body;
  var toggle = document.querySelector('[data-nav-toggle]');
  function nav(open) {
    body.classList.toggle('nav-open', open);
    if (toggle) toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  document.addEventListener('click', function (e) {
    var t = e.target;
    if (t.closest('[data-nav-toggle]')) return nav(!body.classList.contains('nav-open'));
    if (t.closest('[data-nav-close]')) return nav(false);

    // Afficher ou masquer un mot de passe
    var voir = t.closest('[data-voir]');
    if (voir) {
      var champ = document.getElementById(voir.getAttribute('aria-controls'));
      var visible = champ.type === 'password';
      champ.type = visible ? 'text' : 'password';
      var libelle = visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe';
      voir.setAttribute('aria-pressed', visible ? 'true' : 'false');
      voir.setAttribute('aria-label', libelle);
      voir.title = libelle;
      voir.querySelector('i').className = visible ? 'fas fa-eye-slash' : 'fas fa-eye';
      return champ.focus();
    }

    // Ouverture d'une boîte de dialogue ; data-fill="{champ: valeur}" remplit son formulaire
    var opener = t.closest('[data-dialog]');
    if (opener) {
      var dialog = document.getElementById(opener.dataset.dialog);
      var form = dialog.querySelector('form');
      if (opener.dataset.action) form.action = opener.dataset.action;
      if (opener.dataset.fill) {
        var values = JSON.parse(opener.dataset.fill);
        Object.keys(values).forEach(function (name) {
          var input = form.querySelector('[name="' + name + '"]');
          if (!input) return;
          input.value = values[name] === null ? '' : values[name];
          input.dispatchEvent(new Event('change', { bubbles: true }));
        });
      }
      return dialog.showModal();
    }
    if (t.closest('[data-close]')) return t.closest('dialog').close();
    if (t.tagName === 'DIALOG') t.close();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') nav(false);
  });

  document.addEventListener('submit', function (e) {
    var message = e.target.dataset.confirm;
    if (message && !window.confirm(message)) return e.preventDefault();
    // Un mot de passe affiché est masqué avant l'envoi (le navigateur ne le retient pas comme du texte)
    e.target.querySelectorAll('.secret input').forEach(function (i) { i.type = 'password'; });
    // Évite un double envoi
    if (e.target.method === 'post') {
      var button = e.target.querySelector('[type=submit]');
      if (button) setTimeout(function () { button.disabled = true; }, 0);
    }
  });
  window.addEventListener('pageshow', function () {
    document.querySelectorAll('[type=submit]:disabled').forEach(function (b) { b.disabled = false; });
  });

  // Filtres appliqués dès qu'on change une liste
  document.querySelectorAll('[data-autosubmit] select').forEach(function (select) {
    select.addEventListener('change', function () { select.form.submit(); });
  });

  // Filières et années suivent le niveau choisi ; sans choix de niveau, il vient de la filière
  document.querySelectorAll('[data-niveaux]').forEach(function (form) {
    var filiere = form.querySelector('[data-filiere]');
    var annee = form.querySelector('[data-annee]');
    var groups = new Map();
    [filiere, annee].forEach(function (s) {
      if (s) groups.set(s, Array.prototype.slice.call(s.querySelectorAll('optgroup')));
    });
    function filter(select, niveau) {
      if (!select) return;
      var value = select.value;
      groups.get(select).forEach(function (g) {
        if (!niveau || g.dataset.niveau === niveau) select.appendChild(g);
        else g.remove();
      });
      select.value = value;
      if (select.selectedIndex < 0) select.selectedIndex = 0;
    }
    function sync() {
      var choix = form.querySelector('[name=niveau]:checked');
      var niveau = choix ? choix.value : '';
      if (choix) filter(filiere, niveau);
      var option = filiere && filiere.selectedOptions[0];
      var groupe = option && option.closest('optgroup');
      filter(annee, niveau || (groupe ? groupe.dataset.niveau : ''));
    }
    form.addEventListener('change', sync);
    sync();
  });

  // Une boîte contenant des erreurs de validation se rouvre
  var failed = document.querySelector('dialog[data-open]');
  if (failed) failed.showModal();
})();
