// Função de envio de formulário de contato (AJAX)
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('contactForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(form);
      fetch('enviar_email.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        document.getElementById('formMessage').textContent = data.message;
        if (data.success) form.reset();
      })
      .catch(() => {
        document.getElementById('formMessage').textContent = 'Erro ao enviar. Tente novamente.';
      });
    });
  }
});