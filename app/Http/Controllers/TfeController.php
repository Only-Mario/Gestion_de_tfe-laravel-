<?php

namespace App\Http\Controllers;
use App\Http\Requests\TfeRequest;
use App\Mail\RecuDepot;
use App\Services\Courriel;
use App\Models\Document;
use App\Models\Tfe;
use App\Models\TfeModification;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TfeController extends Controller
{
    //

    public function __construct(){
        $this->middleware('email.confirme')->only(['create', 'store', 'edit', 'update']);
    }


    public function index(Request $request){
        $filtres = $request->validate([
            'kw' => ['nullable', 'string', 'max:100'],
            'niveau' => ['nullable', 'integer'],
            'filiere' => ['nullable', 'integer'],
            'annee' => ['nullable', 'integer'],
            'tri' => ['nullable', Rule::in(array_keys(Tfe::TRIS))],
            'page' => ['nullable', 'integer'],
        ]);

        $tfes = Tfe::with(['niveau', 'filiere'])
            ->where('status', 1)
            ->filtrer($filtres)
            ->trier($filtres['tri'] ?? null)
            ->paginate(18)
            ->appends(array_filter($filtres));

        return view('welcome', [
            'tfes' => $tfes,
            'filtres' => $filtres,
            'niveaux' => Niveau::ordonnes()->with('filieres')->get(),
            'annees' => Tfe::where('status', 1)->distinct()->orderByDesc('annee_de_realisation')->pluck('annee_de_realisation'),
            'parNiveau' => Tfe::where('status', 1)->selectRaw('niveau_id, count(*) as total')->groupBy('niveau_id')->pluck('total', 'niveau_id'),
        ]);
    }


    /** Page publique d'un rapport (résumé). Le PDF, lui, demande une connexion. */
    public function rapport($id, $slug = null){
        $tfe = Tfe::with(['niveau', 'filiere'])->where('status', 1)->findOrFail($id);

        if (route('rapport', ['id' => $id, 'slug' => $slug]) !== $tfe->url_publique) {
            return redirect($tfe->url_publique, 301);
        }

        return view('tfe.rapport', compact('tfe'));
    }


    /** Étape 1 : niveau et filière. Étape 2 : formulaire, si aucun dépôt n'existe déjà pour ce niveau. */
    public function create(Request $request){
        $niveaux = Niveau::ordonnes()->with('filieresActives')->get();
        $deposes = Auth::user()->tfes()->with('filiere')->get()->keyBy('niveau_id');
        $filiere = null;

        if ($request->filled('filiere')) {
            $filiere = Filiere::with('niveau')->where('active', true)->whereNotNull('niveau_id')->find((int) $request->query('filiere'));
            if (!$filiere || ($request->filled('niveau') && (int) $request->query('niveau') !== (int) $filiere->niveau_id)) {
                return redirect()->route('tfe.create')->with('error', "Cette filière ne correspond pas au niveau choisi.");
            }
            if ($existant = Tfe::depotExistant(Auth::id(), $filiere->niveau_id)) {
                return redirect()->route('tfe.create')->with('error', $this->messageDejaDepose($existant));
            }
            if ($refus = $this->refusGrade($filiere)) {
                return redirect()->route('profil.edit')->with('error', $refus);
            }
        }

        return view('tfe.create', [
            'ordre' => optional(Auth::user()->niveau)->ordre ?? 0,
            'niveaux' => $niveaux,
            'deposes' => $deposes,
            'filiere' => $filiere,
            'annee_de_realisations' => Tfe::annee_de_realisations(),
        ]);
    }


    /** Le grade du profil doit permettre ce dépôt ; renvoie le message de refus, ou null. */
    private function refusGrade(Filiere $filiere){
        $user = Auth::user();
        $grade = $user->niveau;

        if (!$user->matricule) {
            return 'Renseignez votre numéro matricule avant de déposer un document : il figure sur le reçu.';
        }
        if (!$grade || $grade->ordre < $filiere->niveau->ordre) {
            return sprintf(
                'Votre profil indique le grade %s. Pour déposer un document de %s, choisissez d’abord votre filière de %s et votre année.',
                $grade ? $grade->nom : 'non renseigné', $filiere->niveau->nom, $filiere->niveau->nom
            );
        }
        if ((int) $grade->id === (int) $filiere->niveau_id && $user->entity !== $filiere->nom) {
            return "Votre profil indique la filière {$user->entity}. Modifiez-le si vous êtes en {$filiere->nom}.";
        }

        return null;
    }


    private function messageDejaDepose(Tfe $tfe){
        return sprintf(
            'Vous avez déjà un document de niveau %s, dans la filière %s.',
            $tfe->niveau ? $tfe->niveau->nom : '?',
            $tfe->filiere ? $tfe->filiere->libelle : $tfe->groupe_pedagogique
        );
    }


    public function store(TfeRequest $request){
        $filiere = Filiere::with('niveau')->findOrFail($request->input('filiere_id'));
        if ($existant = Tfe::depotExistant(Auth::id(), $filiere->niveau_id)) {
            return redirect()->route('tfe.create')->with('error', $this->messageDejaDepose($existant));
        }
        if ($refus = $this->refusGrade($filiere)) {
            return redirect()->route('profil.edit')->with('error', $refus);
        }

        $data = $this->donnees($request, $filiere);
        $path = $request->file('document')->storeAs('tfe_documents', Document::generateFileName($data['theme']));
        $existant = null;

        try {
            $tfe = DB::transaction(function () use ($data, $path, $filiere, &$existant) {
                // Verrou : deux envois simultanés ne peuvent pas créer deux dépôts pour un même niveau.
                User::whereKey(Auth::id())->lockForUpdate()->first();
                if ($existant = Tfe::depotExistant(Auth::id(), $filiere->niveau_id)) {
                    return null;
                }

                $document = Document::create(['name' => basename($path), 'path' => $path]);

                // Ces champs ne viennent jamais du formulaire.
                $tfe = new Tfe($data);
                $tfe->user_id = Auth::id();
                $tfe->document_id = $document->id;
                $tfe->niveau_id = $filiere->niveau_id;
                $tfe->filiere_id = $filiere->id;
                $tfe->status = 1; // publié d'office
                $tfe->save();

                return $tfe;
            });
        } catch (\Throwable $e) {
            Storage::delete($path);
            throw $e;
        }

        if ($existant) {
            Storage::delete($path);
            return redirect()->route('tfe.create')->with('error', $this->messageDejaDepose($existant));
        }

        $envoye = $this->envoyerRecu($tfe);

        return redirect()->route('profil')->with('success', 'Votre document a bien été déposé. ' . ($envoye
            ? 'Le reçu a été envoyé à ' . Courriel::masquer($tfe->user->email) . '.'
            : 'Le reçu n’a pas pu être envoyé par email : téléchargez-le ci-dessous.'))->with('recu', $tfe->id);
    }


    private function donnees(TfeRequest $request, Filiere $filiere){
        $data = Arr::except($request->validated(), ['document', 'motif', 'justificatif', 'filiere_id']);
        $data['resume'] = $data['resume'] ?? '';
        $data['groupe_pedagogique'] = $filiere->nom;

        return $data;
    }


    public function show($id){
        $tfe = Tfe::findOrFail($id);

        // Un tfe non validé n'est visible que par son auteur et les administrateurs.
        abort_unless((int) $tfe->status === 1 || $tfe->isOwnedOrAdministeredBy(Auth::user()), 403);

        $document = Document::findOrFail($tfe->document_id);
        abort_unless(Storage::exists($document->path), 404);

        return response()->file(Storage::path($document->path), ['Content-Type' => 'application/pdf', 'X-Robots-Tag' => 'noindex']);
    }


    public function edit($id){
        $tfe = Tfe::findOrFail($id);
        abort_unless($tfe->isEditableBy(Auth::user()), 403);

        $annee_de_realisations = Tfe::annee_de_realisations();
        $filieres = Filiere::where('niveau_id', $tfe->niveau_id)->orderBy('nom')->get();
        return view('tfe.edit', compact('annee_de_realisations', 'tfe', 'filieres'));
    }


    public function update(TfeRequest $request){
        // Droits vérifiés dans TfeRequest::authorize()
        $tfe = Tfe::findOrFail($request->input('tfe_id'));
        $byAuthor = Auth::user()->is_admin != 1;

        $filiere = Filiere::findOrFail($request->input('filiere_id'));
        $data = $this->donnees($request, $filiere);

        $document = Document::findOrFail($tfe->document_id);
        $oldPath = $document->path;
        $path = null;
        $justificatif = null;

        try {
            if ($request->hasFile('document')) {
                $path = $request->file('document')->storeAs('tfe_documents', Document::generateFileName($data['theme']));
            }
            if ($byAuthor) {
                // Hors du dossier public : réservé aux administrateurs
                $justificatif = $request->file('justificatif')->storeAs('justificatifs', Document::generateFileName('justificatif-' . $tfe->id));
            }

            DB::transaction(function () use ($request, $tfe, $data, $filiere, $document, $oldPath, $path, $justificatif, $byAuthor) {
                if ($byAuthor) {
                    TfeModification::create([
                        'tfe_id' => $tfe->id,
                        'user_id' => Auth::id(),
                        'motif' => $request->input('motif'),
                        'justificatif_path' => $justificatif,
                        'ancien_document_path' => $oldPath,
                        'anciennes_donnees' => $tfe->getAttributes(),
                    ]);
                    $tfe->status = 0;
                    $tfe->motif_rejet = null;
                }
                if ($path) {
                    $document->update(['name' => basename($path), 'path' => $path]);
                }
                $tfe->filiere_id = $filiere->id;
                $tfe->niveau_id = $filiere->niveau_id;
                $tfe->fill($data)->save();
            });
        } catch (\Throwable $e) {
            Storage::delete(array_filter([$path, $justificatif]));
            throw $e;
        }

        if ($path) {
            // Conservé si une modification y fait référence
            Document::deleteFileIfUnused($oldPath);
        }

        return redirect()->back()->with('success', $byAuthor
            ? 'Modification enregistrée : votre TFE est en attente de validation.'
            : 'Tfe bien modifié !');
    }


    public function justificatif($id){
        $modification = TfeModification::findOrFail($id);
        abort_unless(Storage::exists($modification->justificatif_path), 404);

        return response()->file(Storage::path($modification->justificatif_path), ['Content-Type' => 'application/pdf']);
    }


    public static function destroy($id,$call=false){
        $tfe = Tfe::findOrFail($id);

        // $call vaut true quand la suppression vient d'un contrôleur d'administration
        if ($call == false) {
            abort_unless($tfe->isDeletableBy(Auth::user()), 403);
        }

        $document = Document::find($tfe->document_id);

        $modifications = $tfe->modifications()->get();

        DB::transaction(function () use ($tfe, $document) {
            $tfe->modifications()->delete();
            $tfe->delete();
            if ($document) {
                $document->delete();
            }
        });

        if ($document) {
            Document::deleteFileIfUnused($document->path);
        }
        foreach ($modifications as $modification) {
            Storage::delete($modification->justificatif_path);
            Document::deleteFileIfUnused($modification->ancien_document_path);
        }

        //si la fonction n'a pas été appelée par une autre
        if($call==false){
             return redirect(route('welcome'))->with('success', 'Le tfe a été bien supprimé.');
        }
    }


    private function nomRecu(Tfe $tfe){
        return 'Recu_de_depot_' . Str::slug($tfe->user->matricule . ' ' . optional($tfe->niveau)->code, '_') . '.pdf';
    }


    /** Envoie le reçu à l'étudiant ; un échec ne bloque jamais le dépôt. */
    private function envoyerRecu(Tfe $tfe){
        try {
            $tfe->loadMissing(['user', 'niveau', 'filiere']);

            return Courriel::envoyer($tfe->user->email, new RecuDepot($tfe, $this->buildAttestationPdf($tfe), $this->nomRecu($tfe)));
        } catch (\Throwable $e) {
            Log::error("Reçu du document #{$tfe->id} non généré : " . $e->getMessage());

            return false;
        }
    }


    /** L'étudiant redemande son reçu par email. */
    public function envoyerRecuParEmail($id){
        $tfe = Tfe::with(['user', 'niveau', 'filiere'])->findOrFail($id);
        abort_unless($tfe->user && (int) $tfe->user_id === (int) Auth::id(), 403);

        return $this->envoyerRecu($tfe)
            ? redirect()->route('profil')->with('success', 'Reçu envoyé à ' . Courriel::masquer($tfe->user->email) . '.')
            : redirect()->route('profil')->with('error', 'Le reçu n’a pas pu être envoyé. Téléchargez-le plutôt.');
    }


    public function downloadAttestation($id){
        $tfe = Tfe::with(['user', 'niveau', 'filiere'])->findOrFail($id);

        abort_unless($tfe->isOwnedOrAdministeredBy(Auth::user()), 403);
        abort_unless($tfe->user, 404);

        return response($this->buildAttestationPdf($tfe), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $this->nomRecu($tfe) . '"',
        ]);
    }


    public function verifyAttestation($reference, $token){
        $tfe = null;
        $valid = hash_equals($this->attestationToken($reference), (string) $token);

        if ($valid && preg_match('/^INSTI-TFE-(\d{6})-\d{4}$/', $reference, $matches)) {
            $tfe = Tfe::with(['user', 'niveau', 'filiere'])->find((int) $matches[1]);
            $valid = $tfe !== null;
        } else {
            $valid = false;
        }

        return view('tfe.verify', ['valid' => $valid, 'tfe' => $tfe, 'reference' => $reference]);
    }


    private function attestationReference(Tfe $tfe){
        return sprintf('INSTI-TFE-%06d-%s', $tfe->id, $tfe->created_at->format('Y'));
    }


    private function attestationToken(string $reference){
        return substr(hash_hmac('sha256', $reference, config('app.key')), 0, 20);
    }


    private function buildQrDataUri(string $data){
        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'outputBase64' => true,
            'scale' => 4,
            'imageTransparent' => false,
        ]);

        return (new QRCode($options))->render($data);
    }


    private function buildAttestationPdf(Tfe $tfe){
        $tfe->loadMissing(['user', 'niveau', 'filiere']);

        $reference = $this->attestationReference($tfe);
        $token = $this->attestationToken($reference);
        $verifyUrl = rtrim(config('app.url'), '/') . route('tfe.verify', ['reference' => $reference, 'token' => $token], false);
        $qrDataUri = $this->buildQrDataUri($verifyUrl);
        $logo = public_path('images/logo_insti.png');
        $logoDataUri = is_file($logo) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo)) : null;

        $html = view('tfe.attestation', compact('tfe', 'reference', 'verifyUrl', 'qrDataUri', 'logoDataUri'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
