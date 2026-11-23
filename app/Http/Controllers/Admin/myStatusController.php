<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TfeController as tfeController;
use App\Mail\MessageInsti;
use App\Models\Document;
use App\Services\Courriel;
use App\Models\Journal;
use App\Models\Tfe;
use App\Models\TfeModification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class myStatusController extends Controller
{
    /**
     * id     : identifiant du tfe, -1 pour tous ceux en attente, 0 avec le statut -2
     * status : 0 en attente, 1 publié, 2 rejeté, -2 supprimer les rejetés, -3 supprimer ce tfe
     */
    public function index(Request $request, $id, $status)
    {
        abort_unless(in_array($status, ['0', '1', '2', '-2', '-3'], true), 404);
        $id = (int) $id;
        $status = (int) $status;
        $motif = $request->validate(['motif_rejet' => ['nullable', 'string', 'max:1000']])['motif_rejet'] ?? null;

        if ($status === -2) {
            abort_unless($id === 0, 404);
            $ids = Tfe::where('status', 2)->pluck('id');
            foreach ($ids as $one) {
                tfeController::destroy($one, true);
            }
            Journal::noter('Documents rejetés supprimés', $ids->count() . ' document(s)');
            $message = $ids->count() . ' document(s) supprimé(s).';
        } elseif ($status === -3) {
            $tfe = Tfe::findOrFail($id);
            tfeController::destroy($tfe->id, true);
            Journal::noter('Document supprimé', "#{$tfe->id} {$tfe->theme}");
            $message = 'Document supprimé.';
        } elseif ($id === -1) {
            abort_unless(in_array($status, [1, 2], true), 404);
            $nombre = Tfe::where('status', 0)->update(['status' => $status, 'motif_rejet' => $status === 2 ? $motif : null]);
            Journal::noter($status === 1 ? 'Documents validés en masse' : 'Documents rejetés en masse', "$nombre document(s)");
            $message = "$nombre document(s) traité(s).";
        } else {
            $tfe = Tfe::findOrFail($id);
            $tfe->status = $status;
            $tfe->motif_rejet = $status === 2 ? $motif : null;
            $tfe->save();
            Journal::noter('Document : ' . $tfe->statut_libelle, "#{$tfe->id} {$tfe->theme}" . ($motif ? " - $motif" : ''));
            $message = 'Document : ' . mb_strtolower($tfe->statut_libelle) . '.';
            if ($tfe->user && in_array($status, [1, 2], true)) {
                Courriel::envoyer($tfe->user->email, new MessageInsti(
                    $status === 1 ? 'Votre document est publié' : 'Votre document a été rejeté',
                    $status === 1 ? 'Document publié' : 'Document rejeté',
                    array_values(array_filter([
                        "Bonjour {$tfe->user->name},",
                        "Votre document « {$tfe->theme} » a été " . ($status === 1 ? 'validé et publié.' : 'rejeté.'),
                        $motif ? "Motif : $motif" : null,
                    ])),
                    null,
                    ['Voir mes documents', rtrim(config('app.url'), '/') . route('profil', [], false)]
                ));
            }
        }

        return redirect()->back(302, [], route('dashboard'))->with('success', $message);
    }

    /** Rétablit la version d'un document d'avant une modification de l'étudiant. */
    public function restaurer($id)
    {
        $modification = TfeModification::findOrFail($id);
        $tfe = Tfe::findOrFail($modification->tfe_id);
        $avant = $modification->anciennes_donnees;

        if (!Storage::exists($modification->ancien_document_path)) {
            return redirect()->back(302, [], route('dashboard'))->with('error', 'Ancien fichier introuvable.');
        }

        DB::transaction(function () use ($tfe, $modification, $avant) {
            // Le fichier remplacé reste sur le disque.
            Document::findOrFail($tfe->document_id)->update([
                'name' => basename($modification->ancien_document_path),
                'path' => $modification->ancien_document_path,
            ]);
            $tfe->fill(Arr::only($avant, $tfe->getFillable()));
            $tfe->filiere_id = $avant['filiere_id'] ?? $tfe->filiere_id;
            $tfe->niveau_id = $avant['niveau_id'] ?? $tfe->niveau_id;
            $tfe->status = $avant['status'];
            $tfe->motif_rejet = null;
            $tfe->save();
        });
        Journal::noter('Version précédente restaurée', "#{$tfe->id} {$tfe->theme}");

        return redirect()->back(302, [], route('dashboard'))->with('success', 'Version précédente restaurée.');
    }
}
