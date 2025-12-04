let cropper;

// Evento direto no input para ativar preview e cropper
window.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('input-cropper');
  if (input) {
    input.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
          const img = document.getElementById('cropper-preview');
          img.src = ev.target.result;
          img.style.display = 'block';
          img.style.width = '100%';
          img.style.height = 'auto';
          if (cropper) cropper.destroy();
          cropper = new Cropper(img, {
            aspectRatio: 1,
            viewMode: 2, // Limita o crop sempre dentro da imagem
            autoCropArea: 1,
            background: false,
            guides: false,
            movable: true,
            zoomable: true,
            rotatable: false,
            scalable: false,
            responsive: true,
            minContainerWidth: 320,
            minContainerHeight: 320
          });
          document.getElementById('cropper-controls').style.display = 'flex';
        };
        reader.readAsDataURL(file);
      }
    });
  }
});

// Botão salvar: envia imagem cortada
function cropAndUpload() {
  if (!cropper) return;
  cropper.getCroppedCanvas({width: 400, height: 400}).toBlob(function(blob) {
    const formData = new FormData();
    formData.append('nova_foto', blob, 'profile.png');
    fetch('upload_foto.php', {
      method: 'POST',
      body: formData
    }).then(res => {
      if (res.redirected) {
        window.location = res.url;
      } else {
        alert('Erro ao enviar imagem.');
      }
    });
  }, 'image/png');
}

// Fecha o modal e reseta o cropper
function closeCropperModal() {
  const modal = document.getElementById('profilePicModal');
  if (modal) modal.style.display = 'none';
  const input = document.getElementById('input-cropper');
  if (input) input.value = '';
  const img = document.getElementById('cropper-preview');
  if (img) {
    img.src = '';
    img.style.display = 'none';
  }
  if (cropper) {
    cropper.destroy();
    cropper = null;
  }
  document.getElementById('cropper-controls').style.display = 'none';
}
