<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MessageInsti;
use App\Models\Journal;
use App\Services\Courriel;
use App\Models\Niveau;
use App\Models\Tfe;
use App\Models\User;
use Illuminate\Http\Request;

class TfeController extends Controller
{
    public function student(Request $request)
    {
        $type = $request->query('type') === 'admins' ? 'admins' : 'students';
        $q = $this->texte($request, 'q');
        $niveau = (int) $request->query('niveau');

        $query = $type === 'admins'
            ? User::where('is_admin', 1)
            : User::with('niveau')->withCount('tfes')->where(function ($sub) {
                $sub->where('is_admin', 0)->orWhereNull('is_admin');
            });

        if ($q !== '') {
            $like = '%' . addcslashes($q, '%_') . '%';
            $query->where(function ($sub) use ($like) {
                $sub->where('name', 'LIKE', $like)->orWhere('email', 'LIKE', $like)->orWhere('matricule', 'LIKE', $like);
            });
        }
        $doublons = $request->boolean('doublons');
        if ($doublons) {
            $query->enDouble();
        }
        if ($niveau && $type === 'students') {
            $query->where('niveau_id', $niveau);
        }

        $users = $query->orderBy('name')->paginate(20)->appends($request->only('type', 'q', 'niveau', 'doublons'));
        $niveaux = Niveau::ordonnes()->with('filieres')->get();

        return view('admin.store', compact('users', 'type', 'q', 'niveau', 'niveaux', 'doublons') + ['nombreDoublons' => User::enDouble()->count()]);
    }

    public function showDashboard(Request $request)
    {
        $parStatut = Tfe::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $counts = [0 => $parStatut[0] ?? 0, 1 => $parStatut[1] ?? 0, 2 => $parStatut[2] ?? 0];

        // Par défaut : les documents à valider s'il y en a, sinon les documents publiés.
        $status = $request->query('status');
        $status = in_array($status, ['0', '1', '2'], true) ? (int) $status : ($counts[0] > 0 ? 0 : 1);
        $q = $this->texte($request, 'q');
        $filtres = $this->filtres($request);

        return view('admin.dashboard', [
            'tfes' => $this->requete($status, $q, $filtres)->paginate(15)->appends(['status' => $status, 'q' => $q] + $filtres),
            'status' => $status,
            'q' => $q,
            'filtres' => $filtres,
            'counts' => $counts,
            'niveaux' => Niveau::ordonnes()->with('filieres')->get(),
            'parNiveau' => Tfe::selectRaw('niveau_id, count(*) as total')->groupBy('niveau_id')->pluck('total', 'niveau_id'),
            'parFiliere' => Tfe::selectRaw('groupe_pedagogique, count(*) as total')->groupBy('groupe_pedagogique')->orderByDesc('total')->pluck('total', 'groupe_pedagogique'),
            'stats' => [
                'students' => User::where(function ($sub) {
                    $sub->where('is_admin', 0)->orWhereNull('is_admin');
                })->count(),
                'total_tfe' => array_sum($counts),
                'mois' => Tfe::where('created_at', '>=', now()->startOfMonth())->count(),
            ],
        ]);
    }

    /** Export CSV (Excel) de la liste filtrée. */
    public function export(Request $request)
    {
        $query = $this->requete((int) $request->query('status', 1), $this->texte($request, 'q'), $this->filtres($request));
        Journal::noter('Export CSV', $query->count() . ' document(s)');

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['N°', 'Étudiant', 'Matricule', 'Niveau', 'Filière', 'Thème', 'Auteur(s)', 'Année', 'Maître de mémoire', 'Tuteur', 'Lieu de stage', 'Statut', 'Déposé le'], ';');
            foreach ($query->get() as $tfe) {
                fputcsv($out, array_map([$this, 'cellule'], [
                    $tfe->id, $tfe->user->name ?? '', $tfe->user->matricule ?? '', $tfe->niveau->nom ?? '',
                    $tfe->groupe_pedagogique, $tfe->theme, $tfe->auteurs, $tfe->annee_de_realisation,
                    $tfe->maitre_memoire, $tfe->tuteur_de_stage, $tfe->lieu_de_stage, $tfe->statut_libelle,
                    optional($tfe->created_at)->format('d/m/Y'),
                ]), ';');
            }
            fclose($out);
        }, 'documents-insti-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Essaie chaque messagerie et montre le résultat, sans identifiant. */
    public function testEmail(Request $request)
    {
        $adresse = $request->validate(['adresse' => ['required', 'string', 'email:rfc,filter', 'max:255']])['adresse'];

        // Noté avant l'envoi : un plantage laisserait quand même une trace
        $ligne = Journal::noter('Test d’envoi d’email', 'En cours vers ' . Courriel::masquer($adresse));
        $resultats = [];
        $ok = Courriel::envoyer($adresse, new MessageInsti('Test d’envoi', 'L’envoi d’emails fonctionne', ['Ce message confirme que la plateforme peut envoyer des emails.']), $resultats);

        $configure = 'relais Gmail ' . (config('mail.mailers.relais.url') ? 'activé' : 'non configuré') . ', ' . config('mail.mailers.smtp.host') . ':' . config('mail.mailers.smtp.port')
            . ', identifiants ' . (config('mail.mailers.smtp.username') && config('mail.mailers.smtp.password') ? 'présents' : 'absents')
            . ', expéditeur ' . (config('mail.from.address') ? 'présent' : 'absent');
        $detail = collect($resultats)->map(function ($r, $m) {
            return "$m : $r";
        })->implode(' | ');
        $ligne->update(['details' => ($ok ? 'Réussi' : 'Échec') . ' vers ' . Courriel::masquer($adresse) . " | $detail | config : $configure"]);

        return redirect()->route('journal')->with($ok ? 'success' : 'error', ($ok ? 'Message accepté par le serveur d’envoi. ' : 'Échec de l’envoi. ') . $detail);
    }

    public function journal()
    {
        return view('admin.journal', ['entrees' => Journal::orderByDesc('id')->paginate(30)]);
    }

    /** Neutralise les formules : une cellule commençant par = + - @ serait exécutée par Excel. */
    private function cellule($valeur)
    {
        $valeur = (string) $valeur;

        return preg_match('/^\s*[=+\-@]/', $valeur) ? "'" . $valeur : $valeur;
    }

    private function requete($status, $q, array $filtres)
    {
        $query = Tfe::with(['user', 'niveau', 'filiere', 'modifications'])
            ->where('status', $status)->filtrer($filtres)->orderByDate();

        if ($q !== '') {
            $like = '%' . addcslashes($q, '%_') . '%';
            $query->where(function ($sub) use ($like) {
                $sub->where('theme', 'LIKE', $like)->orWhere('auteurs', 'LIKE', $like)
                    ->orWhereHas('user', function ($u) use ($like) {
                        $u->where('name', 'LIKE', $like)->orWhere('matricule', 'LIKE', $like);
                    });
            });
        }

        return $query;
    }

    private function filtres(Request $request)
    {
        return ['niveau' => (int) $request->query('niveau') ?: null, 'filiere' => (int) $request->query('filiere') ?: null];
    }

    private function texte(Request $request, $cle)
    {
        $valeur = $request->query($cle, '');

        return is_string($valeur) ? mb_substr(trim($valeur), 0, 100) : '';
    }
}
