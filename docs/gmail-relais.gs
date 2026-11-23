// Script Google Apps Script : envoie les emails de la plateforme depuis VOTRE compte Gmail, par HTTPS.
// Propriété de script à créer : CLE = la valeur de GMAIL_RELAIS_CLE (fichier .env). Ne jamais écrire la clé ici.

function doPost(e) {
  try {
    var d = JSON.parse(e.postData.contents);
    var cle = PropertiesService.getScriptProperties().getProperty('CLE');
    if (!cle || cle.length < 32 || d.cle !== cle) return repondre({ ok: false, erreur: 'clé refusée' });

    var pieces = (d.pieces || []).map(function (p) {
      return Utilities.newBlob(Utilities.base64Decode(p.contenu), p.type, p.nom);
    });
    MailApp.sendEmail(d.to, d.subject, d.text || ' ', { htmlBody: d.html, name: d.nom, attachments: pieces });
    return repondre({ ok: true });
  } catch (err) {
    return repondre({ ok: false, erreur: String(err.message || err) });
  }
}

function repondre(objet) {
  return ContentService.createTextOutput(JSON.stringify(objet)).setMimeType(ContentService.MimeType.JSON);
}

// À lancer une fois dans l'éditeur pour accorder l'autorisation d'envoi (n'envoie rien).
function autoriser() {
  Logger.log('Envois restants aujourd\'hui : ' + MailApp.getRemainingDailyQuota());
}
