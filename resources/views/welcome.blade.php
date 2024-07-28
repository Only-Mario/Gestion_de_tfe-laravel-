@extends('layouts.app')


<style>

    /* Modal */

    /* Modal styles */
  /* Modal styles */
  .modal {
      display: none;
      color: black;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 80%;
      height: 80%;
      overflow: auto;
    }

    .modal-content {
      background-color: white;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid blue;
      width: 80%;
      max-width: 600px;
      text-align: center; /* Center the modal content */
    }

    .closeModal {
      color: black;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover,
    .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
  
  
@section('content')

    <h3 class="text-dark mb-1 ml-3">Acceuil</h3>

    <div class="container" id="tfe">
        <div class="col">
            <h1 class="text-center text-dark mb-4" style="font-style: normal;font-family: bold;">Les derniers rapports publiés
            </h1>
            <div class="row">
                @if (count($tfes) > 0)
                    @include('layouts.sidebar')
                @endif
            </div>

            <div class="row ">
                @forelse($tfes->take(25) as $tfe)
                    <div class="col-12 pb-3">
                        <div class="card shadow">
                         <input id="tfe{{$loop->index}}" type="text" hidden value="{{$tfe->resume}}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-4"  onclick="openModal({{$loop->index}})">
                                        <div class="card-title text-dark"style="font-size: 20px"><span>
                                                {{ $tfe->theme }}</span></div>
                                        <div class="card-text text-dark"><span>Réalisé par {{ $tfe->auteurs }} en
                                                {{ $tfe->annee_de_realisation }}</span>
                                        </div>
                                    </div>
                                    <div class="col-auto mr-4">
                                        <a href="{{ route('tfe.show', $tfe) }}"><img src="images/pdf.png" width="60px">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center">
                        <h1 style="font-size: 30px;color: black;">Aucun rapport disponible pour le moment</h1>
                    </div>
                @endforelse
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

      // Event listener for close button


      window.addEventListener('load',(event)=>{
        const closeButton = document.getElementById('closeModal');
      closeButton.addEventListener('click',closeModal)
      })

      // Event listener to close the modal when clicking outside the modal content



    function openModal(index) {
        var content=document.getElementById('tfe'+index).value
        console.log(content);

        const modalContent = document.getElementById('resume');

            content==""?content="Aucun résumé ajouté à ce projet":null
        modalContent.innerHTML = `
        <h2>Résumé</h2>
          <p>${content}</p>
        `;

        const modal = document.getElementById('modal');
        modal.style.display = 'block';
      }

        // Function to close the modal
    function closeModal() {
        const modal = document.getElementById('modal');
        modal.style.display = 'none';
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
