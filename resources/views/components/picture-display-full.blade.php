@props(['idWrapper' => 'full-screen-image', 'idPicture' => 'picture-display-full'])

<div id="{{ $idWrapper }}" class="position-fixed w-100 h-100 justify-content-center align-items-center"
    style="display: none; top: 0; left: 0; background: rgba(0, 0, 0, 0.8); z-index: 9999;"
    onclick="this.style.display = 'none';">
    <img id="{{ $idPicture }}" alt="Profile Picture" class="img-fluid" style="max-width: 90%; max-height: 90%;">
</div>
