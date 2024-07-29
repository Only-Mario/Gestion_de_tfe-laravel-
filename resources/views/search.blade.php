@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        @include('layouts.sidebar')
    </div>
    <div class="row">
        <div class="col-12">
            <h1 class="text-dark pb-4">
                Résultats de recherche pour : {{ $kw }}
            </h1>
            <div class="row" id="tfe-list">
                @forelse($tfes as $index => $tfe)
                    @if ($tfe->status == 1)
                        <div class="col-12 pb-3 tfe-item" @if($index >= 25) style="display: none;" @endif>
                            <div class="card shadow">
                                <input id="tfe{{$index}}" type="text" hidden value="{{$tfe->resume}}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col mr-4" onclick="openModal({{$index}})">
                                            <div class="card-title text-dark" style="font-size: 20px"><span>
                                                    {{ $tfe->theme }}</span></div>
                                            <div class="card-text text-dark"><span>Réalisé par {{ $tfe->auteurs }} en
                                                    {{ $tfe->annee_de_realisation }} / {{ $tfe->groupe_pedagogique }}</span>
                                            </div>
                                        </div>
                                        <div class="col-auto mr-4">
                                            <a href="{{ route('tfe.show', $tfe) }}"><img src="images/pdf.png" width="60px"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p>
                        Aucun résultat trouvé
                    </p>
                @endforelse
            </div>

            @if (count($tfes) > 25)
                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn btn-primary" id="load-more-btn">Afficher plus...</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <span class="closeModal" id="closeModal">&times;</span>
        <div id="resume">
        </div>
    </div>
</div>

@stop

<script>
    window.addEventListener('click', (event) => {
        const modal = document.getElementById('modal');

        if (event.target == modal) {
            closeModal();
        }
    });

    window.addEventListener('load', (event) => {
        const closeButton = document.getElementById('closeModal');
        closeButton.addEventListener('click', closeModal);

        const loadMoreBtn = document.getElementById('load-more-btn');
        loadMoreBtn.addEventListener('click', showMoreItems);
    });

    function openModal(index) {
        var content = document.getElementById('tfe' + index).value;
        const modalContent = document.getElementById('resume');
        content == "" ? content = "Aucun résumé ajouté à ce projet" : null;
        modalContent.innerHTML = `
        <h2>Résumé</h2>
        <p>${content}</p>
        `;

        const modal = document.getElementById('modal');
        modal.style.display = 'block';
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        modal.style.display = 'none';
    }

    function showMoreItems() {
        const hiddenItems = document.querySelectorAll('.tfe-item[style*="display: none;"]');
        for (let i = 0; i < 25 && i < hiddenItems.length; i++) {
            hiddenItems[i].style.display = 'block';
        }
        if (hiddenItems.length <= 25) {
            document.getElementById('load-more-btn').style.display = 'none';
        }
    }
</script>

@section('footer')
    <footer>
        <div class="container">
            <p class="text-dark text-center">
                INSTI &copy :Tous droits réservés {{ date('Y') }}
                &middot</p>
        </div>
    </footer>
@stop
