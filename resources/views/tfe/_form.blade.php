{{-- Champs communs au dépôt et à la modification --}}
<section class="card"><h2>Le document</h2>
    <div class="fields">
        <x-field name="theme" label="Thème" :value="$tfe->theme ?? null" full required maxlength="255" />
        <x-field name="auteurs" label="Auteur(s)" :value="$tfe->auteurs ?? Auth::user()->name" required maxlength="255" />
        <x-field name="annee_de_realisation" label="Année" type="select" required>
            @foreach ($annee_de_realisations as $annee)<option @if (old('annee_de_realisation', $tfe->annee_de_realisation ?? date('Y')) == $annee) selected @endif>{{ $annee }}</option>@endforeach
        </x-field>
        <x-field name="resume" label="Résumé" type="textarea" :value="$tfe->resume ?? null" full maxlength="20000" hint="Affiché publiquement avec le rapport." />
        {{ $slot ?? '' }}
    </div>
</section>
<section class="card"><h2>Stage et encadrement</h2>
    <div class="fields">
        <x-field name="maitre_memoire" label="Maître de mémoire" :value="$tfe->maitre_memoire ?? null" required maxlength="255" />
        <x-field name="email_maitre_memoire" label="Email du maître de mémoire" type="email" :value="$tfe->email_maitre_memoire ?? null" required />
        <x-field name="tuteur_de_stage" label="Tuteur de stage" :value="$tfe->tuteur_de_stage ?? null" required maxlength="255" />
        <x-field name="email_tuteur" label="Email du tuteur" type="email" :value="$tfe->email_tuteur ?? null" required />
        <x-field name="lieu_de_stage" label="Lieu de stage" :value="$tfe->lieu_de_stage ?? null" full required maxlength="255" />
    </div>
</section>
